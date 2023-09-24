<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchanges_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('share_id');
            $table->unsignedBigInteger('exchange_id');
            $table->unsignedBigInteger('price');
            $table->timestamps();
        });
        Schema::table('exchanges_orders',function($table){
            $table->foreign('exchange_id')->references('id')->on('exchanges');
            $table->foreign('share_id')->references('id')->on('shares');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchanges_orders');
    }
}
