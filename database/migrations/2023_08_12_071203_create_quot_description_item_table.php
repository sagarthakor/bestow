<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotDescriptionItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quot_description_item', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('quot_desc_id')->nullable();
            $table->bigInteger('item')->nullable();
            $table->bigInteger('quot_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quot_description_item');
    }
}
