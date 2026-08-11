<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBeltProductToRollFormulaMstTable extends Migration
{
    /**
     * Which finished belt this roll is woven to become.
     *
     * A roll has no size - it is meters of niwar - so the size on the product
     * picked here is deliberately ignored. What the pick is for is the product
     * *identity*: cutting a roll of this formula then offers exactly that
     * product's own size range, instead of every sized belt in the catalogue.
     *
     * Stored as one variant's id rather than a name, because ids are what the
     * rest of the system joins on; the family is resolved from its product_name
     * when cutting needs the sizes.
     *
     * Nullable: a formula without it still works, cutting just falls back to the
     * whole sized catalogue as before.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roll_formula_mst', function (Blueprint $table) {
            $table->integer('belt_product_id')->nullable()->after('product');
            $table->index('belt_product_id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('roll_formula_mst', function (Blueprint $table) {
            $table->dropIndex(['belt_product_id']);
            $table->dropColumn('belt_product_id');
        });
    }
}
