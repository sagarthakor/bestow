<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeltCuttingMaterialTable extends Migration
{
    /**
     * Fitting material (bukkal, kadi, slider, panni) consumed per cutting line,
     * from that size's belt formula. Audit trail for the stock deduction.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('belt_cutting_material', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('belt_cutting_id');
            $table->unsignedBigInteger('belt_cutting_item_id')->nullable();
            $table->bigInteger('material');
            $table->double('required_qty');
            $table->double('avalible_stock')->nullable();
            $table->string('timestamp')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index('belt_cutting_id');
            $table->index('belt_cutting_item_id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('belt_cutting_material');
    }
}
