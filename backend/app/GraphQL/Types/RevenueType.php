<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class RevenueType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Revenue',
        'description'   => 'A row in a revenue table',
    ];

    public function fields(): array
    {
        return [
            'location' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Location of revenue row',
            ],
            'y' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Year',
            ],
            'm' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Month',
            ],
            'p' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Period descriptor',
            ],
            'amount' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Revenue amount',
            ]
        ];
    }
}