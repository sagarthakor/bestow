<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MoveNiwarGroupSplitOutOfBeltFormula extends Migration
{
    /**
     * Belt formulas used to carry two things that are not size-dependent at all:
     * the auto-calculated dhaga rows (is_auto = 1) and the split of a "group"
     * niwar material across real dhaga (niwar_type_material_id set). Both belong
     * to roll production now.
     *
     * The split ratio itself is worth keeping, so it is back-converted from
     * whichever formula recorded it (qty / meters-for-that-size = gm per meter)
     * into child rows on niwar_type_materials before the formula rows are dropped.
     *
     * @return void
     */
    public function up()
    {
        $this->backfillGroupChildren();

        DB::table('buckle_formula_mst_item')
            ->where(function ($q) {
                $q->where('is_auto', 1)->orWhereNotNull('niwar_type_material_id');
            })
            ->delete();
    }

    private function backfillGroupChildren()
    {
        $groups = DB::table('niwar_type_materials')->where('is_group', 1)->get();

        foreach ($groups as $group) {
            $alreadySplit = DB::table('niwar_type_materials')->where('parent_id', $group->id)->exists();
            if ($alreadySplit) {
                continue;
            }

            // Any formula that used this group carries the same ratio, so the most
            // recently saved one is as good a source as any.
            $formulaId = DB::table('buckle_formula_mst_item')
                ->where('niwar_type_material_id', $group->id)
                ->max('formula_id');

            if (!$formulaId) {
                continue;
            }

            $meter = $this->metersForFormula($formulaId, $group->niwar_code_id);
            if (!$meter) {
                continue;
            }

            $rows = DB::table('buckle_formula_mst_item')
                ->where('niwar_type_material_id', $group->id)
                ->where('formula_id', $formulaId)
                ->get();

            foreach ($rows as $row) {
                DB::table('niwar_type_materials')->insert([
                    'niwar_code_id' => $group->niwar_code_id,
                    'parent_id' => $group->id,
                    'material' => $row->material,
                    'gm_per_meter' => round($row->qty / $meter, 4),
                    'is_group' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function metersForFormula($formulaId, $niwarCodeId)
    {
        $size = DB::table('buckle_formula_mst')->where('id', $formulaId)->value('size');
        if ($size === null || trim((string) $size) === '') {
            return null;
        }

        $requiredInch = DB::table('niwar_size_charts')
            ->where('niwar_code_id', $niwarCodeId)
            ->where('pp_size', trim((string) $size))
            ->value('required_inch');

        if (!$requiredInch) {
            return null;
        }

        $inchPerMeter = DB::table('niwar_codes')->where('id', $niwarCodeId)->value('inch_per_meter') ?: 39.37;

        return $requiredInch / $inchPerMeter;
    }

    /**
     * No-op: the deleted formula rows are reconstructible from the niwar code
     * details, and re-inserting guessed rows would be worse than leaving the
     * formulas as fittings-only.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
