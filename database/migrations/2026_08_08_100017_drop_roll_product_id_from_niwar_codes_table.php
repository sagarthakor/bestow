<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropRollProductIdFromNiwarCodesTable extends Migration
{
    /**
     * One roll product per niwar code was the wrong grain: a single niwar type
     * weaves many different semi products (NAVY 3 PATTI, and so on), each with
     * its own roll formula. The roll product now hangs off roll_formula_mst.
     *
     * The NIWAR ROLL products already created keep their 'semi finished' status,
     * so they stay usable as semi products and any stock they carry is untouched.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('niwar_codes', function (Blueprint $table) {
            $table->dropColumn('roll_product_id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('niwar_codes', function (Blueprint $table) {
            $table->integer('roll_product_id')->nullable()->after('inch_per_meter');
        });
    }
}
