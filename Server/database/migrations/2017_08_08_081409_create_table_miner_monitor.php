<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMinerMonitor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miner_monitor', function (Blueprint $table) {
		$table->increments('id');
		$table->string('wallet',100);
		$table->string('miner',20);
		$table->timestamps();
		$table->index(['wallet','miner'],'idx_wallet_miner');
		
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('miner_monitor');
    }
}
