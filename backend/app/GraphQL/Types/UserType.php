<?php

namespace App\GraphQL\Types;

use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'User',
        'description'   => 'A user',
        'model'         => User::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The id of the user',
            ],
            'role_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The role of the user',
            ],
            'approved' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Can the user create organizations',
            ],
            'default_organization' => [
                'type' => Type::int(),
                'description' => 'Last used organization id',
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of user',
            ],
            'email' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The email of user',
            ],
            'email_verified_at' => [
                'type' => Type::string(),
                'description' => 'The time user\'s email was verified',
            ],
        ];
    }
}
