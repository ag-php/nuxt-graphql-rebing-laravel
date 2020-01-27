<?php

use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterFcstManualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `master_fcst_manual` (
  `FCSTDesc` varchar(45) NOT NULL DEFAULT '0',
  `Mid4Desc` varchar(45) DEFAULT NULL,
  `AccountDesc` varchar(255) DEFAULT NULL,
  `P1` int(11) DEFAULT '0',
  `P2` int(11) DEFAULT '0',
  `P3` int(11) DEFAULT '0',
  `P4` int(11) DEFAULT '0',
  `P5` int(11) DEFAULT '0',
  `P6` int(11) DEFAULT '0',
  `P7` int(11) DEFAULT '0',
  `P8` int(11) DEFAULT '0',
  `P9` int(11) DEFAULT '0',
  `P10` int(11) DEFAULT '0',
  `P11` int(11) DEFAULT '0',
  `P12` int(11) DEFAULT '0',
  `P13` int(11) DEFAULT '0',
  `P14` int(11) DEFAULT '0',
  `P15` int(11) DEFAULT '0',
  `P16` int(11) DEFAULT '0',
  `P17` int(11) DEFAULT '0',
  `P18` int(11) DEFAULT '0',
  `P19` int(11) DEFAULT '0',
  `P20` int(11) DEFAULT '0',
  `P21` int(11) DEFAULT '0',
  `P22` int(11) DEFAULT '0',
  `P23` int(11) DEFAULT '0',
  `P24` int(11) DEFAULT '0',
  `P25` int(11) DEFAULT '0',
  `P26` int(11) DEFAULT '0',
  `P27` int(11) DEFAULT '0',
  `P28` int(11) DEFAULT '0',
  `P29` int(11) DEFAULT '0',
  `P30` int(11) DEFAULT '0',
  `P31` int(11) DEFAULT '0',
  `P32` int(11) DEFAULT '0',
  `P33` int(11) DEFAULT '0',
  `P34` int(11) DEFAULT '0',
  `P35` int(11) DEFAULT '0',
  `P36` int(11) DEFAULT '0',
  `P37` int(11) DEFAULT '0',
  `P38` int(11) DEFAULT '0',
  `P39` int(11) DEFAULT '0',
  `P40` int(11) DEFAULT '0',
  `P41` int(11) DEFAULT '0',
  `P42` int(11) DEFAULT '0',
  `P43` int(11) DEFAULT '0',
  `P44` int(11) DEFAULT '0',
  `P45` int(11) DEFAULT '0',
  `P46` int(11) DEFAULT '0',
  `P47` int(11) DEFAULT '0',
  `P48` int(11) DEFAULT '0',
  `P49` int(11) DEFAULT '0',
  `P50` int(11) DEFAULT '0',
  `P51` int(11) DEFAULT '0',
  `P52` int(11) DEFAULT '0',
  `P53` int(11) DEFAULT '0',
  `P54` int(11) DEFAULT '0',
  `P55` int(11) DEFAULT '0',
  `P56` int(11) DEFAULT '0',
  `P57` int(11) DEFAULT '0',
  `P58` int(11) DEFAULT '0',
  `P59` int(11) DEFAULT '0',
  `P60` int(11) DEFAULT '0',
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
        Schema::connection('tenant')->dropIfExists('master_fcst_manual');
    }
}
