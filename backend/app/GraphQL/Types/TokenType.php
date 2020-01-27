<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TokenType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Token',
        'description'   => 'An auth token',
    ];

    public function fields(): array
    {
        return [
            'access_token' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The JWT token',
            ],
            'token_type' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Type of token use',
            ],
            'expires_in' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Expiration of token',
            ],
        ];
    }
}