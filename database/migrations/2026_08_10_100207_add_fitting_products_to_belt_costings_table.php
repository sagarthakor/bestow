<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFittingProductsToBeltCostingsTable extends Migration
{
    /**
     * Belt costing already says how much bukkal, kadi and panni a belt takes -
     * but only as rates, for pricing. There was never a product behind any of
     * them, so nothing could be taken out of stock.
     *
     * Now that cutting consumes the fitting straight off the costing (the belt
     * formula's own material rows turned out to be leftover dhaga from the old
     * single-stage flow), each of the three needs to name the product it moves.
     *
     * Nullable throughout: a costing with no product named simply consumes
     * nothing for that line, exactly as today.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('belt_costings', function (Blueprint $table) {
            $table->integer('bukkal_product_id')->nullable()->after('bukkal_id');
            // Bukkal is one per belt in every costing seen so far, but the rate
            // fields already allow a quantity for kadi and panni, so bukkal gets
            // one too rather than being the odd one out.
            $table->double('bukkal_qty')->default(1)->after('bukkal_product_id');
            $table->integer('kadi_product_id')->nullable()->after('kadi_qty');
            $table->integer('panni_product_id')->nullable()->after('panni_packing');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('belt_costings', function (Blueprint $table) {
            $table->dropColumn(['bukkal_product_id', 'bukkal_qty', 'kadi_product_id', 'panni_product_id']);
        });
    }
}
