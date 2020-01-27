<?php

use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcctCostRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `acct_cost_relation` (
  `RelationId` int(11) NOT NULL AUTO_INCREMENT,
  `Comboacct_id` int(11) NOT NULL,
  `CostCent_id` int(11) NOT NULL,
  PRIMARY KEY (`RelationId`),
  UNIQUE KEY `relation` (`Comboacct_id`,`CostCent_id`)
) ENGINE=InnoDB;
SQL;
        DB::connection('tenant')->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('tenant')->dropIfExists('acct_cost_relation');
    }
}
