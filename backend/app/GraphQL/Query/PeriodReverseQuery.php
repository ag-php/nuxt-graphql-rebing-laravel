<?php

namespace App\GraphQL\Query;


use Closure;
use App\Period;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Support\AuthenticatedQuery;

class PeriodReverseQuery extends AuthenticatedQuery
{
    protected $attributes = [
        'name' => 'PeriodReverse'
    ];

    public function type(): Type
    {
        return Type::int();
    }

    public function args(): array
    {
        return [
            'month' => [
                'name' => 'month',
                'type' => Type::nonNull(Type::string())
            ],
            'year' => [
                'name' => 'year',
                'type' => Type::nonNull(Type::string())
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $period = Period::getOrdinalByMonthYear($args['month'], $args['year']);

        return $period ?: new Error('Period not found');
    }
}