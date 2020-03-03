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

class GraphFiveCOGSQuery extends AuthenticatedQuery
{
  protected $attributes = [
    'name' => 'GraphFiveCOGS'
  ];

  public function type(): Type
  {
    return Type::listOf(Type::float());
  }

  public function args(): array
  {
    return [
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
    $location = $args['location'];
    $isParent = $args['isParent'];
    $acctId = $args['locationId'];

    $andLocation = '';
    if ($location && $acctId) {
      $andLocation = !$isParent ? "AND `master_fcst_web`.`Mid4Desc` = \"$location\"" :
        "AND `master_fcst_web`.`Mid4Desc` IN (SELECT acct
          FROM (SELECT * FROM costcenter_tree
          ORDER BY parent, acct_id) costcenter_tree_sorted,
          (SELECT @pv := '$acctId') initialisation
          WHERE FIND_IN_SET(parent, @pv)
          AND LENGTH(@pv := CONCAT(@pv, ',', acct_id)))";
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
        (`master_fcst_web`
        LEFT JOIN `master_fcst_layout_tbl`
        ON ((`master_fcst_web`.`AccountDesc` = CONVERT( `master_fcst_layout_tbl`.`acct` USING UTF8))))
    WHERE
        (
            `master_fcst_layout_tbl`.`Parent4` = '40000 Cost of Goods Sold' AND
            `master_fcst_web`.`FCSTDesc` = "$selector"
            $andLocation
        )
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
