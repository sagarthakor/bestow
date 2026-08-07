<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NiwarSizeChart extends Model
{
    protected $fillable = ['niwar_code_id', 'pp_size', 'required_inch'];
}
