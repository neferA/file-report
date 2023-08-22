<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoGarantia;

class WarantyController extends Controller
{
    public function index()
    {
        $tipos= TipoGarantia::all();
        return view('garantias.index', compact('tipos'));
    }
    public function create()
    {
        return view('garantias.crear');
    }
    public function store(Request $request)
    {
            $request->validate([
                'nombre' => 'required',
                'descripcion' => 'required',
            ]);

            $data = $request->all();

            $tipo = TipoGarantia::create($data);

            return redirect()->route('waranty.index')
                ->with('success', 'garantia creada exitosamente');
    }
    public function edit($id)
    {
        $tipo = TipoGarantia::findOrFail($id);
        return view('garantias.editar', compact('tipo'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
        ]);

        $tipo = TipoGarantia::findOrFail($id);
        $data = $request->all();
        
        $tipo->update($data);

        return redirect()->route('waranty.index')
            ->with('success', 'garantia actualizada exitosamente.');
    }
    public function destroy($id)
    {
        $tipo = TipoGarantia::findOrFail($id);

        $tipo->delete();

        return redirect()->route('waranty.index')
            ->with('success', 'garantia eliminada exitosamente');
    }
}
