<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NiwarCode extends Model
{
    protected $fillable = ['type', 'code', 'rate', 'inch_per_meter'];

    public function materials()
    {
        return $this->hasMany(NiwarTypeMaterial::class, 'niwar_code_id');
    }

    public function sizeChart()
    {
        return $this->hasMany(NiwarSizeChart::class, 'niwar_code_id')->orderBy('pp_size');
    }
}
