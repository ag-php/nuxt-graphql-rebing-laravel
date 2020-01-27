<?php

namespace App\GraphQL\Support;

trait Admin
{
    /**
     * Authenticated user
     *
     * @var \App\User
     */
    protected $user;

    /**
     * Security check for endpoints
     *
     * @param array $args
     * @return boolean
     */
    public function authorize(array $args): bool
    {
        $this->user = auth('api')->user();
        return $this->user ? ($this->user->role_id == 1) : false ;
    }

}