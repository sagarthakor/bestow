<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class BuckleFormulaMst extends Model
{
    public $table = "buckle_formula_mst";
    use Eloquence;

    public function product_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(product::class, 'product', 'id');
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BuckleFormulaMstItem::class, 'formula_id', 'id');
    }

    public function belt_costing(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BeltCosting::class, 'belt_costing_id', 'id');
    }
}
