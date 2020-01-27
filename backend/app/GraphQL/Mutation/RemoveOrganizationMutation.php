<?php

namespace App\GraphQL\Mutation;

use Closure;
use GraphQL;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use App\Services\MultiTenancyService;
use GraphQL\Type\Definition\ResolveInfo;
use App\GraphQL\Support\AuthenticatedMutation;

class RemoveOrganizationMutation extends AuthenticatedMutation
{
    protected $attributes = [
        'name' => 'RemoveOrganization'
    ];

    public function type(): Type
    {
        return Type::nonNull(Type::boolean());
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

        $organization = $this->user->organizations()->where('organization_id', $args['organization_id'])->first();

        if ($organization) {
            return $tenancy->removeOrganization($args['organization_id']);
        }

        return new Error('Organization not found');
    }
}