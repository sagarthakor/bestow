<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuckleFormulaMstItem extends Model
{
    public $table = "buckle_formula_mst_item";

    public function material_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(product::class, 'material', 'id');
    }
}
