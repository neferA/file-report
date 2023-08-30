<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Models\waranty;
use App\Models\Blog;
use Illuminate\Support\Facades\Event;
use App\Events\WarrantyExpired;
use Carbon\Carbon;

class HistoryController extends Controller
{
    public function index($id)
    {
        $blog = Blog::findOrFail($id);

        $now = now();
        $endDateOrange = $now->copy()->addDays(13);
        $endDateRed = $now->copy()->addDays(11);

        $historial = Waranty::where('blogs_id', $id)
            ->where(function ($query) use ($endDateOrange, $endDateRed) {
                $query->whereDate('fecha_final', $endDateOrange)
                    ->orWhereDate('fecha_final', $endDateRed);
            })
            ->with(['blog.financiadoras', 'blog.tipoGarantia'])
            ->get();

        foreach ($historial as $waranty) {
            $endDate = Carbon::parse($waranty->fecha_final);
            $daysRemaining = $now->diffInDays($endDate, false);

            if ($endDate->isSameDay($endDateOrange)) {
                event(new WarrantyExpired($waranty, 'orange')); // Alarma naranja
            } elseif ($endDate->isSameDay($endDateRed)) {
                event(new WarrantyExpired($waranty, 'red')); // Alarma roja
            }
        }

        return view('historial.index', compact('blog', 'historial'));
    }
}   

    



