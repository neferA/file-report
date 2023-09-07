<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\BlogUpdated;
use App\Http\Controllers\BlogController; 
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
        $oldData = $event->oldData;
        $newData = $event->newData;
    
        $modifications = [];
    
        foreach ($newData as $field => $newValue) {
            $oldValue = $oldData[$field];
            if ($oldValue !== $newValue) {
                $modificationDetails = "Modificación en el campo '$field': $oldValue => $newValue";
                $modifications[] = $modificationDetails;
            }
        }
     // Crea una instancia del controlador BlogController
     $blogController = new BlogController();

     // Llama al método registerModification del controlador
     $blogController->registerModification($blog->id, implode(". ", $modifications));
        
    }
}