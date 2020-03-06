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

class GraphTwoQuery extends AuthenticatedQuery
{
    protected $attributes = [
        'name' => 'GraphTwo'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('accountRevenue'));
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
            'locationId' => [
              'name' => 'locationId',
              'type' => Type::nonNull(Type::int())
            ],
            'isParent' => [
              'name' => 'isParent',
              'type' => Type::nonNull(Type::boolean())
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $location = $args['location'] && strlen($args['location']) ? trim($args['location']) : '';
        $isParent = $args['isParent'];
        $acctId = $args['locationId'];

        $andLocation = '';
        if ($location && $acctId) {
          $andLocation = !$isParent? "AND `master_fcst_layout_tbl`.`cc` = \"$location\"" : 
          "AND `master_fcst_layout_tbl`.`cc` IN (
             SELECT `costcenter_tree`.`acct`
            FROM `costcenter_tree`
            JOIN `costcenter_descendants` ON `costcenter_descendants`.`descendant_id` = `costcenter_tree`.`acct_id`
            WHERE `costcenter_descendants`.`costcenter_id` = $acctId
            )";
        }

        $groupBy = $location ? "`master_fcst_layout_tbl`.`cc` , " : "";
        $groupBy = "$groupBy `master_fcst_layout_tbl`.`acct`";

        $period = $args['period'];

        $sql = <<<SQL
    SELECT
        `master_fcst_layout_tbl`.`cc` AS `cc`,
        `master_fcst_layout_tbl`.`acct` AS `acct`,
        `master_fcst_actual`.`P$period` AS `amount`
    FROM
        (`master_fcst_actual`
        LEFT JOIN `master_fcst_layout_tbl`
        ON (((CONVERT( `master_fcst_layout_tbl`.`acct` USING UTF8) = `master_fcst_actual`.`AccountDesc`)
        AND (CONVERT( `master_fcst_layout_tbl`.`cc` USING UTF8) = `master_fcst_actual`.`Mid4Desc`))))
    WHERE
        (`master_fcst_layout_tbl`.`Parent4` = 'Income' $andLocation)
    GROUP BY $groupBy
SQL;

if ($isParent) {
  $sql = <<<SQL
  SELECT `t1`.`cc`, `t1`.`acct`, SUM(`t1`.`amount`) as `amount` FROM (
  SELECT
      `master_fcst_layout_tbl`.`cc` AS `cc`,
      `master_fcst_layout_tbl`.`acct` AS `acct`,
      `master_fcst_actual`.`P$period` AS `amount`
  FROM
      (`master_fcst_actual`
      LEFT JOIN `master_fcst_layout_tbl`
      ON (((CONVERT( `master_fcst_layout_tbl`.`acct` USING UTF8) = `master_fcst_actual`.`AccountDesc`)
      AND (CONVERT( `master_fcst_layout_tbl`.`cc` USING UTF8) = `master_fcst_actual`.`Mid4Desc`))))
  WHERE
      (`master_fcst_layout_tbl`.`Parent4` = 'Income' $andLocation)
  GROUP BY $groupBy ) as `t1` GROUP BY `t1`.`acct`
SQL;
}

        $rows = DB::connection('tenant')->select($sql);

        $d = [];
        foreach($rows as $row) {
            $d[] = [
                'location' => $row->cc ?: '',
                'account' => $row->acct,
                'amount' => $row->amount
            ];
        }
        return $d;
    }
}
