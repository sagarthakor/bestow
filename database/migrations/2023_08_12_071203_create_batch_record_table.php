<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_record', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('production_id')->nullable();
            $table->string('batch_no')->nullable();
            $table->string('process')->nullable();
            $table->string('time')->nullable();
            $table->date('date')->nullable();
            $table->integer('operater_name')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('total_production')->nullable();
            $table->string('total_wastage_nos')->nullable();
            $table->string('total_material_used')->nullable();
            $table->string('total_wastage_used')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_record');
    }
}
