<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceRenewalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_renewal', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('services_name')->nullable();
            $table->integer('service')->nullable();
            $table->integer('category')->nullable();
            $table->integer('customer')->nullable();
            $table->integer('usage_unit')->nullable();
            $table->date('sales_start_date')->nullable();
            $table->date('sales_end_date')->nullable();
            $table->date('support_start_date')->nullable();
            $table->date('support_expiry_date')->nullable();
            $table->double('price')->nullable();
            $table->double('purchase_cost')->nullable();
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
        Schema::dropIfExists('service_renewal');
    }
}
