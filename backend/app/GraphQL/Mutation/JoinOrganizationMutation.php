<?php

namespace App\GraphQL\Mutation;

use Closure;
use GraphQL;
use App\User;
use App\Organization;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use App\Services\MultiTenancyService;
use GraphQL\Type\Definition\ResolveInfo;
use App\GraphQL\Support\AuthenticatedMutation;

class JoinOrganizationMutation extends AuthenticatedMutation
{
    protected $attributes = [
        'name' => 'JoinOrganization'
    ];

    public function type(): Type
    {
        return GraphQL::type('membership');
    }

    public function args(): array
    {
        return [
            'organization_id' => [
                'name' => 'organization_id',
                'type' => Type::nonNull(Type::int())
            ],
            'user_id' => [
                'name' => 'user_id',
                'type' => Type::int()
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (isset($args['user_id']) && $args['user_id']) {
            if ($this->user->isAdmin()) {
                $user = User::find($args['user_id']);
                if ($user) {
                    $tenancy = new MultiTenancyService($user);
                    $organization = Organization::find($args['organization_id']);
                } else {
                    return new Error('User not found');
                }
            } else {
                return new Error('Operation not allowed');
            }
        } else {
            $tenancy = new MultiTenancyService($this->user);
            $organization = $this->user->organizations()->where('id', $args['organization_id'])->first();
        }

        if ($organization) {
            return $tenancy->joinOrganization($organization);
        } else {
            return new Error('Organization not found');
        }
    }
}