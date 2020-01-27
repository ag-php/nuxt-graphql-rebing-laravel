<?php

use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQboplimportabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `qboplimporta` (
  `Account` text,
  `1` bigint(20) DEFAULT NULL,
  `2` bigint(20) DEFAULT NULL,
  `3` bigint(20) DEFAULT NULL,
  `4` bigint(20) DEFAULT NULL,
  `5` bigint(20) DEFAULT NULL,
  `6` bigint(20) DEFAULT NULL,
  `7` bigint(20) DEFAULT NULL,
  `8` bigint(20) DEFAULT NULL,
  `9` bigint(20) DEFAULT NULL,
  `10` bigint(20) DEFAULT NULL,
  `11` bigint(20) DEFAULT NULL,
  `12` bigint(20) DEFAULT NULL,
  `13` bigint(20) DEFAULT NULL,
  `14` bigint(20) DEFAULT NULL,
  `15` bigint(20) DEFAULT NULL,
  `16` bigint(20) DEFAULT NULL,
  `17` bigint(20) DEFAULT NULL,
  `18` bigint(20) DEFAULT NULL,
  `19` bigint(20) DEFAULT NULL,
  `20` bigint(20) DEFAULT NULL,
  `21` bigint(20) DEFAULT NULL,
  `22` bigint(20) DEFAULT NULL,
  `23` bigint(20) DEFAULT NULL,
  `24` bigint(20) DEFAULT NULL,
  `25` bigint(20) DEFAULT NULL,
  `26` bigint(20) DEFAULT NULL,
  `27` bigint(20) DEFAULT NULL,
  `28` bigint(20) DEFAULT NULL,
  `29` bigint(20) DEFAULT NULL,
  `30` bigint(20) DEFAULT NULL,
  `31` bigint(20) DEFAULT NULL,
  `32` bigint(20) DEFAULT NULL,
  `33` bigint(20) DEFAULT NULL,
  `34` bigint(20) DEFAULT NULL,
  `35` bigint(20) DEFAULT NULL,
  `36` bigint(20) DEFAULT NULL,
  `37` bigint(20) DEFAULT NULL,
  `38` bigint(20) DEFAULT NULL,
  `39` bigint(20) DEFAULT NULL,
  `40` bigint(20) DEFAULT NULL,
  `41` bigint(20) DEFAULT NULL,
  `42` bigint(20) DEFAULT NULL,
  `43` bigint(20) DEFAULT NULL,
  `44` bigint(20) DEFAULT NULL,
  `45` bigint(20) DEFAULT NULL,
  `46` bigint(20) DEFAULT NULL,
  `47` bigint(20) DEFAULT NULL,
  `48` bigint(20) DEFAULT NULL,
  `49` bigint(20) DEFAULT NULL,
  `50` bigint(20) DEFAULT NULL,
  `51` bigint(20) DEFAULT NULL,
  `52` bigint(20) DEFAULT NULL,
  `53` bigint(20) DEFAULT NULL,
  `54` bigint(20) DEFAULT NULL,
  `55` bigint(20) DEFAULT NULL,
  `56` bigint(20) DEFAULT NULL,
  `57` bigint(20) DEFAULT NULL,
  `58` bigint(20) DEFAULT NULL,
  `59` bigint(20) DEFAULT NULL,
  `60` bigint(20) DEFAULT NULL
);
CREATE TABLE `qboplimportb` (
  `Account` text,
  `1` bigint(20) DEFAULT NULL,
  `2` bigint(20) DEFAULT NULL,
  `3` bigint(20) DEFAULT NULL,
  `4` bigint(20) DEFAULT NULL,
  `5` bigint(20) DEFAULT NULL,
  `6` bigint(20) DEFAULT NULL,
  `7` bigint(20) DEFAULT NULL,
  `8` bigint(20) DEFAULT NULL,
  `9` bigint(20) DEFAULT NULL,
  `10` bigint(20) DEFAULT NULL,
  `11` bigint(20) DEFAULT NULL,
  `12` bigint(20) DEFAULT NULL,
  `13` bigint(20) DEFAULT NULL,
  `14` bigint(20) DEFAULT NULL,
  `15` bigint(20) DEFAULT NULL,
  `16` bigint(20) DEFAULT NULL,
  `17` bigint(20) DEFAULT NULL,
  `18` bigint(20) DEFAULT NULL,
  `19` bigint(20) DEFAULT NULL,
  `20` bigint(20) DEFAULT NULL,
  `21` bigint(20) DEFAULT NULL,
  `22` bigint(20) DEFAULT NULL,
  `23` bigint(20) DEFAULT NULL,
  `24` bigint(20) DEFAULT NULL,
  `25` bigint(20) DEFAULT NULL,
  `26` bigint(20) DEFAULT NULL,
  `27` bigint(20) DEFAULT NULL,
  `28` bigint(20) DEFAULT NULL,
  `29` bigint(20) DEFAULT NULL,
  `30` bigint(20) DEFAULT NULL,
  `31` bigint(20) DEFAULT NULL,
  `32` bigint(20) DEFAULT NULL,
  `33` bigint(20) DEFAULT NULL,
  `34` bigint(20) DEFAULT NULL,
  `35` bigint(20) DEFAULT NULL,
  `36` bigint(20) DEFAULT NULL,
  `37` bigint(20) DEFAULT NULL,
  `38` bigint(20) DEFAULT NULL,
  `39` bigint(20) DEFAULT NULL,
  `40` bigint(20) DEFAULT NULL,
  `41` bigint(20) DEFAULT NULL,
  `42` bigint(20) DEFAULT NULL,
  `43` bigint(20) DEFAULT NULL,
  `44` bigint(20) DEFAULT NULL,
  `45` bigint(20) DEFAULT NULL,
  `46` bigint(20) DEFAULT NULL,
  `47` bigint(20) DEFAULT NULL,
  `48` bigint(20) DEFAULT NULL,
  `49` bigint(20) DEFAULT NULL,
  `50` bigint(20) DEFAULT NULL,
  `51` bigint(20) DEFAULT NULL,
  `52` bigint(20) DEFAULT NULL,
  `53` bigint(20) DEFAULT NULL,
  `54` bigint(20) DEFAULT NULL,
  `55` bigint(20) DEFAULT NULL,
  `56` bigint(20) DEFAULT NULL,
  `57` bigint(20) DEFAULT NULL,
  `58` bigint(20) DEFAULT NULL,
  `59` bigint(20) DEFAULT NULL,
  `60` bigint(20) DEFAULT NULL
);
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
      Schema::connection('tenant')->dropIfExists('qboplimporta');
      Schema::connection('tenant')->dropIfExists('qboplimportb');
    }
}
