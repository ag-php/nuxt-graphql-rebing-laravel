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
            `category`, `FCSTDesc` AS `target`, `rank_in_category` AS `rankInCategory`, `P$period` AS `amount`
            FROM
                (`ops_dashboard_monthly`)
            WHERE (
                (`FCSTDesc` = "$selector" OR `FCSTDesc` = "actuals") 
                AND (`category` = 'totalsales' OR `category` = 'opex')
                $andLocation
            )
SQL;


    return DB::connection('tenant')->select($sql);
  }
}
