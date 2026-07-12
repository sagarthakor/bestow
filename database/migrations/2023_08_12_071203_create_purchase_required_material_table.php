<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseRequiredMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_required_material', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('raw_material')->nullable();
            $table->double('qty')->nullable();
            $table->string('timestamp')->nullable();
            $table->integer('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_required_material');
    }
}
