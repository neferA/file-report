<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\RedWarrantyExpiredNotification;
use App\Notifications\OrangeWarrantyExpiredNotification;
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
        $waranty = $event->waranty;
        $isRedAlarm = $event->isRedAlarm;
        $isOrangeAlarm = $event->isOrangeAlarm;

        if ($isRedAlarm) {
            $this->handleRedAlarm($waranty);
        } elseif ($isOrangeAlarm) {
            $this->handleOrangeAlarm($waranty);
        }
    }

    private function handleRedAlarm($waranty)
    {
        // Lógica para manejar la alarma roja
        // Por ejemplo, enviar una notificación roja
        $waranty->user->notify(new RedWarrantyExpiredNotification($waranty));
    }    

    private function handleOrangeAlarm($waranty)
    {
        // Lógica para manejar la alarma naranja
        // Por ejemplo, enviar una notificación naranja
        $waranty->user->notify(new OrangeWarrantyExpiredNotification($waranty));    }
}
