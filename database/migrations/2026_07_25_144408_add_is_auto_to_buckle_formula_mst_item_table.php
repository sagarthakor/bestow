<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAutoToBuckleFormulaMstItemTable extends Migration
{
    public function up()
    {
        Schema::table('buckle_formula_mst_item', function (Blueprint $table) {
            $table->boolean('is_auto')->default(false)->after('qty');
        });
    }

    public function down()
    {
        Schema::table('buckle_formula_mst_item', function (Blueprint $table) {
            $table->dropColumn('is_auto');
        });
    }
}
