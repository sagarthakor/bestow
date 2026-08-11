<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RollFormulaMstItem extends Model
{
    public $table = "roll_formula_mst_item";

    public function material_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(product::class, 'material', 'id');
    }

    /**
     * The niwar code's category this row fills - Mono, Roto and so on.
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(NiwarTypeMaterial::class, 'niwar_type_material_id', 'id');
    }
}
