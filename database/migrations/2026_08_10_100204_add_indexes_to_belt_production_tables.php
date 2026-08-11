<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToBeltProductionTables extends Migration
{
    /**
     * The consumption and cutting reports group by material and by belt product,
     * and the roll register filters by roll product - all unindexed columns until
     * now, which is fine at a handful of rows and not fine after a year of
     * batches.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('belt_roll_production_material', function (Blueprint $table) {
            $table->index('material');
        });

        Schema::table('belt_cutting_material', function (Blueprint $table) {
            $table->index('material');
        });

        Schema::table('belt_cutting_item', function (Blueprint $table) {
            $table->index('belt_product');
        });

        Schema::table('belt_rolls', function (Blueprint $table) {
            $table->index('roll_product_id');
        });

        Schema::table('belt_roll_production', function (Blueprint $table) {
            $table->index('status');
            $table->index('roll_product_id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('belt_roll_production_material', function (Blueprint $table) {
            $table->dropIndex(['material']);
        });

        Schema::table('belt_cutting_material', function (Blueprint $table) {
            $table->dropIndex(['material']);
        });

        Schema::table('belt_cutting_item', function (Blueprint $table) {
            $table->dropIndex(['belt_product']);
        });

        Schema::table('belt_rolls', function (Blueprint $table) {
            $table->dropIndex(['roll_product_id']);
        });

        Schema::table('belt_roll_production', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['roll_product_id']);
        });
    }
}
