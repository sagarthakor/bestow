<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeltCuttingMaterial extends Model
{
    public $table = "belt_cutting_material";

    public function material_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(product::class, 'material', 'id');
    }
}
