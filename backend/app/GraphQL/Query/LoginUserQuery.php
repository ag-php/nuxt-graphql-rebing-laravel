<?php

namespace App\GraphQL\Query;

use Closure;
use App\User;
use GraphQL\Error\Error;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Hash;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Facades\GraphQL;

class LoginUserQuery extends Query
{
    protected $attributes = [
        'name' => 'Login query'
    ];

    public function type(): Type
    {
        return Type::nonNull(GraphQL::type('token'));
    }

    public function args(): array
    {
        return [
            'email' => [
                'name' => 'email',
                'type' => Type::nonNull(Type::string())
            ],
            'password' => [
                'name' => 'password',
                'type' => Type::nonNull(Type::string())
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $user = User::where('email', $args['email'])->first();

        if ($user) {

            if (Hash::check($args['password'], $user->password)) {

                $token = auth('api')->login($user);

                return $this->respondWithToken($token);
            }

        }

        return new Error('Failed login');
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return array
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }
}