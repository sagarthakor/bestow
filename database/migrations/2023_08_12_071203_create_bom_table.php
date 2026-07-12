<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bom', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('bom_name')->nullable();
            $table->string('created_time')->nullable();
            $table->double('item_total')->nullable();
            $table->integer('bom_category')->nullable();
            $table->integer('bom_material')->nullable();
            $table->string('bom_inner_diameter')->nullable();
            $table->string('bom_outer_diameter')->nullable();
            $table->string('bom_thikness')->nullable();
            $table->string('bom_hsn')->nullable();
            $table->double('bom_price')->nullable();
            $table->integer('bom_gst')->nullable();
            $table->integer('bom_uom')->nullable();
            $table->string('bom_description')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('website_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bom');
    }
}
