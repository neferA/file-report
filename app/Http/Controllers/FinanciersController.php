<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Financiadora;

class FinanciersController extends Controller
{
    public function index()
    {
        $financiadoras = Financiadora::with('blogs')->get();

        return view('financiadoras.index', compact('financiadoras'));
    }
    public function create()
    {
        return view('financiadoras.crear');
    }
    public function store(Request $request)
    {
            $request->validate([
                'nombre' => 'required',
                'descripcion' => 'required',
            ]);

            $data = $request->all();

            $financiadora = Financiadora::create($data);

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
        $financiadora->nombre = $request->input('nombre');
        $financiadora->descripcion = $request->input('descripcion');
        // ... actualizar otros campos si es necesario ...
        $financiadora->save();

        return redirect()->route('financiers.index')
            ->with('success', 'Financiadora actualizada exitosamente');
    }
    public function autocomplete(Request $request)
    {
        $search = $request->get('search');
        $financiadoras = Financiadora::where('nombre', 'like', '%' . $search . '%')->pluck('nombre', 'id');

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

   

