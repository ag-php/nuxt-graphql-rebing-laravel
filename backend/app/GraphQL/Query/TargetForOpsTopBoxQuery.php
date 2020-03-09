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

class TargetForOpsTopBoxQuery extends AuthenticatedQuery
{
  protected $attributes = [
    'name' => 'TargetForOpsTopBox'
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type('targetForOpsTopBox'));
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
      ]
    ];
  }

  public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
  {
    $location = $args['location'] && strlen($args['location']) ? trim($args['location']) : '';
    $isParent = $args['isParent'];
    $acctId = $args['locationId'];

    $andLocation = '';
    if ($location && $acctId) {
      $andLocation = !$isParent ? "AND `store` = \"$location\"" :
        "AND `store` IN (
             SELECT `costcenter_tree`.`acct`
            FROM `costcenter_tree`
            JOIN `costcenter_descendants` ON `costcenter_descendants`.`descendant_id` = `costcenter_tree`.`acct_id`
            WHERE `costcenter_descendants`.`costcenter_id` = $acctId
          )";
    }

    $period = $args['period'];

    $sql = <<<SQL
      SELECT
        `drivers_rates`.`Store` AS `store`,
        `graph_setup_daily`.`dashboardref` AS `item`,
        `drivers_rates`.`P$period` AS `amount`
        FROM
        (`drivers_rates`
        JOIN `graph_setup_daily` ON ((`graph_setup_daily`.`qboaccount` = `drivers_rates`.`Account`)))
        WHERE ((RIGHT(`graph_setup_daily`.`dashboardref`,3) = 'cgs') 
        $andLocation)

SQL;

if ($isParent) {
  $sql = <<<SQL
  SELECT `t1`.`store`, `t1`.`item`, SUM(`t1`.`amount`) as `amount` FROM (
    SELECT
        `drivers_rates`.`Store` AS `store`,
        `graph_setup_daily`.`dashboardref` AS `item`,
        `drivers_rates`.`P$period` AS `amount`
        FROM
        (`drivers_rates`
        JOIN `graph_setup_daily` ON ((`graph_setup_daily`.`qboaccount` = `drivers_rates`.`Account`)))
        WHERE ((RIGHT(`graph_setup_daily`.`dashboardref`,3) = 'cgs') 
        $andLocation)
    ) as `t1` GROUP BY `t1`.`item`
SQL;
}

    return DB::connection('tenant')->select($sql);
  }
}

