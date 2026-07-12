<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id','sku','variant_name','price','stock','image','status'];

    public function product() {
        return $this->belongsTo(product::class);
    }

    public function attributes() {
        return $this->hasMany(ProductVariantAttribute::class,'variant_id');
    }
}

