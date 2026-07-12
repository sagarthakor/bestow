<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_module', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('module')->nullable();
            $table->string('submodule_name')->nullable();
            $table->string('submodule_url')->nullable();
            $table->string('icon')->nullable();
            $table->integer('srno')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_module');
    }
}
