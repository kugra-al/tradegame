<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangeShareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('exchange_share', function (Blueprint $table) {
            $table->unsignedBigInteger('exchange_id');
            $table->unsignedBigInteger('share_id');
        });
        Schema::table('exchange_share',function($table){
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
        Schema::dropIfExists('exchange_share');
    }
}
