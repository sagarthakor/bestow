<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReceiveItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_receive_item', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('purchase_receive_id')->nullable();
            $table->integer('item')->nullable();
            $table->string('order_qty')->nullable();
            $table->string('total_received_qty')->nullable();
            $table->string('remain_qty')->nullable();
            $table->string('qty_received')->nullable();
            $table->string('purchase_no')->nullable();
            $table->double('rem_qty')->nullable();
            $table->date('receive_date')->nullable();
            $table->string('note')->nullable();
            $table->bigInteger('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_receive_item');
    }
}
