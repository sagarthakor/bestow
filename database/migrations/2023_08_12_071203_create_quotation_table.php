<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('quot_no')->nullable();
            $table->string('quotation_no')->nullable();
            $table->bigInteger('revise_quot_no')->nullable();
            $table->integer('customer')->nullable();
            $table->integer('contact_name')->nullable();
            $table->string('customer_name')->nullable();
            $table->date('quot_date')->nullable();
            $table->double('grand_total')->nullable();
            $table->integer('website_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('quot_stage')->nullable();
            $table->date('valid_until')->nullable();
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
            $table->text('term_condition')->nullable();
            $table->double('net_amount')->nullable();
            $table->double('discount_total')->nullable();
            $table->double('gst_amount')->nullable();
            $table->string('gstper')->nullable();
            $table->integer('module')->nullable();
            $table->text('remark')->nullable();
            $table->string('so_status')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('creation_time')->nullable();
            $table->integer('range')->nullable();
            $table->integer('category')->nullable();
            $table->integer('base')->nullable();
            $table->string('group')->nullable();
            $table->string('datetime')->nullable();
            $table->double('cgsttotal')->nullable();
            $table->double('sgsttotal')->nullable();
            $table->double('adjustment')->nullable();
            $table->string('payment_terms')->nullable();
            $table->integer('finacial_year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotation');
    }
}
