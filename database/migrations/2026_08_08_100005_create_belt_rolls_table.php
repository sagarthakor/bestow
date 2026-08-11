<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeltRollsTable extends Migration
{
    /**
     * The roll register - one row per physical roll. stock_status already carries
     * the roll product's total meters, but it cannot tell a 70 mtr roll from a 50
     * mtr one, nor how much is left on each. Cutting picks a specific roll from
     * here and decrements remaining_mtr; the unusable end piece is closed off as
     * scrap_mtr rather than silently disappearing.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('belt_rolls', function (Blueprint $table) {
            $table->id();
            $table->string('roll_no')->unique();
            $table->unsignedBigInteger('belt_roll_production_id');
            $table->unsignedBigInteger('niwar_code_id');
            $table->integer('roll_product_id');
            $table->double('length_mtr');
            $table->double('remaining_mtr');
            $table->double('scrap_mtr')->nullable();
            $table->string('status')->default('open');
            $table->bigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index('belt_roll_production_id');
            $table->index('niwar_code_id');
            $table->index('status');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('belt_rolls');
    }
}
