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
        $warranty = $event->warranty;
        $alarmColor = $event->alarmColor;

        if ($alarmColor === 'red') {
            // Acciones para la alarma roja
            // Por ejemplo, enviar una notificación roja
        } elseif ($alarmColor === 'orange') {
            // Acciones para la alarma naranja
            // Por ejemplo, enviar una notificación naranja
        }

        // Resto de la lógica del oyente
    }
}
