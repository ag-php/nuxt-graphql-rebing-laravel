<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CategoryType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Category',
        'description'   => 'Product category type',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID',
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Category name',
            ],
        ];
    }
}