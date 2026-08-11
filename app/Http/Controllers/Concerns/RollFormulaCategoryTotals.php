<?php

namespace App\Http\Controllers\Concerns;

use App\NiwarTypeMaterial;

/**
 * The one rule the whole roll costing rests on: within a niwar category, the raw
 * materials chosen must add up to exactly the gm/meter the Niwar Code master
 * fixes for it.
 *
 * A "group" category such as Roto is a total (12.1 gm/meter) fulfilled by a mix
 * of coloured threads; 4.0 + 3.5 + 2.6 + 2.0 must come to 12.1, not 12.3. Both
 * the Roll Formula screen (before saving) and Roll Production (before issuing
 * dhaga against a formula that may have been saved before its niwar code was
 * last edited) check it, so they are never allowed to disagree.
 */
trait RollFormulaCategoryTotals
{
    /**
     * gm/meter is stored to 4 decimals, so anything under half a milligram per
     * meter is entry noise rather than a real mismatch. Over a 100 mtr roll that
     * is 0.05 gm - well inside what a floor scale can even read.
     */
    protected $categoryTotalTolerance = 0.0005;

    /**
     * Problems with the given rows against a niwar code's categories, as a list
     * of human-readable messages. Empty means the formula balances.
     *
     * $rows is a list of ['niwar_type_material_id' => id, 'material' => id,
     * 'gm_per_meter' => float] - the same shape the revision snapshot stores, so
     * a formula can be re-checked straight from what was frozen.
     */
    protected function categoryTotalErrors($niwarCodeId, array $rows): array
    {
        $categories = NiwarTypeMaterial::with('material_item:id,product_name')
            ->where('niwar_code_id', $niwarCodeId)
            ->get();

        if ($categories->isEmpty()) {
            return ['This niwar code has no raw material rate yet - set its gm/meter rows in Niwar Code > Manage Details first.'];
        }

        $sums = [];
        $materialsPerCategory = [];
        foreach ($rows as $row) {
            $categoryId = $row['niwar_type_material_id'];
            $sums[$categoryId] = ($sums[$categoryId] ?? 0) + $row['gm_per_meter'];
            $materialsPerCategory[$categoryId][] = $row['material'];
        }

        $errors = [];
        foreach ($categories as $category) {
            $name = $category->material_item->product_name ?? ('Material #' . $category->material);
            $target = (float) $category->gm_per_meter;
            $actual = (float) ($sums[$category->id] ?? 0);
            $difference = round($target - $actual, 4);

            if (!isset($sums[$category->id])) {
                $errors[] = "{$name}: no raw material selected. Add the raw materials making up its "
                    . $this->trimNumber($target) . ' gm/meter.';
                continue;
            }

            if ($actual <= 0) {
                $errors[] = "{$name}: the raw materials add up to zero. Enter the gm/meter of each.";
                continue;
            }

            if (abs($difference) > $this->categoryTotalTolerance) {
                $errors[] = "{$name}: raw materials add up to " . $this->trimNumber($actual)
                    . ' gm/meter, but the niwar rate is ' . $this->trimNumber($target) . ' gm/meter - '
                    . ($difference > 0 ? 'short by ' : 'over by ') . $this->trimNumber(abs($difference)) . ' gm/meter.';
            }

            // The same dhaga twice in one category is always a data-entry slip,
            // and it makes the consumption report double-count the material.
            $duplicates = array_diff_assoc(
                $materialsPerCategory[$category->id],
                array_unique($materialsPerCategory[$category->id])
            );
            if ($duplicates) {
                $errors[] = "{$name}: the same raw material is listed more than once. Combine the rows into one.";
            }
        }

        // A row pointing at a category that does not belong to this niwar code
        // would silently never be checked, so catch it rather than store it.
        $known = $categories->pluck('id')->all();
        foreach (array_keys($sums) as $categoryId) {
            if (!in_array($categoryId, $known)) {
                $errors[] = 'A raw material row belongs to a different niwar code - reselect the niwar code and re-enter the materials.';
                break;
            }
        }

        return $errors;
    }

    /**
     * 12.1000 reads as 12.1, but 12.1050 keeps the digits that matter.
     */
    private function trimNumber($value): string
    {
        return rtrim(rtrim(number_format((float) $value, 4, '.', ''), '0'), '.') ?: '0';
    }
}
