<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AccountRevenueType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'AccountRevenue',
        'description'   => 'A row in a revenue table',
    ];

    public function fields(): array
    {
        return [
            'location' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Location of revenue row',
            ],
            'account' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Account',
            ],
            'amount' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Revenue amount',
            ]
        ];
    }
}