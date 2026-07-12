<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesUpdateReasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_update_reason', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('so_no')->nullable();
            $table->longText('reason')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_update_reason');
    }
}
