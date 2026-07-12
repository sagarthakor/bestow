<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuckleFormulaMstTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buckle_formula_mst', function (Blueprint $table) {
            $table->id();
            $table->integer('product');
            $table->string('size')->nullable();
            $table->string('nos')->default('1');
            $table->timestamps();

            $table->unique('product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buckle_formula_mst');
    }
}
