<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeltCosting extends Model
{
    protected $fillable = [
        'bukkal_id', 'niwar_id', 'miter', 'kadi_qty', 'kadi_rate',
        'size_label', 'panni_packing', 'total_cost', 'bukkal_rate', 'niwar_rate', 'bukkal_code'
    ];

    public function bukkal()
    {
        return $this->belongsTo(BukkalCode::class);
    }

    public function niwar()
    {
        return $this->belongsTo(NiwarCode::class);
    }
}
