<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulaMstTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formula_mst', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nos')->nullable();
            $table->string('size')->nullable();
            $table->string('required_qty')->nullable();
            $table->string('product')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formula_mst');
    }
}
