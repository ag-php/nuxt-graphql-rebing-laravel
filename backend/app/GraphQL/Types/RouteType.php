<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class RouteType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Route',
        'description'   => 'An app route',
    ];

    public function fields(): array
    {
        return [
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the route',
            ],
            'path' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'URI path of route',
            ],
            'icon' => [
                'type' => Type::string(),
                'description' => 'Icon for path',
            ],
        ];
    }
}