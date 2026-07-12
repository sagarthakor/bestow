<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeltProduction extends Model
{
    public $table = "belt_production";

    public function belt_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(product::class, 'belt_product', 'id');
    }

    public function customer_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(customers::class, 'customer', 'id');
    }

    public function materials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BeltProductionMaterial::class, 'belt_production_id', 'id');
    }
}
