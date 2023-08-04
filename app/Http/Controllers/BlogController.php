<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\waranty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver_tickets|crear-blog|editar-blog|borrar-blog')
            ->only('index');
        $this->middleware('permission:crear_tickets',['only'=>['create', 'store']]);
        $this->middleware('permission:editar_tickets',['only'=>['edit', 'update']]);
        $this->middleware('permission:borrar_tickets',['only'=>['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Blog::query();

        $search = $request->input('search');

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('num_boleta', 'like', '%' . $search . '%')
              ->orWhere('proveedor', 'like', '%' . $search . '%')
              ->orWhere('motivo', 'like', '%' . $search . '%')
              ->orWhere('ejecutora', 'like', '%' . $search . '%');
        });
    }

    $blogs = $query->paginate(10);

    return view('blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blogs.crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'num_boleta' => 'required',
            'proveedor' => 'required',
            'motivo' => 'required',
            'ejecutora' => 'required',
            'caracteristicas' => 'required',
            'observaciones' => 'required',
            'fecha_inicio' => 'required',
            'fecha_final' => 'required'
        ]);

        $data = $request->all();

        // Obtén el nombre del usuario actualmente autenticado y guárdalo en el campo correspondiente
        $data['usuario'] = Auth::user()->name;

        $blog = Blog::create($data);

        // Crea un nuevo registro en la tabla waranyt_Histories vinculado al registro de Blog recién creado
        waranty::create([
            'blogs_id' => $blog->id,
            'titulo' => $blog->num_boleta,
            'contenido' => $blog->motivo,
            'caracteristicas' => $request->input('caracteristicas'),
            'observaciones' => $request->input('observaciones'),
            'fecha_inicio' => $request->input('fecha_inicio'),
            'fecha_final' => $request->input('fecha_final'),
        ]);
        return redirect()->route('tickets.index');
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
        $blog = Blog::findOrFail($id);
        return view('blogs.editar', compact('blog'));
    }
    /**
     * Update the specified resource in storage.
     */
       
    // ...
    
    public function update(Request $request, $id)
    {
        // Validar los datos recibidos del formulario de edición
        $request->validate([
            'num_boleta' => 'required',
            'proveedor' => 'required',
            'motivo' => 'required',
            'ejecutora' => 'required',
            'caracteristicas' => 'required',
            'observaciones' => 'required',
            'fecha_inicio' => 'required',
            'fecha_final' => 'required',
           
        ]);

        // Buscar el registro en la tabla blogs
        $blog = Blog::findOrFail($id);

        // Verificar que el usuario autenticado tenga permisos para editar este registro.
        if (Auth::user()->id === $blog->user_id) {
            // Actualizar el registro en la tabla blogs
            $blog->update($request->all());

            // Obtener el modelo "waranty" asociado, si existe
            $waranty = $blog->waranty;

            // Si también deseas actualizar la tabla waranty_histories
            if ($waranty) {
                $waranty->update([
                    'titulo' => $blog->num_boleta,
                    'contenido' => $blog->motivo,
                    'caracteristicas' => $request->input('caracteristicas'),
                    'observaciones' => $request->input('observaciones'),
                    'fecha_inicio' => $request->input('fecha_inicio'),
                    'fecha_final' => $request->input('fecha_final'),
                ]);
            } else {
                // Si no existe un modelo "waranty" asociado, crear uno nuevo.
                Waranty::create([
                    'blogs_id' => $blog->id,
                    'titulo' => $blog->num_boleta,
                    'contenido' => $blog->motivo,
                    'caracteristicas' => $request->input('caracteristicas'),
                    'observaciones' => $request->input('observaciones'),
                    'fecha_inicio' => $request->input('fecha_inicio'),
                    'fecha_final' => $request->input('fecha_final'),
                ]);
            }

            // Redireccionar a la página de detalles o a donde prefieras después de la actualización.
            return redirect()->route('tickets.index')->with('success', 'Blog actualizado exitosamente.');
        } else {
            // El usuario no tiene permisos para editar este registro.
            return redirect()->back()->with('error', 'No tienes permisos para editar este blog.');
        }
    }
       
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
    
    // Aquí puedes agregar lógica adicional antes de eliminar el registro, si es necesario
    
    $blog->delete();
    
    return redirect()->route('tickets.index')->with('success', 'Blog eliminado exitosamente.');
}
}
