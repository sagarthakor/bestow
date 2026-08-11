<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRollFormulaIdToBeltRollProductionTable extends Migration
{
    /**
     * A roll batch is now started by picking a semi product, which means picking
     * its roll formula - the niwar code and roll product both come from there.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('belt_roll_production', function (Blueprint $table) {
            $table->unsignedBigInteger('roll_formula_id')->nullable()->after('batch_no');
            $table->index('roll_formula_id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('belt_roll_production', function (Blueprint $table) {
            $table->dropIndex(['roll_formula_id']);
            $table->dropColumn('roll_formula_id');
        });
    }
}
