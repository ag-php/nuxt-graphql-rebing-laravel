<?php

namespace App\GraphQL\Query;

use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Support\AuthenticatedQuery;

class DashboardSalesQuery extends AuthenticatedQuery
{
    protected $attributes = [
        'name' => 'DashboardSales'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('dashboardSalesForOps'));
    }

    public function args(): array
    {
        return [
            'start' => [
                'name' => 'start',
                'type' => Type::nonNull(Type::string())
            ],
            'end' => [
                'name' => 'end',
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {

        $start = $args['start'];
        $end = $args['end'];

        $sql = <<<SQL
            SELECT
            `item`, `type`, SUM(`amount`) AS `amount`
            FROM
                (`ops_dashboard`)
                WHERE (CONVERT( `type` USING UTF8) = "target" 
                OR CONVERT( `ddate` USING UTF8) BETWEEN "$start" AND "$end"
                )
                GROUP BY `item`, `type`

SQL;
        return DB::connection('tenant')->select($sql);
    }
}