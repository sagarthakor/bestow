<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quot_item', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer')->nullable();
            $table->integer('quot_no')->nullable();
            $table->string('quotation_no')->nullable();
            $table->integer('product')->nullable();
            $table->text('description')->nullable();
            $table->string('group_no')->nullable();
            $table->string('item_code')->nullable();
            $table->string('inner_diameter')->nullable();
            $table->string('outer_diameter')->nullable();
            $table->string('thikness')->nullable();
            $table->string('hsn')->nullable();
            $table->double('qty')->nullable();
            $table->double('price')->nullable();
            $table->double('amount')->nullable();
            $table->string('cgst_per')->nullable();
            $table->double('cgst_amount')->nullable();
            $table->string('sgst_per')->nullable();
            $table->double('sgst_amount')->nullable();
            $table->double('gst_per')->nullable();
            $table->double('gst_amount')->nullable();
            $table->double('grand_total')->nullable();
            $table->double('discount_amount')->nullable();
            $table->double('discount_per')->nullable();
            $table->string('product_range')->nullable();
            $table->string('product_base')->nullable();
            $table->string('product_category')->nullable();
            $table->longText('custom_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quot_item');
    }
}
