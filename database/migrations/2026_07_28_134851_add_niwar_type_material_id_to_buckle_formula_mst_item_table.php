<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNiwarTypeMaterialIdToBuckleFormulaMstItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buckle_formula_mst_item', function (Blueprint $table) {
            $table->unsignedBigInteger('niwar_type_material_id')->nullable()->after('is_auto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buckle_formula_mst_item', function (Blueprint $table) {
            $table->dropColumn('niwar_type_material_id');
        });
    }
}
