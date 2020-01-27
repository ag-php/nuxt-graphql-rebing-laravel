<?php

use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcctTreeOldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `acct_tree_old` (
  `acct_id` int(11) NOT NULL AUTO_INCREMENT,
  `acct` varchar(255) DEFAULT NULL,
  `acctdesc` varchar(255) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `applies_to` text,
  `acct_type` varchar(255) DEFAULT NULL,
  `impacts` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`acct_id`)
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
        Schema::connection('tenant')->dropIfExists('acct_tree_old');
    }
}
