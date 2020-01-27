<?php

namespace App\GraphQL\Query;

use App\User;
use GraphQL\Type\Definition\Type;
use App\GraphQL\Support\AdminQuery;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UsersQuery extends AdminQuery
{

    protected $enforceEmailVerification = false;


    protected $attributes = [
        'name' => 'Users query'
    ];

    public function type(): Type
    {
        return Type::nonNull(Type::listOf(GraphQL::type('user')));
    }

    public function args(): array
    {
        return [];
    }


    public function resolve()
    {
        return User::all();
    }
}