<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinishProductCustomerToPurchaseRequirementTable extends Migration
{
    public function up()
    {
        Schema::table('purchase_requirement', function (Blueprint $table) {
            $table->integer('finish_product')->nullable()->after('order_no');
            $table->integer('customer')->nullable()->after('finish_product');
        });
    }

    public function down()
    {
        Schema::table('purchase_requirement', function (Blueprint $table) {
            $table->dropColumn(['finish_product', 'customer']);
        });
    }
}
