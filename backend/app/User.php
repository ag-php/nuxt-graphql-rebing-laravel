<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'default_organization', 'approved'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The role that belong to the user.
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function memberships()
    {
        return $this->hasMany('App\Membership');
    }

    /**
     * The organizations that belong to the user.
     */
    public function organizations()
    {
        return $this->belongsToMany('App\Organization', 'memberships', 'user_id', 'organization_id');

    }

    /**
     * Where the logged in user is an admin
     *
     * @return boolean
     */
    public function isAdmin(): bool
    {
        return intval($this->role_id) === 1;
    }

    /**
     * Whether the loged in user has rights to create an organization
     *
     * @return boolean
     */
    public function isApproved(): bool
    {
        return !!$this->approved;
    }

    /**
     * Approves a user for organization creation
     *
     * @return User
     */
    public function approve(): User
    {
        $this->approved = 1;
        $this->save();
        return $this;
    }

    /**
     * Disapproves a user for organization creation
     *
     * @return User
     */
    public function disapprove(): User
    {
        $this->approved = 0;
        $this->save();
        return $this;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
