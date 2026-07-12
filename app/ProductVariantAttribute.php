<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantAttribute extends Model
{
    protected $fillable = ['variant_id','attribute_name','attribute_value'];

    public function variant() {
        return $this->belongsTo(ProductVariant::class,'variant_id');
    }
}

