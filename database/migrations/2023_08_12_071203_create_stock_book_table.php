<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_book', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('inward_id')->nullable();
            $table->string('inward_no')->nullable();
            $table->string('challan_no')->nullable();
            $table->string('salesorder_no')->nullable();
            $table->string('quotation')->nullable();
            $table->integer('vendor')->nullable();
            $table->date('inward_date')->nullable();
            $table->string('subject')->nullable();
            $table->string('remark')->nullable();
            $table->integer('product');
            $table->double('inward_qty')->nullable();
            $table->double('outward_qty')->nullable();
            $table->double('remaining_qty')->nullable();
            $table->string('particular')->nullable();
            $table->string('created_time')->nullable();
            $table->string('purchase_no')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('website_id')->nullable();
            $table->integer('customer')->nullable();
            $table->string('inward_type')->nullable();
            $table->string('inward_from')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_book');
    }
}
