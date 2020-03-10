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
      ],
      'location' => [
        'name' => 'location',
        'type' => Type::string()
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
      $andLocation = !$isParent ? "AND `store` = \"$location\"" :
        "AND `store` IN (
           SELECT `costcenter_tree`.`acct`
            FROM `costcenter_tree`
            JOIN `costcenter_descendants` ON `costcenter_descendants`.`descendant_id` = `costcenter_tree`.`acct_id`
            WHERE `costcenter_descendants`.`costcenter_id` = $acctId
                )";
    }

    $start = $args['start'];
    $end = $args['end'];

    $sql = <<<SQL
          SELECT
          `item`, `type`, SUM(`amount`) AS `amount`
          FROM
              (`ops_dashboard`)
              WHERE ((CONVERT( `type` USING UTF8) = "target" 
              OR CONVERT( `ddate` USING UTF8) BETWEEN "$start" AND "$end")
              $andLocation
              )
              GROUP BY `item`, `type`

SQL;

    return DB::connection('tenant')->select($sql);
  }
}
