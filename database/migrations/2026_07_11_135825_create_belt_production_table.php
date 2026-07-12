<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeltProductionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('belt_production', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no')->nullable();
            $table->integer('belt_product');
            $table->integer('customer')->nullable();
            $table->double('planned_qty');
            $table->double('total_production')->nullable();
            $table->double('total_wastage_nos')->nullable();
            $table->string('status')->default('N');
            $table->string('timestamp')->nullable();
            $table->bigInteger('user_id')->nullable();
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
        Schema::dropIfExists('belt_production');
    }
}
