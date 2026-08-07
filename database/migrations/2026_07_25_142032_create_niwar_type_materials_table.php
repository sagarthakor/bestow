<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNiwarTypeMaterialsTable extends Migration
{
    public function up()
    {
        Schema::create('niwar_type_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('niwar_code_id');
            $table->bigInteger('material');
            $table->decimal('gm_per_meter', 10, 4);
            $table->timestamps();

            $table->foreign('niwar_code_id')->references('id')->on('niwar_codes')->onDelete('cascade');
            $table->index('niwar_code_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('niwar_type_materials');
    }
}
