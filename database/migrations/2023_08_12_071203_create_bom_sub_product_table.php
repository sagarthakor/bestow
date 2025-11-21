<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBomSubProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bom_sub_product', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('bom_id')->nullable();
            $table->integer('product')->nullable();
            $table->string('description')->nullable();
            $table->string('inner_daimitter')->nullable();
            $table->string('outer_daimitter')->nullable();
            $table->string('thikness')->nullable();
            $table->string('hsn')->nullable();
            $table->string('uom')->nullable();
            $table->double('qty')->nullable();
            $table->double('price')->nullable();
            $table->double('amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bom_sub_product');
    }
}
