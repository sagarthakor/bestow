<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotSampleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quot_sample', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('sample_name')->nullable();
            $table->bigInteger('quot_id')->nullable();
            $table->string('sample_base')->nullable();
            $table->string('sample_range')->nullable();
            $table->text('sample_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quot_sample');
    }
}
