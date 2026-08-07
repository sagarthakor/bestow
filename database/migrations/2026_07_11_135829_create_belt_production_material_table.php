<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeltProductionMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('belt_production_material', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('belt_production_id');
            $table->bigInteger('material');
            $table->double('required_qty');
            $table->double('avalible_stock')->nullable();
            $table->string('timestamp')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index('belt_production_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('belt_production_material');
    }
}
