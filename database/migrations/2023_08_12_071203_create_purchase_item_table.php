<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_item', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('poid')->nullable();
            $table->string('pono')->nullable();
            $table->integer('vendor')->nullable();
            $table->integer('product')->nullable();
            $table->string('description')->nullable();
            $table->string('inner_diameter')->nullable();
            $table->string('outer_diameter')->nullable();
            $table->string('thikness')->nullable();
            $table->string('hsn')->nullable();
            $table->string('qty')->nullable();
            $table->double('price')->nullable();
            $table->double('total')->nullable();
            $table->double('discount_per')->nullable();
            $table->double('discount_amount')->nullable();
            $table->string('cgst_per')->nullable();
            $table->double('cgst_amount')->nullable();
            $table->string('sgst_per')->nullable();
            $table->double('sgst_amount')->nullable();
            $table->double('gst_per')->nullable();
            $table->double('gst_amount')->nullable();
            $table->double('grand_total')->nullable();
            $table->double('received_qty')->nullable();
            $table->double('remain_qty')->nullable();
            $table->integer('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_item');
    }
}
