<?php

namespace App\Http\Controllers;

use App\Models\ejecutora;
use Illuminate\Http\Request;

class ExecutorController extends Controller
{
    public function index()
    {
        $ejecutoras= ejecutora::all();
        return view('ejecutoras.index', compact('ejecutoras'));
    }
    public function create()
    {
        return view('ejecutoras.crear');
    }
    public function store(Request $request)
    {
            $request->validate([
                'nombre' => 'required',
                'descripcion' => 'required',
            ]);

            $data = $request->all();

            $ejecutora = ejecutora::create($data);

            return redirect()->route('executor.index')
                ->with('success', 'garantia creada exitosamente');
    }
    public function edit($id)
    {
        $ejecutora = ejecutora::findOrFail($id);
        return view('ejecutoras.editar', compact('ejecutora'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
        ]);

        $ejecutora = ejecutora::findOrFail($id);
        $data = $request->all();
        
        $ejecutora->update($data);

        return redirect()->route('executor.index')
            ->with('success', 'ejecutora actualizada exitosamente.');
    }
    public function destroy($id)
    {
        $tipo = ejecutora::findOrFail($id);

        $tipo->delete();

        return redirect()->route('executor.index')
            ->with('success', 'ejecutora eliminada exitosamente');
    }
}
