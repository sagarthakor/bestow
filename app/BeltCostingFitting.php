<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * One component a finished belt takes besides its niwar - a bukkal, a kadi, a
 * slider, a rivet, packaging. Held as a list on the belt costing so a new
 * component is a row, not a migration.
 */
class BeltCostingFitting extends Model
{
    public $table = "belt_costing_fitting";

    protected $fillable = ['belt_costing_id', 'label', 'product', 'qty'];

    public function product_item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(product::class, 'product', 'id');
    }
}
