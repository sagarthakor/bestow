<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesmanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesman', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('salesman_name')->nullable();
            $table->string('salesman_area')->nullable();
            $table->string('salesman_code')->nullable();
            $table->string('salesman_mobile')->nullable();
            $table->string('salesman_email')->nullable();
            $table->string('salesman_password')->nullable();
            $table->string('otp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salesman');
    }
}
