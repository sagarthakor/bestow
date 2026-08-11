<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeltRollProductionMaterial extends Model
{
    public $table = "belt_roll_production_material";

    public function material_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(product::class, 'material', 'id');
    }
}
