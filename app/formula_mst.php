<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class formula_mst extends Model
{
    //
    public $table="formula_mst";
    public $timestamps=false;
    use Eloquence;

    public function product_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(product::class, 'product', 'id');
    }
}
