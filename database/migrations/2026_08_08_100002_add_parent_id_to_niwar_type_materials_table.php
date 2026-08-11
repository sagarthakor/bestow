<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToNiwarTypeMaterialsTable extends Migration
{
    /**
     * A "group" niwar material (e.g. 300/ROTO) is not a real raw material - it is
     * fulfilled by a mix of actual dhaga. Until now that split was re-entered on
     * every size-wise belt formula and validated in BuckleFormulaController. The
     * split is really a property of the niwar code, not of the size, so the child
     * rows now hang off the parent here and are entered once.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('niwar_type_materials', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('niwar_code_id');
            $table->index('parent_id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('niwar_type_materials', function (Blueprint $table) {
            $table->dropIndex(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
}
