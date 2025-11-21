<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'state_id',
        'city_id',
        'pincode',
        'is_default'
    ];

    public function state()
    {
        return $this->belongsTo(state::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(city::class, 'city_id');
    }

}
