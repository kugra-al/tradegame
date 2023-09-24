<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExchangeIdOwnerIdToShares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shares', function (Blueprint $table) {
            $table->unsignedBigInteger('exchange_id')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->unsignedBigInteger('company_id')->change();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('exchange_id')->references('id')->on('exchanges');
            $table->foreign('owner_id')->references('id')->on('owners');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shares', function (Blueprint $table) {
            $table->dropColumns(['exchange_id','owner_id']);
        });
    }
}
