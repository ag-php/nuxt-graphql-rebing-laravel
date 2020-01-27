<?php

use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterFcstActualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `master_fcst_actual` (
  `FCSTDesc` varchar(45) NOT NULL DEFAULT '0',
  `Mid4Desc` varchar(45) DEFAULT NULL,
  `AccountDesc` varchar(255) DEFAULT NULL,
  `P1` int(11) DEFAULT NULL,
  `P2` int(11) DEFAULT NULL,
  `P3` int(11) DEFAULT NULL,
  `P4` int(11) DEFAULT NULL,
  `P5` int(11) DEFAULT NULL,
  `P6` int(11) DEFAULT NULL,
  `P7` int(11) DEFAULT NULL,
  `P8` int(11) DEFAULT NULL,
  `P9` int(11) DEFAULT NULL,
  `P10` int(11) DEFAULT NULL,
  `P11` int(11) DEFAULT NULL,
  `P12` int(11) DEFAULT NULL,
  `P13` int(11) DEFAULT NULL,
  `P14` int(11) DEFAULT NULL,
  `P15` int(11) DEFAULT NULL,
  `P16` int(11) DEFAULT NULL,
  `P17` int(11) DEFAULT NULL,
  `P18` int(11) DEFAULT NULL,
  `P19` int(11) DEFAULT NULL,
  `P20` int(11) DEFAULT NULL,
  `P21` int(11) DEFAULT NULL,
  `P22` int(11) DEFAULT NULL,
  `P23` int(11) DEFAULT NULL,
  `P24` int(11) DEFAULT NULL,
  `P25` int(11) DEFAULT NULL,
  `P26` int(11) DEFAULT NULL,
  `P27` int(11) DEFAULT NULL,
  `P28` int(11) DEFAULT NULL,
  `P29` int(11) DEFAULT NULL,
  `P30` int(11) DEFAULT NULL,
  `P31` int(11) DEFAULT NULL,
  `P32` int(11) DEFAULT NULL,
  `P33` int(11) DEFAULT NULL,
  `P34` int(11) DEFAULT NULL,
  `P35` int(11) DEFAULT NULL,
  `P36` int(11) DEFAULT NULL,
  `P37` int(11) DEFAULT NULL,
  `P38` int(11) DEFAULT NULL,
  `P39` int(11) DEFAULT NULL,
  `P40` int(11) DEFAULT NULL,
  `P41` int(11) DEFAULT NULL,
  `P42` int(11) DEFAULT NULL,
  `P43` int(11) DEFAULT NULL,
  `P44` int(11) DEFAULT NULL,
  `P45` int(11) DEFAULT NULL,
  `P46` int(11) DEFAULT NULL,
  `P47` int(11) DEFAULT NULL,
  `P48` int(11) DEFAULT NULL,
  `P49` int(11) DEFAULT NULL,
  `P50` int(11) DEFAULT NULL,
  `P51` int(11) DEFAULT NULL,
  `P52` int(11) DEFAULT NULL,
  `P53` int(11) DEFAULT NULL,
  `P54` int(11) DEFAULT NULL,
  `P55` int(11) DEFAULT NULL,
  `P56` int(11) DEFAULT NULL,
  `P57` int(11) DEFAULT NULL,
  `P58` int(11) DEFAULT NULL,
  `P59` int(11) DEFAULT NULL,
  `P60` int(11) DEFAULT NULL,
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
        Schema::connection('tenant')->dropIfExists('master_fcst_actual');
    }
}
