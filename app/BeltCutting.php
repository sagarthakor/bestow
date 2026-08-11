<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeltCutting extends Model
{
    public $table = "belt_cutting";

    public function roll(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BeltRoll::class, 'roll_id', 'id');
    }

    public function customer_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(customers::class, 'customer', 'id');
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BeltCuttingItem::class, 'belt_cutting_id', 'id');
    }

    public function materials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BeltCuttingMaterial::class, 'belt_cutting_id', 'id');
    }
}
