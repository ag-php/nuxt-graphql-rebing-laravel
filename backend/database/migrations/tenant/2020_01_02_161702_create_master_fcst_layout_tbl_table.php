<?php

use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterFcstLayoutTblTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `master_fcst_layout_tbl` (
  `cc_id` int(11) NOT NULL,
  `cc_2` varchar(255) DEFAULT NULL,
  `cc_1` varchar(255) DEFAULT NULL,
  `cc_a` varchar(255) DEFAULT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `cc_type` varchar(255) DEFAULT NULL,
  `acct_id` int(11) NOT NULL DEFAULT '0',
  `applies_to` text,
  `Parent1` varchar(255) DEFAULT NULL,
  `Parent1_Sort` varchar(255) DEFAULT NULL,
  `Parent2` varchar(255) DEFAULT NULL,
  `Parent2_Sort` varchar(255) DEFAULT NULL,
  `Parent3` varchar(255) DEFAULT NULL,
  `Parent3_Sort` varchar(255) DEFAULT NULL,
  `Parent4` varchar(255) DEFAULT NULL,
  `Parent4_Sort` varchar(255) DEFAULT NULL,
  `Parent5` varchar(255) DEFAULT NULL,
  `Parent5_Sort` varchar(255) DEFAULT NULL,
  `Parent6` varchar(255) DEFAULT NULL,
  `Parent6_Sort` varchar(255) DEFAULT NULL,
  `acct` varchar(255) DEFAULT NULL,
  `acct_type` varchar(255) DEFAULT NULL,
  `acct_sort` varchar(255) DEFAULT NULL,
  `cc_acct_concat` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`cc_acct_concat`)
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
        Schema::connection('tenant')->dropIfExists('master_fcst_layout_tbl');
    }
}
