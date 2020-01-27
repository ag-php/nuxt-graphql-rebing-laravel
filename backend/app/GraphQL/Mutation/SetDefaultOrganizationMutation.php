<?php

namespace App\GraphQL\Mutation;

use Closure;
use GraphQL;
use GraphQL\Type\Definition\Type;
use App\Services\MultiTenancyService;
use GraphQL\Type\Definition\ResolveInfo;
use App\GraphQL\Support\AuthenticatedMutation;

class SetDefaultOrganizationMutation extends AuthenticatedMutation
{
    protected $attributes = [
        'name' => 'SetDefaultOrganization'
    ];

    public function type(): Type
    {
        return Type::boolean();
    }

    public function args(): array
    {
        return [
            'organization_id' => [
                'name' => 'organization_id',
                'type' => Type::nonNull(Type::int())
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $tenancy = new MultiTenancyService($this->user);

        try {
            $tenancy->setDefaultOrganization($args['organization_id']);
            return true;
        } catch (\Exception $e) {
            return false;
        }

    }
}