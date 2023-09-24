<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyIdToExchangesOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchanges_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id');
          //  $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('share_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchanges_orders', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
}
