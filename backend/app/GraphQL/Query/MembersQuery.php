<?php

namespace App\GraphQL\Query;


use Closure;
use App\Organization;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Support\AuthenticatedQuery;

class MembersQuery extends AuthenticatedQuery
{
    protected $attributes = [
        'name' => 'Members'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('user'));
    }

    public function args(): array
    {
        return [
            'organization_id' => [
                'name' => 'organization_id',
                'type' => Type::nonNull(Type::int())
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {

        $organization = Organization::find($args['organization_id']);

        if (!$organization) {
            return Error('Organization not found');
        }

        if (($organization->user_id == $this->user->id) || $this->user->isAdmin()) {

            return $organization->users;
        }

        return new Error('Organzations query failed');
    }
}