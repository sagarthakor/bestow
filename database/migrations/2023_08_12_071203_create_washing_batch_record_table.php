<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWashingBatchRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('washing_batch_record', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('washing_id')->nullable();
            $table->integer('production_id')->nullable();
            $table->string('batch_no')->nullable();
            $table->string('process')->nullable();
            $table->string('time')->nullable();
            $table->date('date')->nullable();
            $table->integer('operater_name')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('total_washing')->nullable();
            $table->string('total_washing_wastage_nos')->nullable();
            $table->string('total_washing_material_used')->nullable();
            $table->string('total_washing_wastage_material')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('washing_batch_record');
    }
}
