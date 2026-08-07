<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsGroupToNiwarTypeMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('niwar_type_materials', function (Blueprint $table) {
            $table->boolean('is_group')->default(false)->after('gm_per_meter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('niwar_type_materials', function (Blueprint $table) {
            $table->dropColumn('is_group');
        });
    }
}
