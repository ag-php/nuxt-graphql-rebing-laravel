<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class MiddleBoxForOpsType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'middleBoxForOps',
        'description'   => 'data for middle box',
    ];

    public function fields(): array
    {
        return [
            'category' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the category',
            ]
            ,
            'target' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The target of the item',
            ],
            'rankInCategory' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'rank in category for the item',
            ],
            'amount' => [
                'type' =>  Type::float(),
                'description' => 'amount for the item',
            ],
        ];
    }
}