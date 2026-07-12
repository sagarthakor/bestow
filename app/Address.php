<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sofa\Eloquence\Eloquence;

class Address extends Model
{
    protected $table = 'addresses';
    use Eloquence;
    CONST ACTIVE = 1, INACTIVE = 2;//status
    CONST HOME = 1, OFFICE = 2, OTHER = 3;//type

    protected $fillable = [
        'user_id', 'name', 'email', 'mobile',
        'address', 'landmark', 'country_id', 'state_id', 'city_id', 'pin_code',
        'type', 'created_by', 'updated_by'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getTypes($id = null)
    {
        $types = [1 => 'Home', 2 => 'Office', 3 => 'Other'];
        $map = ['home' => 1, 'office' => 2, 'other' => 3];

        if ($id && isset($map[$id])) {
            $id = $map[$id];
        }

        return $id ? ($types[$id] ?? null) : $types;
    }

    public function getTypeNameAttribute(): string
    {
        return self::getTypes($this->type);
    }

    public function getFullAddressAttribute(): string
    {
        $country_name =  $this->country ? $this->country->name : '';
        $state_name =  $this->state ? $this->state->name : '';
        $city_name =  $this->city ? $this->city->name : '';
        return $this->address .','. $this->landmark .','. $city_name .','. $state_name .','. $country_name .'-'. $this->pin_code;
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(country::class, 'country_id', 'id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(state::class, 'state_id', 'id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(city::class, 'city_id', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(customers::class,'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(customers::class,'updated_by', 'id');
    }
}
