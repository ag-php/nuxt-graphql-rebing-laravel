<?php

namespace App\GraphQL\Mutation;

use Closure;
use GraphQL;
use GraphQL\Type\Definition\Type;
use App\Services\MultiTenancyService;
use GraphQL\Type\Definition\ResolveInfo;
use App\GraphQL\Support\AuthenticatedMutation;

class CreateOrganizationMutation extends AuthenticatedMutation
{
    protected $attributes = [
        'name' => 'CreateOrganization'
    ];

    public function type(): Type
    {
        return GraphQL::type('organization');
    }

    public function args(): array
    {
        return [
            'name' => [
                'name' => 'name',
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $tenancy = new MultiTenancyService($this->user);

        return $tenancy->createOrganization($args['name']);
    }
}