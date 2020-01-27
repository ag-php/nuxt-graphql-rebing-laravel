<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PeriodType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Period',
        'description'   => 'A month time period',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID',
            ],
            'description' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Text description',
            ],
            'month' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Short month text name',
            ],
            'year' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Year of period',
            ],
            'period' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Ordinal number of period (1-60)',
            ],
        ];
    }
}