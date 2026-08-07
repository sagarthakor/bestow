<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNiwarSizeChartsTable extends Migration
{
    public function up()
    {
        Schema::create('niwar_size_charts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('niwar_code_id');
            $table->string('pp_size');
            $table->decimal('required_inch', 10, 2);
            $table->timestamps();

            $table->foreign('niwar_code_id')->references('id')->on('niwar_codes')->onDelete('cascade');
            $table->unique(['niwar_code_id', 'pp_size']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('niwar_size_charts');
    }
}
