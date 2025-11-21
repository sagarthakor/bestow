<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_order_item', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('order_no')->nullable();
            $table->string('order_id')->nullable();
            $table->double('product')->nullable();
            $table->longText('custom_description')->nullable();
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
            $table->integer('qty')->nullable();
            $table->double('price')->nullable();
            $table->integer('group_id')->nullable();
            $table->double('total_amount')->nullable();
            $table->string('discount_per')->nullable();
            $table->double('discount_amount')->nullable();
            $table->string('cgst_per')->nullable();
            $table->double('cgst_amount')->nullable();
            $table->string('sgst_per')->nullable();
            $table->double('sgst_amount')->nullable();
            $table->string('gst_per')->nullable();
            $table->double('gst_amount')->nullable();
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
        Schema::dropIfExists('customer_order_item');
    }
}
