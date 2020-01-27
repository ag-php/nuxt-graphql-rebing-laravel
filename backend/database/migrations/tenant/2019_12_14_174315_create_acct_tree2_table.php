<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcctTree2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE `acct_tree2` (
    `id` varchar(255) NOT NULL,
    `acct_id` int(11) NOT NULL,
    `acct` varchar(255) DEFAULT NULL,
    `acctdesc` varchar(255) DEFAULT NULL,
    `parent` int(11) DEFAULT NULL,
    `applies_to` text,
    `acct_type` varchar(255) DEFAULT NULL,
    `impacts` varchar(255) DEFAULT NULL,
    `sort` int(11) DEFAULT NULL,
    `P-L Group` varchar(255) DEFAULT NULL,
    `Detail P-L` varchar(255) DEFAULT NULL,
    `sign` int(11) NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
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
        Schema::connection('tenant')->dropIfExists('acct_tree2');
    }
}
