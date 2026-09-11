<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutwardStock extends Model
{
    public $table = "outward_stocks";

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OutwardStockItem::class, 'outward_stock_id', 'id');
    }
}
