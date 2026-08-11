<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * A frozen copy of a roll formula as it stood at one version. roll_formula_mst
 * always holds the current recipe - this is what lets a batch woven months ago
 * still show the recipe it was actually woven on.
 */
class RollFormulaRevision extends Model
{
    public $table = "roll_formula_revision";

    protected $casts = ['items' => 'array'];

    public function formula(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RollFormulaMst::class, 'roll_formula_id', 'id');
    }

    public function niwar(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(NiwarCode::class, 'niwar_code_id', 'id');
    }

    /**
     * The item list with material and category names resolved, for display. Names
     * are looked up now rather than stored, so a renamed dhaga reads correctly;
     * the quantities - the part that must never move - come from the snapshot.
     */
    public function itemsWithNames(): \Illuminate\Support\Collection
    {
        $items = collect($this->items ?? []);

        if ($items->isEmpty()) {
            return $items;
        }

        $materialNames = product::whereIn('id', $items->pluck('material'))->pluck('product_name', 'id');
        $categoryNames = NiwarTypeMaterial::with('material_item:id,product_name')
            ->whereIn('id', $items->pluck('niwar_type_material_id'))
            ->get()
            ->mapWithKeys(fn ($c) => [$c->id => $c->material_item->product_name ?? ('Material #' . $c->material)]);

        return $items->map(fn ($item) => (object) [
            'material' => $item['material'],
            'material_name' => $materialNames[$item['material']] ?? ('Material #' . $item['material']),
            'category_name' => $categoryNames[$item['niwar_type_material_id']] ?? '-',
            'gm_per_meter' => (float) $item['gm_per_meter'],
        ]);
    }

    /**
     * Normalised fingerprint of a recipe, used to decide whether a save is a real
     * revision or just a re-save of the same numbers. Order of entry must not
     * count as a change, so the rows are sorted before hashing.
     */
    public static function fingerprint($niwarCodeId, array $items): string
    {
        $normalised = array_map(fn ($i) => [
            (int) $i['niwar_type_material_id'],
            (int) $i['material'],
            number_format((float) $i['gm_per_meter'], 4, '.', ''),
        ], $items);

        sort($normalised);

        return md5(json_encode([(int) $niwarCodeId, $normalised]));
    }
}
