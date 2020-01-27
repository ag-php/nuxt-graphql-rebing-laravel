<?php

namespace App\GraphQL\Query;


use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Support\AuthenticatedQuery;
use App\Organization;

class OrganizationsQuery extends AuthenticatedQuery
{
    protected $attributes = [
        'name' => 'Organizations'
    ];

    public function type(): Type
    {
        return Type::nonNull(Type::listOf(GraphQL::type('organization')));
    }

    public function args(): array
    {
        return [
            'all' => [
                'name' => 'all',
                'type' => Type::boolean()
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (isset($args['all']) && $args['all']) {
            if ($this->user->isAdmin()) {
                $organizations = Organization::all();
            } else {
                return new Error('Query not permitted');
            }
        } else {
            $organizations = $this->user->organizations;
        }

        if ($organizations) {
            return $organizations;
        }

        return new Error('Organzations query failed');
    }
}