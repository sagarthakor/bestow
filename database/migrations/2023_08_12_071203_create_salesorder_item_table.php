<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesorderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesorder_item', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('soid')->nullable();
            $table->string('sono')->nullable();
            $table->integer('product')->nullable();
            $table->longText('description')->nullable();
            $table->string('inner_daimitter')->nullable();
            $table->string('outer_daimitter')->nullable();
            $table->string('thk')->nullable();
            $table->string('hsn')->nullable();
            $table->string('qty')->nullable();
            $table->string('deliver_qty')->nullable();
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salesorder_item');
    }
}
