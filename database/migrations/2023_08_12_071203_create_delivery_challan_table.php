<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryChallanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_challan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('challan_no')->nullable();
            $table->string('challan_number')->nullable();
            $table->integer('customer')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('subject')->nullable();
            $table->string('salaesorder_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('payment_terms')->nullable();
            $table->date('invoice_duedate')->nullable();
            $table->string('status')->nullable();
            $table->string('remark')->nullable();
            $table->double('item_total')->nullable();
            $table->double('discount_total')->nullable();
            $table->double('cgsttotal')->nullable();
            $table->double('sgsttotal')->nullable();
            $table->double('igsttotal')->nullable();
            $table->double('adjustment')->nullable();
            $table->double('grand_total')->nullable();
            $table->longText('billing_address')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_postalcode')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_postalcode')->nullable();
            $table->longText('term_condition')->nullable();
            $table->integer('module')->nullable();
            $table->string('invoice')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('website_id')->nullable();
            $table->string('purchase_order')->nullable();
            $table->integer('finacial_year')->nullable();
            $table->integer('delete_status')->nullable();
            $table->bigInteger('delete_user')->nullable();
            $table->string('delete_datetime')->nullable();
            $table->string('purchase_order_date')->nullable();
            $table->string('salaesorder_date')->nullable();
            $table->string('quotation_no')->nullable();
            $table->string('quot_date')->nullable();
            $table->string('transport_name')->nullable();
            $table->string('receipt_no')->nullable();
            $table->string('receipt_date')->nullable();
            $table->bigInteger('without_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_challan');
    }
}
