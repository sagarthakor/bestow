<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_options', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('group_id')->nullable();
            $table->integer('product')->nullable();
            $table->integer('product_attribute')->nullable();
            $table->string('attribute1')->nullable();
            $table->string('value1')->nullable();
            $table->string('attribute2')->nullable();
            $table->string('value2')->nullable();
            $table->string('attribute3')->nullable();
            $table->string('value3')->nullable();
            $table->string('attribute4')->nullable();
            $table->string('value4')->nullable();
            $table->string('attribute5')->nullable();
            $table->string('value5')->nullable();
            $table->string('attribute6')->nullable();
            $table->string('value6')->nullable();
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->string('image4')->nullable();
            $table->string('image5')->nullable();
            $table->string('image6')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_options');
    }
}
