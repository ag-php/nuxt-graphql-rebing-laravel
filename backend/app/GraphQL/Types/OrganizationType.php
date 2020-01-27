<?php

namespace App\GraphQL\Types;

use App\Organization;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class OrganizationType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Organization',
        'description'   => 'An organization',
        'model'         => Organization::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The id of the user',
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of user',
            ],
            'status' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The email of user',
            ],
        ];
    }
}
