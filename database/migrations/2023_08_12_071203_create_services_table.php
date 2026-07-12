<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('service_name')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->double('price')->nullable();
            $table->double('gst')->nullable();
            $table->string('uom')->nullable();
            $table->integer('category')->nullable();
            $table->date('sales_start_date')->nullable();
            $table->date('sales_end_date')->nullable();
            $table->text('description')->nullable();
            $table->integer('website_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('product_image')->nullable();
            $table->integer('vendor')->nullable();
            $table->string('outer_diameter')->nullable();
            $table->string('inner_diameter')->nullable();
            $table->string('thikness')->nullable();
            $table->string('hsn')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
