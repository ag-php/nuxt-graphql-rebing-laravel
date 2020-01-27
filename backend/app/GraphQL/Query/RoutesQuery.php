<?php

namespace App\GraphQL\Query;

use Closure;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Support\AuthenticatedQuery;

class RoutesQuery extends AuthenticatedQuery
{
    protected $enforceEmailVerification = false;

    protected $attributes = [
        'name' => 'Routes query'
    ];

    public function type(): Type
    {
        return Type::nonNull(Type::listOf(GraphQL::type('route')));
    }

    public function args(): array
    {
        return [
            'admin' => [
                'name' => 'admin',
                'type' => Type::boolean()
            ]
        ];
    }


    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $routes =  [];

        if (isset($args['admin']) && $args['admin']) {
            if ($this->user->isAdmin()) {
                $routes =  [
                    [
                        'name' => 'Manage Users',
                        'path' => '/admin/users',
                        'icon' => 'peoples',
                    ],
                    [
                        'name' => 'Manage Organizations',
                        'path' => '/admin/organizations',
                        'icon' => 'tree-table',
                    ],
                ];
            }
        } else {
            $routes = [
                [
                    'name' => 'Dashboard',
                    'path' => '/dashboard',
                    'icon' => 'dashboard',
                ],

                // *** Temp Route ***
                [
                    'name' => 'Dashboard II',
                    'path' => '/dashboard2',
                    'icon' => 'dashboard',
                ],

            ];

            if ($this->user->approved) {

                $routes = array_merge($routes, [
                    [
                        'name' => 'Organizations',
                        'path' => '/organizations',
                        'icon' => 'tree',
                    ],
                    // [
                    //     'name' => 'Reports',
                    //     'path' => '/reports',
                    //     'icon' => 'list',
                    // ],
                    // [
                    //     'name' => 'Data Edit',
                    //     'path' => '/data',
                    //     'icon' => 'edit',
                    // ]
                ]);
            }
        }

        return $routes;
    }
}