<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class formula_material extends Model
{
    //
    public $table="formula_material";
    public $timestamps=false;

    protected $fillable = [
        'raw_mat', 'percentage','user_id'
    ];
}
