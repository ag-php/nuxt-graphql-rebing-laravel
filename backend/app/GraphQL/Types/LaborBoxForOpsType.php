<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class LaborBoxForOpsType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'LaborBoxForOps',
        'description'   => 'data for middle box',
    ];

    public function fields(): array
    {
        return [
            'target' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The target of the item',
            ],
            'itemDescription' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'item description',
            ],
            'amount' => [
                'type' =>  Type::float(),
                'description' => 'amount for the item',
            ],
        ];
    }
}