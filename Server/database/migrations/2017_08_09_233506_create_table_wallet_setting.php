<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableWalletSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_setting', function (Blueprint $table) {
            $table->increments('id');
	    $table->string('wallet',100)->unique();
	    $table->string('email',50);
	    $table->string('balance',20);
	    $table->string('last_paid_date',20);
	    $table->string('last_paid_balance',20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_setting');
    }
}
