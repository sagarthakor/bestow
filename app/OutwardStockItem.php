<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutwardStockItem extends Model
{
    public $table = "outward_stock_items";

    public function product_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(product::class, 'product', 'id');
    }
}
