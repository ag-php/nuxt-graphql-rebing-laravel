<?php

namespace App\GraphQL\Mutation;

use Closure;
use GraphQL;
use App\User;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use App\GraphQL\Support\AdminMutation;
use GraphQL\Type\Definition\ResolveInfo;


class DisapproveUserMutation extends AdminMutation
{
    protected $attributes = [
        'name' => 'DisapproveUser'
    ];

    public function type(): Type
    {
        return Type::nonNull(GraphQL::type('user'));
    }

    public function args(): array
    {
        return [
            'user_id' => [
                'name' => 'user_id',
                'type' => Type::nonNull(Type::int())
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $user = User::find($args['user_id']);

        if ($user) {

            $user->approved = 0;
            $user->save();
            return $user;

        } else {

            return new Error('User not found');
        }
    }

}