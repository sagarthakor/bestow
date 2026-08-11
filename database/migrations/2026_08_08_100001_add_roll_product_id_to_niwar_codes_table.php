<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRollProductIdToNiwarCodesTable extends Migration
{
    /**
     * Links a Niwar Code to the size-less semi-finished "roll" product whose
     * stock is carried in meters. Roll production inwards this product, and
     * cutting outwards it - that is what lets a roll exist in stock without a
     * size, unlike the finished belt products which are always size-wise.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('niwar_codes', function (Blueprint $table) {
            $table->integer('roll_product_id')->nullable()->after('inch_per_meter');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('niwar_codes', function (Blueprint $table) {
            $table->dropColumn('roll_product_id');
        });
    }
}
