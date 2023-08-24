<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Financiadora;
use App\Models\Blog;


class FinanciersController extends Controller
{
    public function index()
    {
        $financiadoras = Financiadora::with('blogs')->get();

        return view('financiadoras.index', compact('financiadoras'));
    }
    public function create()
    {
        $blogs = Blog::all(); // Obtén todos los blogs disponibles
        return view('financiadoras.crear', compact('blogs'));
    }
    
    public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required',
        'descripcion' => 'required',
    ]);

    $data = $request->all();

    $financiadora = Financiadora::create($data);

    // Obtén los IDs de los blogs relacionados seleccionados en el formulario
    $blogIds = $request->input('blog_ids');

    // Adjunta los IDs de los blogs relacionados a la tabla pivote
    $financiadora->blogs()->attach($blogIds);

    return redirect()->route('financiers.index')
        ->with('success', 'Financiadora creada exitosamente');
}

    
    public function edit($id)
    {
        $financiadora = Financiadora::findOrFail($id);
        return view('financiadoras.editar', compact('financiadora'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
        ]);

        $financiadora = Financiadora::findOrFail($id);
        $data = $request->all();
        
        $financiadora->update($data);

        return redirect()->route('financiers.index')
            ->with('success', 'Financiadora actualizada exitosamente.');
    }
    public function search(Request $request) {
        $term = $request->input('term');
    
        $financiadoras = Financiadora::where('nombre', 'LIKE', '%' . $term . '%')->pluck('nombre');
    
        return response()->json($financiadoras);
    }


    public function destroy($id)
    {
        $financiadora = Financiadora::findOrFail($id);

        $financiadora->delete();

        return redirect()->route('financiers.index')
            ->with('success', 'Financiadora eliminada exitosamente');
    }
    
}

   

