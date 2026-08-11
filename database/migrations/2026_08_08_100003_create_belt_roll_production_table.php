<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeltRollProductionTable extends Migration
{
    /**
     * Stage A of belt manufacturing: weaving niwar into rolls of a chosen length
     * (50/60/70 mtr as per order). Size does not exist at this stage - the roll is
     * cut to size later, in belt_cutting.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('belt_roll_production', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no')->nullable();
            $table->unsignedBigInteger('niwar_code_id');
            $table->integer('roll_product_id');
            $table->double('roll_length_mtr');
            $table->integer('no_of_rolls');
            $table->double('planned_mtr');
            $table->double('produced_mtr')->nullable();
            $table->double('wastage_mtr')->nullable();
            $table->integer('customer')->nullable();
            $table->string('status')->default('N');
            $table->string('timestamp')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index('niwar_code_id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('belt_roll_production');
    }
}
