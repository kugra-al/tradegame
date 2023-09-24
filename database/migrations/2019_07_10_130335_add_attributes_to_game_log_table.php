<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttributesToGameLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_log', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->foreign('owner_id')->references('id')->on('owners');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('exchange_id')->nullable();
            $table->foreign('exchange_id')->references('id')->on('exchanges');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_log', function (Blueprint $table) {
            $table->dropColumns(['company_id','owner_id','exchange_id']);
        });
    }
}
