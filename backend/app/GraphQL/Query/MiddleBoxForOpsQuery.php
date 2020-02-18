<?php

namespace App\GraphQL\Query;

use Log;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Support\AuthenticatedQuery;

class MiddleBoxForOpsQuery extends AuthenticatedQuery
{
    protected $attributes = [
        'name' => 'MiddleBoxForOps'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('middleBoxForOps'));
    }

    public function args(): array
    {
        return [
            'location' => [
                'name' => 'location',
                'type' => Type::string()
            ],
            'period' => [
                'name' => 'period',
                'type' => Type::nonNull(Type::int())
            ],
            'selector' => [
                'name' => 'selector',
                'type' => Type::nonNull(Type::string())
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $location = $args['location'] && strlen($args['location']) ? trim($args['location']) : '';

        $andLocation = $location ? "AND `Mid4Desc` = \"$location\"" : '';

        $period = $args['period'];

        $selector = $args['selector'];

        $sql = <<<SQL
            SELECT
            `category`, `FCSTDesc` AS `target`, `rank_in_category` AS `rankInCategory`, `P$period` AS `amount`
            FROM
                (`ops_dashboard_monthly`)
            WHERE (
                (`FCSTDesc` = "$selector" OR `FCSTDesc` = "actuals") 
                $andLocation
            )
SQL;
        Log::info($sql);

        return DB::connection('tenant')->select($sql);
    }
}