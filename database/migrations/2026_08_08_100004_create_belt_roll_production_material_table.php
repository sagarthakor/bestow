<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeltRollProductionMaterialTable extends Migration
{
    /**
     * Dhaga consumed by a roll batch, frozen at batch creation so a later edit to
     * the niwar code's gm_per_meter cannot rewrite history. Mirrors
     * belt_production_material.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('belt_roll_production_material', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('belt_roll_production_id');
            $table->bigInteger('material');
            $table->double('required_qty');
            $table->double('avalible_stock')->nullable();
            $table->string('timestamp')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index('belt_roll_production_id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('belt_roll_production_material');
    }
}
