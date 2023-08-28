<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Models\waranty;
use App\Models\Blog;
use Illuminate\Support\Facades\Event;
use App\Events\WarrantyExpired;

class HistoryController extends Controller
{
    public function index($id)
    {
        $blog = Blog::findOrFail($id);
        $historial = Waranty::where('blogs_id', $id)->with(['blog.financiadoras', 'blog.tipoGarantia'])->paginate(10);

        // Agregar la lógica para crear alarmas aquí
        foreach ($historial as $warranty) {
            $now = now();
            $startDate = $warranty->fecha_inicio;
            $endDate = $warranty->fecha_final;

            // Calcular las fechas para las alarmas
            $redAlarmDate = $endDate->subDays(11);
            $orangeAlarmDate = $endDate->subDays(13);

            // Comprobar y disparar las alarmas
            if ($endDate && $now->gte($redAlarmDate) && $now->lte($endDate)) {
                event(new WarrantyExpired($warranty, 'red')); // Alarma roja
            } elseif ($endDate && $now->gte($orangeAlarmDate) && $now->lte($redAlarmDate)) {
                event(new WarrantyExpired($warranty, 'orange')); // Alarma naranja
            }
        }

        return view('historial.index', compact('blog', 'historial'));
    }
}


