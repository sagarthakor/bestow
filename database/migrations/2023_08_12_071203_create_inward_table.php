<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInwardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inward', function (Blueprint $table) {
            $table->integer('id', true);
            $table->double('inward_no')->nullable();
            $table->string('inward_number')->nullable();
            $table->integer('customer')->nullable();
            $table->integer('vendor')->nullable();
            $table->date('inward_date')->nullable();
            $table->string('inward_type')->nullable();
            $table->string('inward_from')->nullable();
            $table->string('purchase')->nullable();
            $table->string('subject')->nullable();
            $table->string('remark')->nullable();
            $table->string('created_time')->nullable();
            $table->integer('website_id')->nullable();
            $table->integer('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inward');
    }
}
