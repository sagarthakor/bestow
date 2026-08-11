<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NiwarCode extends Model
{
    protected $fillable = ['type', 'code', 'rate', 'inch_per_meter'];

    /**
     * The raw-material categories a meter of this niwar is made of, each with the
     * gm per meter it must come to - Mono and Roto on a PP niwar, only the direct
     * material on a cotton one. Which actual raw materials fill a category is
     * decided per semi product, in its roll formula.
     */
    public function materials()
    {
        return $this->hasMany(NiwarTypeMaterial::class, 'niwar_code_id');
    }

    public function sizeChart()
    {
        return $this->hasMany(NiwarSizeChart::class, 'niwar_code_id')->orderBy('pp_size');
    }

    public function getLabelAttribute(): string
    {
        return trim($this->type . '/' . $this->code);
    }

    /**
     * What one meter of this niwar weighs, split by raw-material category -
     * Mono 9.22 g, Roto 12.10 g and so on. These category totals are exactly what
     * every roll formula on this code has to add up to, so they are equally the
     * weight of a meter however it was woven.
     */
    public function weightBreakdown(): \Illuminate\Support\Collection
    {
        return $this->materials->map(fn ($m) => (object) [
            'name' => $m->material_item->product_name ?? ('Material #' . $m->material),
            'is_group' => (bool) $m->is_group,
            'gm_per_meter' => (float) $m->gm_per_meter,
        ])->values();
    }

    /**
     * Total grams of dhaga in one meter of this niwar.
     */
    public function gramsPerMeter(): float
    {
        return round($this->materials->sum('gm_per_meter'), 4);
    }

    /**
     * Grams of dhaga in one belt of the given P.P size. Null when the size is not
     * charted, same as metersForSize.
     */
    public function gramsForSize($ppSize): ?float
    {
        $meters = $this->metersForSize($ppSize);

        return $meters === null ? null : round($meters * $this->gramsPerMeter(), 2);
    }

    /**
     * Inches of this niwar one belt of the given P.P size eats, straight off the
     * size chart - "size 28 takes 30 inch". Null when the size is not charted.
     */
    public function inchForSize($ppSize): ?float
    {
        $inch = $this->sizeChart->firstWhere('pp_size', trim((string) $ppSize))->required_inch ?? null;

        return $inch ? (float) $inch : null;
    }

    /**
     * How many meters of this niwar one belt of the given P.P size eats.
     * Null when the size is not on the chart - callers treat that as "cannot cut".
     */
    public function metersForSize($ppSize): ?float
    {
        $requiredInch = $this->inchForSize($ppSize);

        if (!$requiredInch) {
            return null;
        }

        return round($requiredInch / ($this->inch_per_meter ?: 39.37), 4);
    }
}
