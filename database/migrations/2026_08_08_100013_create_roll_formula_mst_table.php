<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRollFormulaMstTable extends Migration
{
    /**
     * The roll formula: how one meter of a particular semi product (e.g. "NAVY
     * 3 PATTI ROLL") is made. Size does not exist at this level - the semi
     * product is size-less and only gets cut to size later.
     *
     * The niwar code decides which raw-material categories the formula must fill
     * (Mono + Roto on a PP niwar, no Mono on a cotton one) and, via its size
     * chart, how the finished roll is cut.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roll_formula_mst', function (Blueprint $table) {
            $table->id();
            $table->integer('product');
            $table->unsignedBigInteger('niwar_code_id');
            $table->timestamps();

            $table->unique('product');
            $table->index('niwar_code_id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roll_formula_mst');
    }
}
