<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWashingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('washing', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('pressing_id')->nullable();
            $table->bigInteger('production_id')->nullable();
            $table->bigInteger('machine')->nullable();
            $table->string('batch_no')->nullable();
            $table->bigInteger('batch')->nullable();
            $table->integer('customer')->nullable();
            $table->integer('finish_product')->nullable();
            $table->string('nos')->nullable();
            $table->string('size')->nullable();
            $table->string('total_material')->nullable();
            $table->string('timestamp')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('status')->nullable();
            $table->string('complete_time')->nullable();
            $table->string('stitching_start')->nullable();
            $table->string('total_washing')->nullable();
            $table->string('total_washing_wastage_nos')->nullable();
            $table->string('total_washing_material_used')->nullable();
            $table->string('total_washing_wastage_material')->nullable();
            $table->string('stitching_complete_time')->nullable();
            $table->longText('remarks')->nullable();
            $table->integer('finished_user')->nullable();
            $table->string('washing_status')->nullable();
            $table->string('washing_process')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('washing');
    }
}
