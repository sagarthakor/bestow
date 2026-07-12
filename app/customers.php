<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class customers extends Model
{
    use Notifiable;

    protected $table = "customers";
    public $timestamps = false;

    // ✅ Tell Laravel to send notifications to primary_email field
    public function routeNotificationForMail($notification)
    {
        return $this->primary_email;
    }
}
