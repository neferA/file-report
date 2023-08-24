<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Models\waranty;
use App\Models\Blog;

class HistoryController extends Controller
{
    public function index($id)
{
    $blog = Blog::findOrFail($id);
    $historial = Waranty::where('blogs_id', $id)->with(['blog.financiadoras', 'blog.tipoGarantia'])->paginate(10);
    return view('historial.index', compact('blog', 'historial'));
}

    

}
