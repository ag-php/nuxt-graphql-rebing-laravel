<?php

use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewdcprrefTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `newdcprref` (
  `orgin` varchar(55) NOT NULL,
  `new` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`orgin`)
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
        Schema::connection('tenant')->dropIfExists('newdcprref');
    }
}
