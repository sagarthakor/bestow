<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeltCuttingTable extends Migration
{
    /**
     * Stage B: one entry cuts exactly one roll (kept 1:1 so every finished belt
     * traces back to a single roll_no) into any number of sizes, then fits
     * kadi/slider/bukkal on the cut pieces.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('belt_cutting', function (Blueprint $table) {
            $table->id();
            $table->string('cutting_no')->nullable();
            $table->unsignedBigInteger('roll_id');
            $table->integer('customer')->nullable();
            $table->integer('total_pieces')->nullable();
            $table->double('total_meter_used')->nullable();
            $table->double('balance_mtr')->nullable();
            $table->string('status')->default('Y');
            $table->string('timestamp')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index('roll_id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('belt_cutting');
    }
}
