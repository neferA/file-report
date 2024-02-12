<?php
namespace App\Http\Controllers;



use Illuminate\Support\Facades\Validator;

use App\Models\Modification; 
use App\Models\ModificationsPdf;
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

        // Acceder a las modificaciones generales asociadas al blog
        $modifications = $blog->modifications()
            ->orderBy('created_at', 'desc')
            ->get();

        // Acceder a las modificaciones de PDF asociadas al blog
        $pdfModifications = $blog->historial()
            ->orderBy('created_at', 'desc')
            ->get();

        // Tu código adicional para mostrar los detalles del recurso aquí
        return view('historial.show', compact('blog', 'modifications', 'pdfModifications'));
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
    
            if ($this->isRedAlarm($daysSinceStart, $daysRemaining)) {
                event(new WarrantyExpired($history, true, false)); // Alarma roja
            } elseif ($this->isOrangeAlarm($daysSinceStart, $daysRemaining)) {
                event(new WarrantyExpired($history, false, true)); // Alarma naranja
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
   
   

    



