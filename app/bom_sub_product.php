<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bom_sub_product extends Model
{
    protected $table="bom_sub_product";
    public $timestamps=false;
    /**
     * @var mixed
     */
    private $bom_id;
    /**
     * @var mixed
     */
    private $product;
}
