<?php

use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewdcprTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `newdcpr` (
  `id` int(11) NOT NULL,
  `t_store` varchar(45) NOT NULL,
  `t_year` int(11) NOT NULL,
  `t_month` varchar(45) NOT NULL,
  `t_week` int(11) NOT NULL,
  `t_day` varchar(45) NOT NULL,
  `dayofweek` varchar(45) NOT NULL,
  `t_date` date NOT NULL,
  `food` decimal(12,2) NOT NULL,
  `beer` decimal(12,2) NOT NULL,
  `liquor` decimal(12,2) NOT NULL,
  `wine` decimal(12,2) NOT NULL,
  `apparel` decimal(12,2) NOT NULL,
  `Lunch Sales` decimal(12,2) NOT NULL,
  `Dinner Sales` decimal(12,2) NOT NULL,
  `Total Sales` decimal(12,2) NOT NULL,
  `hourlylabor` decimal(12,2) NOT NULL,
  `Lunch Labor` decimal(12,2) NOT NULL,
  `Dinner Labor` decimal(12,2) NOT NULL,
  `Total Labor` decimal(12,2) NOT NULL,
  `customerdiscount` decimal(12,2) NOT NULL,
  `employeediscount` decimal(12,2) NOT NULL,
  `managerdiscount` decimal(12,2) NOT NULL,
  `paidoutpos` decimal(12,2) NOT NULL,
  `cashindrawerpos` decimal(12,2) NOT NULL,
  `bankdeposit` decimal(12,2) NOT NULL,
  `lotterydeposit` decimal(12,2) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `week_num` varchar(45) DEFAULT NULL,
  `periodref` varchar(45) DEFAULT NULL,
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
        Schema::connection('tenant')->dropIfExists('newdcpr');
    }
}
