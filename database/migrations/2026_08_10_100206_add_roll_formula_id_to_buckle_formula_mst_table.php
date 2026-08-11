<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRollFormulaIdToBuckleFormulaMstTable extends Migration
{
    /**
     * Which semi product a finished belt is cut from.
     *
     * Until now a belt was only tied to a niwar code, through its belt costing.
     * But one niwar code weaves several different semi products - PP/11 makes
     * both "Niwar 3 patti belt" and "Navy 5 patti belt" - so cutting any PP/11
     * roll offered every belt costed against PP/11, including ones that plainly
     * cannot come out of that roll.
     *
     * Nullable on purpose: existing formulas keep working against the niwar code
     * alone, and cutting only narrows to the semi product once the link is set.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buckle_formula_mst', function (Blueprint $table) {
            $table->unsignedBigInteger('roll_formula_id')->nullable()->after('belt_costing_id');
            $table->index('roll_formula_id');
        });

        $this->linkWhereUnambiguous();
    }

    /**
     * Where a niwar code has exactly one semi product, the link is not a guess -
     * there is nothing else the belt could be cut from. Anything less certain is
     * left null for a human to set.
     */
    private function linkWhereUnambiguous()
    {
        $singles = DB::table('roll_formula_mst')
            ->select('niwar_code_id', DB::raw('MIN(id) as roll_formula_id'), DB::raw('COUNT(*) as n'))
            ->groupBy('niwar_code_id')
            ->having('n', '=', 1)
            ->get();

        foreach ($singles as $single) {
            $costingIds = DB::table('belt_costings')->where('niwar_id', $single->niwar_code_id)->pluck('id');

            if ($costingIds->isEmpty()) {
                continue;
            }

            DB::table('buckle_formula_mst')
                ->whereIn('belt_costing_id', $costingIds)
                ->whereNull('roll_formula_id')
                ->update(['roll_formula_id' => $single->roll_formula_id]);
        }
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('buckle_formula_mst', function (Blueprint $table) {
            $table->dropIndex(['roll_formula_id']);
            $table->dropColumn('roll_formula_id');
        });
    }
}
