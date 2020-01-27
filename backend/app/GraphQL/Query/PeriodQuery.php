<?php

namespace App\GraphQL\Query;


use Closure;
use App\Period;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Support\AuthenticatedQuery;

class PeriodQuery extends AuthenticatedQuery
{
    protected $attributes = [
        'name' => 'Period'
    ];

    public function type(): Type
    {
        return GraphQL::type('period');
    }

    public function args(): array
    {
        return [
            'period' => [
                'name' => 'period',
                'type' => Type::nonNull(Type::int())
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {

        $period = Period::getByNumber(intval($args['period']));

        if (!$period) {
            return Error('Period not found');
        }

        return [
            'id' => $period->id,
            'description' => $period->Desc,
            'month' => $period->Month,
            'year' => $period->Year,
            'period' => $period->Period,
        ];
    }
}