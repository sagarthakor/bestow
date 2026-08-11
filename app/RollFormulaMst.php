<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RollFormulaMst extends Model
{
    public $table = "roll_formula_mst";

    protected $casts = ['effective_date' => 'date'];

    /** Category id => name for this formula's niwar code, resolved on first use. */
    private $categoryNameCache = null;

    /**
     * The size-less semi product this formula makes, stocked in meters.
     */
    public function product_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(product::class, 'product', 'id');
    }

    public function niwar(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(NiwarCode::class, 'niwar_code_id', 'id');
    }

    /**
     * The finished belt this roll is woven to become. One variant stands for the
     * whole family - its size is not what was chosen, its identity is.
     */
    public function belt_product_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(product::class, 'belt_product_id', 'id');
    }

    /**
     * The product family name cutting reads the size range from. Null when no
     * belt product has been picked, which cutting treats as "no narrowing".
     */
    public function beltProductFamily(): ?string
    {
        if (empty($this->belt_product_id)) {
            return null;
        }

        $name = $this->belt_product_item->product_name ?? null;

        return $name !== null && trim($name) !== '' ? trim($name) : null;
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RollFormulaMstItem::class, 'roll_formula_id', 'id');
    }

    /**
     * Every state this formula has ever been saved in, newest first.
     */
    public function revisions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RollFormulaRevision::class, 'roll_formula_id', 'id')->orderByDesc('version');
    }

    /**
     * Only an active formula may start a batch - an obsolete recipe stays
     * readable for the batches that used it without being offered for new ones.
     */
    public function isActive(): bool
    {
        return ($this->status ?? 'active') === 'active';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Raw material needed for the given number of meters, in grams, with the
     * niwar category each row belongs to. This is the whole point of the formula
     * and the only thing roll production asks it for.
     *
     * Returns rows of {material, gm_per_meter, required_qty, category,
     * niwar_type_material_id}.
     */
    public function materialsForMeters($totalMtr): \Illuminate\Support\Collection
    {
        $categoryNames = $this->categoryNames();

        return $this->items->map(fn ($item) => (object) [
            'material' => $item->material,
            'niwar_type_material_id' => $item->niwar_type_material_id,
            'gm_per_meter' => (float) $item->gm_per_meter,
            'required_qty' => round($item->gm_per_meter * $totalMtr, 2),
            'category' => $categoryNames[$item->niwar_type_material_id] ?? null,
        ])->values();
    }

    /**
     * Category id => display name for this formula's niwar code, fetched once
     * per model instance since materialsForMeters() is called repeatedly
     * (preview, gate, save) within one request.
     */
    public function categoryNames(): \Illuminate\Support\Collection
    {
        if ($this->categoryNameCache === null) {
            $this->categoryNameCache = NiwarTypeMaterial::with('material_item:id,product_name')
                ->where('niwar_code_id', $this->niwar_code_id)
                ->get()
                ->mapWithKeys(fn ($c) => [$c->id => $c->material_item->product_name ?? ('Material #' . $c->material)]);
        }

        return $this->categoryNameCache;
    }

    /**
     * Item rows in the shape the revision snapshot and the category-total check
     * both work in.
     */
    public function itemRows(): array
    {
        return $this->items->map(fn ($i) => [
            'niwar_type_material_id' => (int) $i->niwar_type_material_id,
            'material' => (int) $i->material,
            'gm_per_meter' => (float) $i->gm_per_meter,
        ])->all();
    }
}
