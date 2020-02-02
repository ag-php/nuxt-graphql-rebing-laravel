<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class DashboardSalesForOpsType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'DashboardSalesForOps',
        'description'   => 'dashboard sales parameters',
    ];

    public function fields(): array
    {
        return [
            'item' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the route',
            ],
            'type' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'URI path of route',
            ],
            'amount' => [
                'type' => Type::float(),
                'description' => 'Icon for path',
            ],
        ];
    }
}