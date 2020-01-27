<?php

namespace App\GraphQL\Types;

use App\Membership;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class MembershipType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Membership',
        'description'   => 'A user organization membership',
        'model'         => Membership::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Membership id',
            ],
            'user' => [
                'type' => GraphQL::type('user'),
                'description' => 'Membership user'
            ],
            'organization' => [
                'type' => GraphQL::type('organization'),
                'description' => 'Membership organization'
            ],
        ];
    }
}