<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('product_name')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->double('price')->nullable();
            $table->double('gst')->nullable();
            $table->string('uom')->nullable();
            $table->integer('category')->nullable();
            $table->integer('material')->nullable();
            $table->string('category_name')->nullable();
            $table->string('material_name')->nullable();
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
            $table->string('status')->nullable();
            $table->string('created_time')->nullable();
            $table->string('remark')->nullable();
            $table->integer('group_id')->nullable();
            $table->double('purchase_price')->nullable();
            $table->string('item_code')->nullable();
            $table->string('group_no')->nullable();
            $table->integer('item_group')->nullable();
            $table->integer('sub_item_group')->nullable();
            $table->double('item_total')->nullable();
            $table->string('sku')->nullable();
            $table->integer('manufacturer')->nullable();
            $table->string('manufacturer_name')->nullable();
            $table->string('brand')->nullable();
            $table->string('brand_name')->nullable();
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
            $table->integer('subcategory')->nullable();
            $table->string('subcategory_name')->nullable();
            $table->string('product_url')->nullable();
            $table->longText('product_description')->nullable();
            $table->string('show_hide')->nullable();
            $table->string('price_show_hide')->nullable();
            $table->double('opening_stock')->nullable();
            $table->integer('importer')->nullable();
            $table->integer('packer')->nullable();
            $table->integer('colour')->nullable();
            $table->integer('size')->nullable();
            $table->string('raw_material_group')->nullable();
            $table->string('cotton')->nullable();
            $table->string('spendex')->nullable();
            $table->string('elastics')->nullable();
            $table->string('nylon')->nullable();
            $table->string('polyester')->nullable();
            $table->string('p_p_yarn')->nullable();
            $table->string('bar_code')->nullable();
            $table->string('cover_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
}
