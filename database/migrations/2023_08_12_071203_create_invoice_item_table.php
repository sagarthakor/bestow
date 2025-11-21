<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_item', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('invid')->nullable();
            $table->string('invoice_no')->nullable();
            $table->integer('product')->nullable();
            $table->string('description')->nullable();
            $table->string('inner_diamitter')->nullable();
            $table->string('outer_diamitter')->nullable();
            $table->string('thikness')->nullable();
            $table->string('hsn')->nullable();
            $table->double('qty')->nullable();
            $table->double('price')->nullable();
            $table->double('total_amount')->nullable();
            $table->double('discount_per')->nullable();
            $table->double('discount_amount')->nullable();
            $table->double('cgst_per')->nullable();
            $table->double('cgst_amount')->nullable();
            $table->double('sgst_per')->nullable();
            $table->double('sgst_amount')->nullable();
            $table->double('igst_per')->nullable();
            $table->double('igst_amount')->nullable();
            $table->double('net_price')->nullable();
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
        Schema::dropIfExists('invoice_item');
    }
}
