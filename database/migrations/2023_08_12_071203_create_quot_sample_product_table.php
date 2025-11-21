<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotSampleProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quot_sample_product', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('sample_id')->nullable();
            $table->bigInteger('quot_id')->nullable();
            $table->bigInteger('product')->nullable();
            $table->double('qty')->nullable();
            $table->double('price')->nullable();
            $table->double('net_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quot_sample_product');
    }
}
