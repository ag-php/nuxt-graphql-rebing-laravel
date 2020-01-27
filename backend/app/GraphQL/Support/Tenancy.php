<?php

namespace App\GraphQL\Support;

use App\GraphQL\Support\RateLimiter;
use App\Services\MultiTenancyService;

trait Tenancy
{
    /**
     * Authenticated user
     *
     * @var \App\User
     */
    protected $user;

    /**
     * Whether to enforce email verification in this operation
     *
     * @var boolean
     */
    protected $enforceEmailVerification = true;

    /**
     * Security check for endpoints
     *
     * @param array $args
     * @return boolean
     */
    public function authorize(array $args): bool
    {
        $rateLimiter = new RateLimiter('authorize', 30, 10);

        if ($rateLimiter->test()) {
            return false;
        }

        $this->user = auth('api')->user();

        if (!$this->user || get_class($this->user) !== 'App\User') {

            $rateLimiter->logEvent();

            return false;
        }

        (new MultiTenancyService($this->user))->selectDefaultOrganization();

        return $this->enforceEmailVerification ? $this->user->hasVerifiedEmail() : true;
    }
}