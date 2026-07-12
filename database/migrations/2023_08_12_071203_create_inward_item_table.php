<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInwardItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inward_item', function (Blueprint $table) {
            $table->integer('id', true);
            $table->date('inward_date')->nullable();
            $table->integer('inward_id')->nullable();
            $table->string('inward_no')->nullable();
            $table->integer('vendor')->nullable();
            $table->integer('customer')->nullable();
            $table->string('inward_type')->nullable();
            $table->string('inward_from')->nullable();
            $table->integer('product')->nullable();
            $table->double('total_qty')->nullable();
            $table->double('received_qty')->nullable();
            $table->double('remaining_qty')->nullable();
            $table->string('created_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inward_item');
    }
}
