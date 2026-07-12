<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReceiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_receive', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('purchase_no')->nullable();
            $table->date('receive_date')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('create_time')->nullable();
            $table->longText('note')->nullable();
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('invoice_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_receive');
    }
}
