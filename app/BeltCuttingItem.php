<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeltCuttingItem extends Model
{
    public $table = "belt_cutting_item";

    public function belt_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(product::class, 'belt_product', 'id');
    }

    public function materials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BeltCuttingMaterial::class, 'belt_cutting_item_id', 'id');
    }
}
