<?php

use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverAcctTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `driver_acct` (
  `acct_id` int(11) DEFAULT NULL,
  `acct` text,
  `acctdesc` text,
  `parent` int(11) DEFAULT NULL,
  `applies_to` text,
  `acct_type` text,
  `impacts` text,
  `sort` int(11) DEFAULT NULL
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
        Schema::connection('tenant')->dropIfExists('driver_acct');
    }
}
