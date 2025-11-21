<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePressingBatchRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pressing_batch_record', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('pressing_id')->nullable();
            $table->integer('production_id')->nullable();
            $table->string('batch_no')->nullable();
            $table->string('process')->nullable();
            $table->string('time')->nullable();
            $table->date('date')->nullable();
            $table->integer('operater_name')->nullable();
            $table->longText('remarks')->nullable();
            $table->string('total_pressing')->nullable();
            $table->string('total_pressing_wastage_nos')->nullable();
            $table->string('total_pressing_material_used')->nullable();
            $table->string('total_pressing_wastage_material')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pressing_batch_record');
    }
}
