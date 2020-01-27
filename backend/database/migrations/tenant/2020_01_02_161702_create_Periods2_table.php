<?php

use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriods2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `Periods2` (
  `id` int(11) NOT NULL DEFAULT '0',
  `Desc` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `Month` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `Year` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `Period` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
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
        Schema::connection('tenant')->dropIfExists('Periods2');
    }
}
