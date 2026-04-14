<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id','cart_id','amount','currency','status',
        'razorpay_order_id','razorpay_payment_id','razorpay_signature',
        'name','phone','address','city_id','state_id','city_name','state_name','pincode', 'order_number', 'order_status', 'payment_status', 'payment_method',
        'tracking_number','courier_name','tracking_url',
        'shipped_at','delivered_at','cancelled_at', 'shipping_charge', 'total_amount'
    ];

    protected $casts = [
        'shipped_at'   => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(customers::class, 'user_id', 'id');
    }

    public function order_items(){
        return $this->hasMany(OrderItem::class);
    }

    public function state()
    {
        return $this->belongsTo(state::class, 'state_id','id');
    }

    public function city()
    {
        return $this->belongsTo(city::class, 'city_id','id');
    }

    /** Display helpers — handle both dropdown (ID-based) and manual (text) entries */
    public function getCustomerNameAttribute()    { return $this->name; }
    public function getCustomerPhoneAttribute()   { return $this->phone; }
    public function getCustomerAddressAttribute() { return $this->address; }
    public function getCustomerPincodeAttribute() { return $this->pincode; }

    public function getCustomerStateAttribute()
    {
        if ($this->state_name) return $this->state_name;
        return optional($this->state)->state_name ?? '—';
    }

    public function getCustomerCityAttribute()
    {
        if ($this->city_name) return $this->city_name;
        return optional($this->city)->city_name ?? '—';
    }
}

