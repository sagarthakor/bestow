<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderShipped extends Notification
{
    use Queueable;
    public $order;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];  // You can add 'database', 'sms' also here
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Order is Shipped 📦')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Great news! Your order #' . $this->order->id . ' has been shipped.')
            ->line('Courier: ' . ($this->order->courier_name ?? 'Not available'))
            ->line('Tracking Number: ' . ($this->order->tracking_number ?? 'Not available'))
            ->action('Track Your Order', $this->order->tracking_url ?? url('/orders'))
            ->line('Thank you for shopping with us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
