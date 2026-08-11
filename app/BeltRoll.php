<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeltRoll extends Model
{
    public $table = "belt_rolls";

    public function niwar(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(NiwarCode::class, 'niwar_code_id', 'id');
    }

    public function roll_product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(product::class, 'roll_product_id', 'id');
    }

    public function roll_production(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BeltRollProduction::class, 'belt_roll_production_id', 'id');
    }

    public function cuttings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BeltCutting::class, 'roll_id', 'id');
    }

    /**
     * Meters of this roll that have become belts or trim - everything that has
     * left it, whether it ended up as product or as scrap.
     */
    public function getConsumedMtrAttribute(): float
    {
        return round($this->length_mtr - $this->remaining_mtr, 2);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Roll label used in every dropdown and listing, e.g.
     * "R-000012 - PP/11 - 70 mtr (balance 18.40)".
     */
    public function getLabelAttribute(): string
    {
        $niwar = $this->niwar ? $this->niwar->type . '/' . $this->niwar->code : '';

        return trim($this->roll_no . ' - ' . $niwar . ' - ' . rtrim(rtrim(number_format($this->length_mtr, 2), '0'), '.')
            . ' mtr (balance ' . number_format($this->remaining_mtr, 2) . ')');
    }
}
