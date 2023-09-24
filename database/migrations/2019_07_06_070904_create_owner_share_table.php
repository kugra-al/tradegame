<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOwnerShareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('owner_share', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('share_id');
        });
        Schema::table('owner_share',function($table){
            $table->foreign('owner_id')->references('id')->on('owners');
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
        Schema::dropIfExists('owner_share');
    }
}
