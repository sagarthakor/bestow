<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_user', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('website')->nullable();
            $table->string('user_name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->integer('master_user')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->integer('country')->nullable();
            $table->integer('state')->nullable();
            $table->integer('city')->nullable();
            $table->string('address')->nullable();
            $table->string('age')->nullable();
            $table->string('photo')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->string('role')->nullable();
            $table->string('aadhar_card')->nullable();
            $table->string('pan_card')->nullable();
            $table->string('primary_phone')->nullable();
            $table->string('secondary_phone')->nullable();
            $table->string('secondary_email')->nullable();
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_user');
    }
}
