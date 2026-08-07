<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBeltCostingIdToBuckleFormulaMstTable extends Migration
{
    public function up()
    {
        Schema::table('buckle_formula_mst', function (Blueprint $table) {
            $table->unsignedBigInteger('belt_costing_id')->nullable()->after('product');
        });
    }

    public function down()
    {
        Schema::table('buckle_formula_mst', function (Blueprint $table) {
            $table->dropColumn('belt_costing_id');
        });
    }
}
