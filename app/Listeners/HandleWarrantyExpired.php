<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\RedWarrantyExpiredNotification;
use App\Notifications\OrangeWarrantyExpiredNotification;
use App\Notifications\BlackWarrantyExpiredNotification; 
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
        $isBlackAlarm = $event->isBlackAlarm; // Obtén el valor de la alarma negra

        if ($isRedAlarm) {
            $this->handleRedAlarm($waranty);
        } elseif ($isOrangeAlarm) {
            $this->handleOrangeAlarm($waranty);
        } elseif ($isBlackAlarm) { // Maneja la alarma negra si es verdadero
            $this->handleBlackAlarm($waranty);
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
        $waranty->user->notify(new OrangeWarrantyExpiredNotification($waranty));    
    }
    private function handleBlackAlarm($waranty)
    {
        // Lógica para manejar la alarma negra
        // Por ejemplo, enviar una notificación negra
        // $waranty->user->notify(new BlackWarrantyExpiredNotification($waranty));
    }
}
