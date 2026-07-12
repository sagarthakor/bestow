<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStitchingMachineAllocateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stitching_machine_allocate', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('machine')->nullable();
            $table->double('total_socks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stitching_machine_allocate');
    }
}
