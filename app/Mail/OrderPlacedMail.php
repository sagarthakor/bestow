<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $items;
    public $subtotal;
    public $shipping;
    public $grand;
    public $address;
    public function __construct($order, $mailData)
    {
        $this->order    = $order;
        $this->items    = $mailData['items'];
        $this->subtotal = $mailData['subtotal'];
        $this->shipping = $mailData['shipping'];
        $this->grand    = $mailData['grand'];
        $this->address  = $mailData['address'];
    }

    public function build()
    {
        return $this->subject('Your order '.$this->order->order_number.' has been placed')
            ->view('emails.order_placed')   // markdown hata ke view use kar lo (HTML template hai)
            ->with([
                'order'    => $this->order,
                'items'    => $this->items,
                'subtotal' => $this->subtotal,
                'shipping' => $this->shipping,
                'grand'    => $this->grand,
                'address'  => $this->address,
                'order_url'=> route('website.order.success', $this->order->id),
            ]);
    }

}
