<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulaMstItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formula_mst_item', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('formula_id')->nullable();
            $table->string('nos')->nullable();
            $table->string('size')->nullable();
            $table->string('required_qty')->nullable();
            $table->bigInteger('material')->nullable();
            $table->double('percentage')->nullable();
            $table->double('qty')->nullable();
            $table->string('material_name')->nullable();
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
        Schema::dropIfExists('formula_mst_item');
    }
}
