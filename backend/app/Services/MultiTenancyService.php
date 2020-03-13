<?php

namespace App\Services;

use Log;
use App\User;
use Exception;
use App\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Collection;


class MultiTenancyService
{

  /**
   * Authenticated user
   *
   * @var User
   */
  protected $user;

  /**
   * Create instance
   *
   * @param User $user
   */
  public function __construct(User $user)
  {
    $this->user = $user;
  }

  /**
   * Get user organizations
   *
   * @return Collection
   */
  public function getOrganizations(): Collection
  {
    return $this->user->organizations;
  }

  /**
   * Creates organization
   *
   * @param string $name
   * @return Organization
   */
  public function createOrganization(string $name): Organization
  {
    if (!$this->user->approved) {
      throw new Exception('User not approved');
    }

    DB::beginTransaction();

    $organization = Organization::create([
      'user_id' => $this->user->id,
      'name' => $name,
    ]);

    $this->joinOrganization($organization);

    if (!$this->user->default_organization) {
      $this->user->default_organization = $organization->id;
      $this->user->save();
    }

    $dbName = str_replace(' ', '_', strtolower($name));

    $organization->tenant()->create([
      'database' => $dbName
    ]);

    if ($this->createTenantDatabase($dbName)) {

      $this->selectTenantDatabase($dbName);
      $this->migrateTenantDatabase();

      DB::commit();
    } else {

      DB::rollBack();

      throw new Exception('Unable to create tenant database');
    }

    $organization->refresh();

    return $organization;
  }

  /**
   * Join a user to an organization
   *
   * @param Organization $organization
   * @return \App\Membership
   */
  public function joinOrganization(Organization $organization)
  {
    return $organization->memberships()->create([
      'user_id' => $this->user->id,
    ]);
  }

  /**
   * Leave organization
   *
   * @param Organization $organization
   * @return void
   */
  public function leaveOrganization(Organization $organization)
  {
    $organization
      ->memberships()
      ->where('user_id', $this->user->id)
      ->delete();
  }

  /**
   * Removes organization
   *
   * @param int $organizationId
   * @return bool
   */
  public function removeOrganization(int $organizationId): bool
  {
    $organization = $this->user->organizations()->find($organizationId);

    if ($organization) {

      if ($this->user->default_organization == $organizationId) {
        $this->user->update(['default_organization' => null]);
      }

      if ($organization->tenant->name) {
        $this->dropTenantDatabase($organization->tenant->name);
      }

      if ($organization->tenant) {
        $organization->tenant->delete();
      }

      if ($organization->memberships()->count()) {
        $organization->memberships()->delete();
      }

      $organization->delete();

      return true;
    } else {

      return false;
    }
  }

  /**
   * Creates a tenant database for the organization
   *
   * @param string $dbName
   * @return boolean
   */
  protected function createTenantDatabase(string $dbName): bool
  {
    $newDB = DB::statement("CREATE DATABASE $dbName");


    return !!$newDB;
  }

  /**
   * Deletes a tenant database
   *
   * @param string $dbName
   * @return boolean
   */
  protected function dropTenantDatabase(string $dbName): bool
  {
    $dropDB = DB::statement("DROP DATABASE $dbName");
    return !!$dropDB;
  }

  /**
   * Runs migrations for tenant database
   *
   * @return void
   */
  protected function migrateTenantDatabase()
  {
    return Artisan::call('migrate', [
      '--path' => 'database/migrations/tenant',
    ]);
  }

  /**
   * Selects the user's organization
   *
   * @param integer|Organization $organization
   */
  public function selectOrganization($organization)
  {
    if (is_int($organization)) {
      $organization = $this->user->organizations()->find($organization);
    }

    if ($organization && get_class($organization) === 'App\Organization') {
      $this->selectTenantDatabase($organization->tenant->database);
    } else {
      throw new Exception('User not member of organization');
    }
  }

  /**
   * Selects the user's organization
   *
   */
  public function selectDefaultOrganization()
  {
    $organization = Organization::find($this->user->default_organization);
    $this->selectTenantDatabase($organization->tenant->database);
  }

  /**
   * Set the user's default organization
   *
   * @param integer $organizationId
   * @return boolean
   */
  public function setDefaultOrganization(int $organizationId): bool
  {
    $this->user->default_organization = $organizationId;
    return $this->user->save();
  }


  /**
   * Get default organization for user
   *
   * @return Organization|null
   */
  public function getDefaultOrganization(): ?Organization
  {
    $defaultOrganizationId = $this->user->default_organization;
    return $defaultOrganizationId ? $this->user->organizations()->where('id', $defaultOrganizationId)->first() : null;
  }

  public function getDefaultOrganizationName(): ?string
  {
    $organization = Organization::find($this->user->default_organization);
    return $organization ? $organization->tenant->database : null;
  }

  /**
   * Get any of the user's organizations, if any
   *
   * @return Organization|null
   */
  public function getAnyOrganization(): ?Organization
  {
    return $this->user->organizations()->first();
  }

  /**
   * Selects the tenant database
   *
   * @param string $dbName
   * @return void
   */
  public function selectTenantDatabase(string $dbName)
  {
    Config::set('database.connections.tenant.database', $dbName);
    DB::purge('tenant');
    DB::reconnect('tenant');
  }
}
