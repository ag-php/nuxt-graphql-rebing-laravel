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

class GraphSixIndividualQuery extends AuthenticatedQuery
{
  protected $attributes = [
    'name' => 'GraphSixIndividual'
  ];

  public function type(): Type
  {
    return Type::listOf(Type::float());
  }

  public function args(): array
  {
    return [
      'category' => [
        'name' => 'category',
        'type' => Type::nonNull(Type::int())
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
      'startP' => [
        'name' => 'startP',
        'type' => Type::nonNull(Type::int())
      ],
      'endP' => [
        'name' => 'endP',
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
    $category = $args['category'];

    $location = $args['location'];
    $isParent = $args['isParent'];
    $acctId = $args['locationId'];

    $andLocation = '';
    if ($location && $acctId) {
      $andLocation = !$isParent ? "AND `master_fcst_web`.`Mid4Desc` = \"$location\"" :
        "AND `master_fcst_web`.`Mid4Desc` IN (
           SELECT `costcenter_tree`.`acct`
            FROM `costcenter_tree`
            JOIN `costcenter_descendants` ON `costcenter_descendants`.`descendant_id` = `costcenter_tree`.`acct_id`
            WHERE `costcenter_descendants`.`costcenter_id` = $acctId
          )";
    }

    $groupBy = $location ? ", `master_fcst_web`.`Mid4Desc`" : '';

    $selector = $args['selector'];
    $startP = intval($args['startP']);
    $endP = intval($args['endP']);

    $sql = <<<SQL
    SELECT
        `master_fcst_web`.`FCSTDesc` AS `FCSTDesc`,
        `master_fcst_web`.`Mid4Desc` AS `Mid4Desc`,

SQL;



    for ($i = $startP; $i <= $endP; $i++) {

      $sql = $sql . "SUM(`master_fcst_web`.`P$i`) AS `P$i`" . (($i < $endP) ? ',' : '');
    }

    $sql = $sql . <<<SQL

    FROM
        `master_fcst_web`
    WHERE
        `master_fcst_web`.`FCSTDesc` = "$selector"
        $andLocation
        AND
        (`master_fcst_web`.`AccountDesc` = CONVERT( (SELECT
                `graph_setup`.`item`
            FROM
                `graph_setup`
            WHERE
                (`graph_setup`.`category` = "COGS Individual" AND
                 `graph_setup`.`rank_in_category` = $category)) USING UTF8))
    GROUP BY `master_fcst_web`.`FCSTDesc` $groupBy
SQL;

    if (!$isParent) {
      $row = DB::connection('tenant')->select($sql)[0];
      $d = [];
      for ($i = $startP; $i <= $endP; $i++) {
        $d[] = floatval($row->{"P$i"});
      }
      return $d;
    } else {
      $data = DB::connection('tenant')->select($sql);
      $d = array_fill(0, $endP - $startP + 1, 0);
      foreach ($data as $row) {
        for ($i = $startP; $i <= $endP; $i++) {
          $d[$i - $startP] += floatval($row->{"P$i"});
        }
      }
      return $d;
    }
  }
}
