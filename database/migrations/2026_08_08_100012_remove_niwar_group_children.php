<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveNiwarGroupChildren extends Migration
{
    /**
     * The Niwar Code master now holds only what is genuinely fixed for a niwar
     * type: its plain materials (Mono) and the total gm/meter of each group
     * material (Roto). Which dhaga make up a group is decided per roll batch, in
     * Roll Production, because the colour mix changes order to order - so the
     * child rows added by AddParentIdToNiwarTypeMaterialsTable have no place to
     * live any more.
     *
     * Each batch's actual mix is recorded in belt_roll_production_material, so
     * nothing about production history depends on these rows.
     *
     * @return void
     */
    public function up()
    {
        DB::table('niwar_type_materials')->whereNotNull('parent_id')->delete();

        Schema::table('niwar_type_materials', function (Blueprint $table) {
            $table->dropIndex(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }

    /**
     * Brings the column back empty. The old child rows are not restored - the
     * split they held is per batch now, not per niwar type.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('niwar_type_materials', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('niwar_code_id');
            $table->index('parent_id');
        });
    }
}
