<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseRequirementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requirement', function (Blueprint $table) {
            $table->integer('id', true);
            $table->date('date')->nullable();
            $table->string('timestamp')->nullable();
            $table->integer('order_no')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('po_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_requirement');
    }
}
