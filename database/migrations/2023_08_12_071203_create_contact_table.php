<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->string('primary_phone')->nullable();
            $table->string('secondary_phone')->nullable();
            $table->string('primary_email')->nullable();
            $table->timestamps();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->integer('website_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('secondary_email')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('owner_mobile')->nullable();
            $table->string('alternate_no')->nullable();
            $table->string('owner_email')->nullable();
            $table->string('work_email')->nullable();
            $table->string('owner_gst')->nullable();
            $table->string('owner_pan')->nullable();
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('billing_pobox')->nullable();
            $table->string('shipping_pobox')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('billing_postalcode')->nullable();
            $table->string('shipping_postalcode')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('shipping_country')->nullable();
            $table->text('description')->nullable();
            $table->string('created_time')->nullable();
            $table->integer('industry')->nullable();
            $table->integer('type')->nullable();
            $table->date('support_start_date')->nullable();
            $table->date('support_end_date')->nullable();
            $table->string('photo')->nullable();
            $table->string('office_phone')->nullable();
            $table->string('office_email')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact');
    }
}
