<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeltCosting extends Model
{
    protected $fillable = [
        'bukkal_id', 'niwar_id', 'miter', 'kadi_qty', 'kadi_rate',
        'size_label', 'panni_packing', 'total_cost', 'bukkal_rate', 'niwar_rate', 'bukkal_code',
        'bukkal_product_id', 'bukkal_qty', 'kadi_product_id', 'panni_product_id',
    ];

    public function bukkal()
    {
        return $this->belongsTo(BukkalCode::class);
    }

    public function niwar()
    {
        return $this->belongsTo(NiwarCode::class);
    }

    /**
     * Everything a belt is fitted with - bukkal, kadi, slider, rivet, packaging,
     * whatever the costing lists.
     */
    public function fittings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BeltCostingFitting::class, 'belt_costing_id', 'id')->orderBy('id');
    }

    /**
     * The fitting a single finished belt takes out of stock, as {product, qty,
     * label}. Lines with no product behind them are dropped: a costing rate on
     * its own cannot move stock.
     *
     * This is the whole fitting bill now. The dhaga is Roll Formula's job and the
     * meters are the size chart's, so nothing else about a belt is consumed here.
     */
    public function fittingPerBelt(): \Illuminate\Support\Collection
    {
        return $this->fittings
            ->map(fn ($f) => [
                'product' => $f->product,
                'qty' => (float) $f->qty,
                'label' => $f->label ?: 'Fitting',
            ])
            ->filter(fn ($row) => !empty($row['product']) && $row['qty'] > 0)
            ->values();
    }
}
