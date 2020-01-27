<?php

use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchases1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `purchases1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` varchar(12) DEFAULT NULL,
  `day` varchar(25) DEFAULT NULL,
  `delivery_date` varchar(255) DEFAULT NULL,
  `store` varchar(255) DEFAULT NULL,
  `invoice` varchar(255) DEFAULT NULL,
  `invoice_TTL` varchar(255) DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `purchase_type` varchar(255) DEFAULT NULL,
  `apparel` int(11) DEFAULT '0',
  `hourly labor` int(11) DEFAULT '0',
  `month_num` int(11) DEFAULT '0',
  `day_num` int(11) DEFAULT '0',
  `year` int(11) DEFAULT '0',
  `day_count` int(11) DEFAULT '0',
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
        Schema::connection('tenant')->dropIfExists('purchases1');
    }
}
