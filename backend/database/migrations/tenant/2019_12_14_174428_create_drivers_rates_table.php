<?php

use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `drivers_rates` (
    `FCSTDesc` varchar(45) NOT NULL DEFAULT '0',
    `FCSTVersion` varchar(45) NOT NULL DEFAULT '0',
    `Account` varchar(45) NOT NULL DEFAULT '0',
    `Store` varchar(45) DEFAULT NULL,
    `Type` varchar(255) DEFAULT NULL,
    `AccountDriver1` varchar(255) DEFAULT NULL,
    `AccountDriver2` varchar(45) DEFAULT NULL,
    `AccountDriver3` varchar(45) DEFAULT NULL,
    `Account_map` varchar(45) NOT NULL DEFAULT '0',
    `P1` int(11) DEFAULT NULL,
    `P2` int(11) DEFAULT NULL,
    `P3` double DEFAULT NULL,
    `P4` double DEFAULT NULL,
    `P5` double DEFAULT NULL,
    `P6` double DEFAULT NULL,
    `P7` double DEFAULT NULL,
    `P8` double DEFAULT NULL,
    `P9` double DEFAULT NULL,
    `P10` double DEFAULT NULL,
    `P11` double DEFAULT NULL,
    `P12` double DEFAULT NULL,
    `P13` double DEFAULT NULL,
    `P14` double DEFAULT NULL,
    `P15` double DEFAULT NULL,
    `P16` double DEFAULT NULL,
    `P17` double DEFAULT NULL,
    `P18` double DEFAULT NULL,
    `P19` double DEFAULT NULL,
    `P20` double DEFAULT NULL,
    `P21` double DEFAULT NULL,
    `P22` double DEFAULT NULL,
    `P23` double DEFAULT NULL,
    `P24` double DEFAULT NULL,
    `P25` double DEFAULT NULL,
    `P26` double DEFAULT NULL,
    `P27` double DEFAULT NULL,
    `P28` double DEFAULT NULL,
    `P29` double DEFAULT NULL,
    `P30` double DEFAULT NULL,
    `P31` double DEFAULT NULL,
    `P32` double DEFAULT NULL,
    `P33` double DEFAULT NULL,
    `P34` double DEFAULT NULL,
    `P35` double DEFAULT NULL,
    `P36` double DEFAULT NULL,
    `P37` double DEFAULT NULL,
    `P38` double DEFAULT NULL,
    `P39` double DEFAULT NULL,
    `P40` double DEFAULT NULL,
    `P41` double DEFAULT NULL,
    `P42` double DEFAULT NULL,
    `P43` double DEFAULT NULL,
    `P44` double DEFAULT NULL,
    `P45` double DEFAULT NULL,
    `P46` double DEFAULT NULL,
    `P47` double DEFAULT NULL,
    `P48` double DEFAULT NULL,
    `P49` double DEFAULT NULL,
    `P50` double DEFAULT NULL,
    `P51` double DEFAULT NULL,
    `P52` double DEFAULT NULL,
    `P53` double DEFAULT NULL,
    `P54` double DEFAULT NULL,
    `P55` double DEFAULT NULL,
    `P56` double DEFAULT NULL,
    `P57` double DEFAULT NULL,
    `P58` double DEFAULT NULL,
    `P59` double DEFAULT NULL,
    `P60` double DEFAULT NULL,
    `id` int(11) NOT NULL AUTO_INCREMENT,
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
        Schema::connection('tenant')->dropIfExists('drivers_rates');
    }
}
