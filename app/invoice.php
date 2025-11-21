<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    protected $table="invoice";
    public $timestamps=false;
    
    public function invoiceItems()
    {
        return $this->hashMany(invoice_item::class, 'id','invoice_id');
    }
}
