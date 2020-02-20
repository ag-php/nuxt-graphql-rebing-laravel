<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class LocationType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Location',
        'description'   => 'locations',
    ];

    public function fields(): array
    {
        return [
            'acct_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID',
            ],
            'acct' => [
                'type' => Type::nonNull(Type::string()),
                'acct name' => 'Text description',
            ],
            'parent' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'is parent',
            ],
        ];
    }
}