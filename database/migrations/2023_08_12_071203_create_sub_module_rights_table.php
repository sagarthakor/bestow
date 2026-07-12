<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubModuleRightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_module_rights', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable();
            $table->integer('module_id')->nullable();
            $table->integer('main_module')->nullable();
            $table->integer('module_add')->nullable();
            $table->integer('module_edit')->nullable();
            $table->integer('module_delete')->nullable();
            $table->integer('module_view')->nullable();
            $table->integer('access')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_module_rights');
    }
}
