<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeltRollProduction extends Model
{
    public $table = "belt_roll_production";

    public function niwar(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(NiwarCode::class, 'niwar_code_id', 'id');
    }

    public function roll_product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(product::class, 'roll_product_id', 'id');
    }

    public function customer_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(customers::class, 'customer', 'id');
    }

    public function materials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BeltRollProductionMaterial::class, 'belt_roll_production_id', 'id');
    }

    public function rolls(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BeltRoll::class, 'belt_roll_production_id', 'id');
    }

    public function formula(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RollFormulaMst::class, 'roll_formula_id', 'id');
    }

    /**
     * The recipe as it stood when this batch was woven, not as it stands now.
     * Null for batches started before formula versioning existed.
     *
     * Deliberately a lookup rather than a relation: the version is part of the
     * key, and a relation whose constraint depends on the parent row's own
     * columns would be applied from one instance to a whole eager-loaded set.
     */
    public function formulaRevision(): ?RollFormulaRevision
    {
        if (empty($this->roll_formula_id) || empty($this->roll_formula_version)) {
            return null;
        }

        return RollFormulaRevision::where('roll_formula_id', $this->roll_formula_id)
            ->where('version', $this->roll_formula_version)
            ->first();
    }

    public function isCancelled(): bool
    {
        return $this->status === 'C';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'Y';
    }

    /**
     * Meters woven against meters planned. 0 for a batch still open.
     */
    public function getEfficiencyAttribute(): float
    {
        if ($this->status != 'Y' || $this->planned_mtr <= 0) {
            return 0;
        }

        return round($this->produced_mtr / $this->planned_mtr * 100, 2);
    }
}
