<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
    protected $fillable = [
        'state_id',
        'min_amount',
        'max_amount',
        'shipping_fee',
        'is_active',
    ];

    public function state()
    {
        return $this->belongsTo(state::class, 'state_id');
    }
}
