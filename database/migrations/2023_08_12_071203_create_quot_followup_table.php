<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotFollowupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quot_followup', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('quot_id')->nullable();
            $table->string('quot_no')->nullable();
            $table->date('quot_date')->nullable();
            $table->date('followup_date')->nullable();
            $table->string('followp_time')->nullable();
            $table->date('next_follwup_date')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('quot_stage')->nullable();
            $table->text('remark')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('website_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quot_followup');
    }
}
