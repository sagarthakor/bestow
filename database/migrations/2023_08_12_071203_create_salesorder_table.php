<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesorder', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('sono')->nullable();
            $table->string('salaesorder_no')->nullable();
            $table->integer('customer')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('subject')->nullable();
            $table->string('quotation_no')->nullable();
            $table->integer('quot_no')->nullable();
            $table->date('quot_date')->nullable();
            $table->date('salaesorder_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('purchase_order')->nullable();
            $table->string('status')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_postalcode')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_postalcode')->nullable();
            $table->text('term_condition')->nullable();
            $table->text('remark')->nullable();
            $table->integer('website_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('so_status')->nullable();
            $table->double('net_amount')->nullable();
            $table->double('discount_total')->nullable();
            $table->double('gst_amount')->nullable();
            $table->double('grand_total')->nullable();
            $table->double('cgstamount')->nullable();
            $table->double('sgstamount')->nullable();
            $table->double('adjustment')->nullable();
            $table->string('invoice_status')->nullable();
            $table->string('deliverychallan_status')->nullable();
            $table->integer('module')->nullable();
            $table->string('payment_terms')->nullable();
            $table->integer('finacial_year')->nullable();
            $table->integer('delete_status')->nullable();
            $table->bigInteger('delete_user')->nullable();
            $table->string('delete_datetime')->nullable();
            $table->string('purchase_order_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salesorder');
    }
}
