<?php

namespace App\GraphQL\Mutation;

use DB;
use GraphQL;
use Closure;
use App\User;
use Exception;
use Validator;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use GraphQL\Type\Definition\ResolveInfo;


class RegisterUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'RegisterUser'
    ];

    public function type(): Type
    {
        return Type::nonNull(GraphQL::type('token'));
    }

    public function args(): array
    {
        return [
            'name' => [
                'name' => 'name',
                'type' => Type::nonNull(Type::string())
            ],
            'email' => [
                'name' => 'email',
                'type' => Type::nonNull(Type::string())
            ],
            'password' => [
                'name' => 'password',
                'type' => Type::nonNull(Type::string())
            ],
            'no_login' => [
                'name' => 'no_login',
                'type' => Type::boolean()
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if ($this->validate($args)) {

            try {

                DB::beginTransaction();

                $user = $this->create($args);

                if (isset($args['no_login']) && $args['no_login']) {

                } else {
                    $token = auth('api')->login($user);
                }


            } catch(Exception $e) {

                DB::rollBack();
                return new Error($e->getMessage());
            }

            DB::commit();

            event(new Registered($user));

            if (isset($args['no_login']) && $args['no_login']) {
                return [
                    'access_token' => '',
                    'token_type' => '',
                    'expires_in' => ''
                ];
            } else {
                return $this->respondWithToken($token);
            }
        }

        return new Error('Error creating user');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return bool
     */
    protected function validate(array $data)
    {
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        return $validator->passes();
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
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