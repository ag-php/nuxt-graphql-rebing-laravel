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

class GraphOneQuery extends AuthenticatedQuery
{
  protected $attributes = [
    'name' => 'GraphOne'
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type('revenue'));
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
    $location = $args['location'];
    $isParent = $args['isParent'];
    $acctId = $args['locationId'];

    $andLocation = '';
    if ($location && $acctId) {
      $andLocation = !$isParent ? "AND `pldetailpivot`.`Mid4Desc` = \"$location\"" :
        "AND `pldetailpivot`.`Mid4Desc` IN (SELECT acct
          FROM (SELECT * FROM costcenter_tree
          ORDER BY parent, acct_id) costcenter_tree_sorted,
          (SELECT @pv := '$acctId') initialisation
          WHERE FIND_IN_SET(parent, @pv)
          AND LENGTH(@pv := CONCAT(@pv, ',', acct_id)))";
    }

    if ($location) {
      $groupBy = "`pldetailpivot`.`FCSTDesc` , `pldetailpivot`.`Mid4Desc` , `pldetailpivot`.`Period` , `Periods2`.`Month` , `Periods2`.`Year`";
    } else {
      $groupBy = "`pldetailpivot`.`FCSTDesc` , `pldetailpivot`.`Period` , `Periods2`.`Month` , `Periods2`.`Year`";
    }

    $period = intval($args['period']);
    $periodSixMonthsPrior = $period - 5;

    $sql = <<<SQL
            SELECT
                `pldetailpivot`.`Mid4Desc` AS `location`,
                `pldetailpivot`.`Period` AS `p`,
                `Periods2`.`Month` AS `m`,
                `Periods2`.`Year` AS `y`,
                SUM(`pldetailpivot`.`amount`) AS `amount`
            FROM
                ((`pldetailpivot`
                JOIN `Periods2` ON ((CONVERT( `pldetailpivot`.`Period` USING UTF8MB4) = CONCAT('P', CAST(`Periods2`.`Period` AS CHAR CHARSET UTF8MB4)))))
                JOIN `master_fcst_layout_tbl` ON ((CONCAT(`pldetailpivot`.`Mid4Desc`, `pldetailpivot`.`AccountDesc`) = CONVERT( `master_fcst_layout_tbl`.`cc_acct_concat` USING UTF8))))
            WHERE
                (`master_fcst_layout_tbl`.`Parent4` = 'income' AND
                CAST(substring(`pldetailpivot`.`Period`, 2, 2) AS SIGNED) <= $period AND
                CAST(substring(`pldetailpivot`.`Period`, 2, 2) AS SIGNED) >= $periodSixMonthsPrior
                $andLocation)
            GROUP BY $groupBy
            ORDER BY CAST(substring(`pldetailpivot`.`Period`, 2, 2) AS SIGNED)
SQL;
    
    if ($isParent) {
      $sql = <<<SQL
      SELECT `t1`.`location`, `t1`.`p`, `t1`.`m`, `t1`.`y`, SUM(`t1`.`amount`) AS `amount` FROM (
          SELECT
              `pldetailpivot`.`Mid4Desc` AS `location`,
              `pldetailpivot`.`Period` AS `p`,
              `Periods2`.`Month` AS `m`,
              `Periods2`.`Year` AS `y`,
              SUM(`pldetailpivot`.`amount`) AS `amount`
          FROM
              ((`pldetailpivot`
              JOIN `Periods2` ON ((CONVERT( `pldetailpivot`.`Period` USING UTF8MB4) = CONCAT('P', CAST(`Periods2`.`Period` AS CHAR CHARSET UTF8MB4)))))
              JOIN `master_fcst_layout_tbl` ON ((CONCAT(`pldetailpivot`.`Mid4Desc`, `pldetailpivot`.`AccountDesc`) = CONVERT( `master_fcst_layout_tbl`.`cc_acct_concat` USING UTF8))))
          WHERE
              (`master_fcst_layout_tbl`.`Parent4` = 'income' AND
              CAST(substring(`pldetailpivot`.`Period`, 2, 2) AS SIGNED) <= $period AND
              CAST(substring(`pldetailpivot`.`Period`, 2, 2) AS SIGNED) >= $periodSixMonthsPrior
              $andLocation)
          GROUP BY $groupBy
          ORDER BY CAST(substring(`pldetailpivot`.`Period`, 2, 2) AS SIGNED) ) as `t1` GROUP BY `t1`.`p`
SQL;
    }

    return DB::connection('tenant')->select($sql);
  }
}
