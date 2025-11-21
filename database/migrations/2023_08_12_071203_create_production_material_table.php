<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_material', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('machine')->nullable();
            $table->bigInteger('production_id')->nullable();
            $table->string('batch_no')->nullable();
            $table->bigInteger('required_material')->nullable();
            $table->double('required_qty')->nullable();
            $table->double('avalible_stock')->nullable();
            $table->double('need_to_order_stock')->nullable();
            $table->string('timestamp')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->integer('finish_product')->nullable();
            $table->integer('customer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_material');
    }
}
