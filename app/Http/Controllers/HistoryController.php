<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\waranty;
use App\Models\Blog;

class HistoryController extends Controller
{
    public function index($id)
    {
        $blog = Blog::findOrFail($id);
        $historial = waranty::where('blogs_id', $id)->paginate(10); // Cambia el número 10 según la cantidad de registros por página que desees mostrar
        return view('historial.index', compact('blog', 'historial'));
    }
}
