<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BlackWarrantyExpiredNotification extends Notification
{
    use Queueable;
    public $waranty;

    /**
     * Create a new notification instance.
     */
    public function __construct($waranty)
    {
        $this->waranty = $waranty;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Tu garantía está a punto de expirar.')
            ->action('Ver detalles', route('warranty.show', $this->waranty->id))
            ->line('Gracias por usar nuestra aplicación.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
