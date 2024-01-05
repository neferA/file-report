<?php

namespace App\Http\Controllers;

use App\Models\afianzadora;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $afianzadoras = $this->getAfianzadoras($request);
        return view('afianzados.index', compact('afianzadoras'));
    }
    
    private function getAfianzadoras(Request $request)
    {
        $query = Afianzadora::with('blogs');
    
        $searchTerm = $request->input('search');
        $order = $request->input('order', 'desc');
    
        if ($searchTerm) {
            $query->where('nombre', 'like', "%$searchTerm%")
                ->orWhere('descripcion', 'like', "%$searchTerm%");
            // Añade más columnas según tus necesidades
        }
    
        // Ordenamiento
        $orderColumn = 'created_at';
        $orderDirection = 'desc';
    
        if ($order === 'asc') {
            $orderDirection = 'asc';
        } elseif ($order === 'updated_at_desc') {
            $orderColumn = 'updated_at';
        }
    
        // Aplicar el ordenamiento
        $query->orderBy($orderColumn, $orderDirection);
    
        return $query->get();
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('afianzados.crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
        ]);

        $data = $request->all();

        $afianzadora = afianzadora::create($data);

        return redirect()->route('suppliers.index')
            ->with('success', 'afianzado creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $afianzadora = afianzadora::findOrFail($id);
        return view('afianzados.editar', compact('afianzadora'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
        ]);

        $afianzadora = afianzadora::findOrFail($id);
        $data = $request->all();
        
        $afianzadora->update($data);

        return redirect()->route('suppliers.index')
            ->with('success', 'ejecutora actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tipo = afianzadora::findOrFail($id);

        $tipo->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'ejecutora eliminada exitosamente');
    }
}
