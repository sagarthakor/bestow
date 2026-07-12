<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id','product_id','variant_id','qty','price','name','image'];
    public function cart(){ return $this->belongsTo(Cart::class); }
    public function product(){ return $this->belongsTo(product::class, 'product_id'); }
}
