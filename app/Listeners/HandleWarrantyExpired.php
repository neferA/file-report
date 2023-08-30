<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\WarrantyExpired;
class HandleWarrantyExpired
{
    /**
     * Handle the event.
     *
     * @param  WarrantyExpired  $event
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WarrantyExpired $event)
    {
        // $waranty = $event->waranty;
        $alarmColor = $event->alarmColor;

        if ($alarmColor === 'red') {
            // Enviar una notificación roja
            // Notification::send($waranty->user, new RedWarrantyExpiredNotification($waranty));
        } elseif ($alarmColor === 'orange') {
            // Enviar una notificación naranja
            // Notification::send($waranty->user, new OrangeWarrantyExpiredNotification($waranty));
        }
    }
}
