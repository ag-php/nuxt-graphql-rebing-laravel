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

class GraphOneTotalsQuery extends AuthenticatedQuery
{
    protected $attributes = [
        'name' => 'GraphTwo'
    ];

    public function type(): Type
    {
        return Type::float();
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

        $andLocation = $location ? "AND `master_fcst_web`.`Mid4Desc` = \"$location\"" : '';

        $groupBy = "`master_fcst_web`.`FCSTDesc`" . ($location ? " , `master_fcst_web`.`Mid4Desc`" : '');

        $period = $args['period'];

        $selector = $args['selector'];

        $sql = <<<SQL
    SELECT
        `master_fcst_web`.`FCSTDesc` AS `selector`,
        `master_fcst_web`.`Mid4Desc` AS `location`,
        SUM(`master_fcst_web`.`P$period`) AS `revenue`
    FROM
        (`master_fcst_web`
        LEFT JOIN `master_fcst_layout_tbl`
        ON ((`master_fcst_web`.`AccountDesc` = CONVERT( `master_fcst_layout_tbl`.`acct` USING UTF8))))
    WHERE
        (`master_fcst_layout_tbl`.`Parent4` = 'income'
        AND `master_fcst_web`.`FCSTDesc` = "$selector"
        $andLocation)
    GROUP BY $groupBy
SQL;

        Log::info($sql);

        $row = DB::connection('tenant')->select($sql)[0];

        return $row->revenue;
    }
}