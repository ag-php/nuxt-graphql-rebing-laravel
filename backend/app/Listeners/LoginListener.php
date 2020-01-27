<?php

namespace App\Listeners;

use App\Services\MultiTenancyService;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the login event.
     * Load organizational tenant for user
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        if ($event->user) {

            $tenancy = new MultiTenancyService($event->user);

            $organization = $tenancy->getDefaultOrganization();

            if (!$organization) {

                $organization = $tenancy->getAnyOrganization();

                if ($organization) {

                    $tenancy->setDefaultOrganization($organization->id);
                }
            }

            if ($organization) {

                $tenancy->selectOrganization($organization);
            }
        }
    }
}
