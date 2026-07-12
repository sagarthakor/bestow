<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('pono')->nullable();
            $table->string('purchase_no')->nullable();
            $table->integer('vendor')->nullable();
            $table->string('vendor_name')->nullable();
            $table->integer('contact_name')->nullable();
            $table->string('subject')->nullable();
            $table->string('salesorder_no')->nullable();
            $table->date('po_date')->nullable();
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
            $table->double('cgsttotal')->nullable();
            $table->double('sgsttotal')->nullable();
            $table->double('igsttotal')->nullable();
            $table->double('adjustment')->nullable();
            $table->double('grand_total')->nullable();
            $table->integer('inward_status')->nullable();
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('invoice_file')->nullable();
            $table->string('receive')->nullable();
            $table->integer('finacial_year')->nullable();
            $table->integer('delete_status')->nullable();
            $table->bigInteger('delete_user')->nullable();
            $table->string('delete_datetime')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('vendor_po')->nullable();
            $table->string('module')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase');
    }
}
