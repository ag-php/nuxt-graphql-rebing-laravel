<?php

namespace App\GraphQL\Types;

use App\Role;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class RoleType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Role',
        'description'   => 'A user role',
        'model'         => Role::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Expiration of token',
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The JWT token',
            ],
        ];
    }
}