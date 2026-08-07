<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NiwarTypeMaterial extends Model
{
    protected $fillable = ['niwar_code_id', 'material', 'gm_per_meter', 'is_group'];

    public function material_item()
    {
        return $this->belongsTo(product::class, 'material', 'id');
    }
}
