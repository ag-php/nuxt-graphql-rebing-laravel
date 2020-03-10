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

class LaborBoxForOpsQuery extends AuthenticatedQuery
{
  protected $attributes = [
    'name' => 'LaborBoxForOps'
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type('laborBoxForOps'));
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
      $andLocation = !$isParent ? "AND `Mid4Desc` = \"$location\"" :
        "AND `Mid4Desc` IN (
             SELECT `costcenter_tree`.`acct`
            FROM `costcenter_tree`
            JOIN `costcenter_descendants` ON `costcenter_descendants`.`descendant_id` = `costcenter_tree`.`acct_id`
            WHERE `costcenter_descendants`.`costcenter_id` = $acctId
          )";
    }

    $period = $args['period'];

    $selector = $args['selector'];

    $sql = <<<SQL
            SELECT
            `FCSTDesc` AS `target`, `itemdescription` AS `itemDescription`, `P$period` AS `amount`
            FROM
                (`ops_dashboard_monthly`)
            WHERE (
                (`FCSTDesc` = "actuals") 
                AND `category` = 'metrics'
                $andLocation
            )
SQL;

    if ($isParent) {
      $sql = <<<SQL
        SELECT `t1`.`target`, `t1`.`itemDescription`,SUM(`t1`.`amount`) AS `amount` FROM(
          SELECT  `FCSTDesc` AS `target`, `itemdescription` AS `itemDescription`, `P$period` AS `amount`
          FROM
              (`ops_dashboard_monthly`)
          WHERE (
            (`FCSTDesc` = "actuals" ) 
            AND `category` = 'metrics'
            $andLocation
          )
        ) AS `t1` GROUP BY `t1`.`itemDescription`, `t1`.`target`
SQL;
    }

    return DB::connection('tenant')->select($sql);
  }
}
