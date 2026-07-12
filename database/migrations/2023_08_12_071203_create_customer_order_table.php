<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_order', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('order_no')->nullable();
            $table->string('order_number')->nullable();
            $table->integer('customer')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->date('order_date')->nullable();
            $table->string('datetime')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('billing_postalcode')->nullable();
            $table->string('shipping_postalcode')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('shipping_country')->nullable();
            $table->longText('term_condition')->nullable();
            $table->double('net_amount')->nullable();
            $table->double('discount_total')->nullable();
            $table->double('cgsttotal')->nullable();
            $table->double('sgsttotal')->nullable();
            $table->double('adjustment')->nullable();
            $table->double('gst_amount')->nullable();
            $table->double('gstper')->nullable();
            $table->double('module')->nullable();
            $table->double('remark')->nullable();
            $table->string('so_status')->nullable();
            $table->string('quot_status')->nullable();
            $table->string('payment_terms')->nullable();
            $table->date('order_duedate')->nullable();
            $table->string('status')->nullable();
            $table->double('grand_total')->nullable();
            $table->string('salesman')->nullable();
            $table->string('payment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_order');
    }
}
