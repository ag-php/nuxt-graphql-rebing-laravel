<?php

namespace App\GraphQL\Query;

use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Support\AuthenticatedQuery;

class UserQuery extends AuthenticatedQuery
{

    protected $enforceEmailVerification = false;


    protected $attributes = [
        'name' => 'User query'
    ];

    public function type(): Type
    {
        return Type::nonNull(GraphQL::type('user'));
    }

    public function args(): array
    {
        return [];
    }


    public function resolve()
    {
        return $this->user ?: new Error('Failed login');
    }
}