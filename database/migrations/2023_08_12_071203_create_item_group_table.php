<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_group', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('group_name')->nullable();
            $table->longText('description')->nullable();
            $table->longText('product_description')->nullable();
            $table->string('created_time')->nullable();
            $table->integer('category')->nullable();
            $table->integer('subcategory')->nullable();
            $table->string('product_url')->nullable();
            $table->string('category_name')->nullable();
            $table->string('subcategory_name')->nullable();
            $table->integer('material')->nullable();
            $table->string('material_name')->nullable();
            $table->integer('manufacturer')->nullable();
            $table->string('manufacturer_name')->nullable();
            $table->integer('brand')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('hsn')->nullable();
            $table->integer('uom')->nullable();
            $table->integer('gst')->nullable();
            $table->string('primary_image')->nullable();
            $table->double('group_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_group');
    }
}
