<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
use App\Models\Modification; 

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

use App\Models\waranty;
use App\Models\Blog;
use App\Events\WarrantyExpired;
use Carbon\Carbon;

class HistoryController extends Controller
{
    public function index($id)
    {
        $blog = Blog::findOrFail($id);
        $historial = waranty::where('blogs_id', $id)
            ->with(['blog.financiadoras', 'blog.tipoGarantia'])
            ->paginate(1);

        // Llamar al método privado para manejar las alarmas
        $this->handleAlarms($historial);

        return view('historial.index', compact('blog', 'historial'));
    }
    public function show($id = null)
    {
        if ($id !== null) {
            // Mostrar detalles del recurso específico con ID $id
            $blog = Blog::findOrFail($id);
            $modifications = Modification::where('blogs_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();
            // Tu código adicional para mostrar los detalles del recurso aquí
            return view('historial.show', compact('blog', 'modifications'));
        } else {
            // Mostrar lista de recursos
            // Otra lógica para mostrar la lista
            return view('historial.index');
        }
        
    }
    private function handleAlarms($historial)
    {
        $now = now();
    
        foreach ($historial as $history) {
            $startDate = Carbon::parse($history->fecha_inicio);
            $endDate = Carbon::parse($history->fecha_final);
    
            $daysRemaining = $now->diffInDays($endDate, false);
            $daysSinceStart = $now->diffInDays($startDate, false);
    
            // Validar si el intervalo de tiempo es correcto y la fecha de finalización es válida
            $validator = Validator::make([
                'fecha_final' => $endDate,
                'fecha_inicio' => $startDate,
            ], [
                'fecha_inicio' => 'required|date',
                'fecha_final' => 'required|date',
            ]);
    
            if ($validator->passes()) {
                if ($this->isOrangeAlarm($daysSinceStart, $daysRemaining)) {
                    $this->handleOrangeAlarm($history); // Llama al método para manejar la alarma naranja
                } elseif ($this->isRedAlarm($daysSinceStart, $daysRemaining)) {
                    $this->handleRedAlarm($history); // Llama al método para manejar la alarma roja
                }
            }
        }
    }
    

    private function isOrangeAlarm($daysSinceStart, $daysRemaining)
    {
        return $daysRemaining === 13 || ($daysSinceStart <= 13 && $daysSinceStart > 12);
    }

    private function isRedAlarm($daysSinceStart, $daysRemaining)
    {
        return $daysRemaining === 11 || ($daysSinceStart <= 11 && $daysSinceStart > 10);
    }
   

}
   
   

    



