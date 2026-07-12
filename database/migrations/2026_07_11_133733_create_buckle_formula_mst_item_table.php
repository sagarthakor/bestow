<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuckleFormulaMstItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buckle_formula_mst_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('formula_id');
            $table->bigInteger('material');
            $table->double('qty');
            $table->timestamps();

            $table->index('formula_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buckle_formula_mst_item');
    }
}
