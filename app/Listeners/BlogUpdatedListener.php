<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\BlogUpdated;
use Illuminate\Support\Facades\Log;

class BlogUpdatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BlogUpdated $event)
    {
        $blog = $event->blog;
        $newData = $event->newData;

        // Realiza la comparación de campos y registra las modificaciones en el log
        foreach ($newData as $field => $value) {
            if ($blog->$field !== $value) {
                $modificationDetails = "Modificación en el campo '$field' del Blog #$blog->id: {$blog->$field} => $value";
                // Llamar al método público del controlador para registrar la modificación
                app('App\Http\Controllers\BlogController')->registerModification($blog->id, $modificationDetails);
            }
        }
    }   
        
}
