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
    public function update(Request $request, Blog $blog)
    {
        request()->validate([
            'num_boleta' => 'required',
            'proveedor' => 'required',
            'motivo' => 'required',
            'ejecutora' => 'required',
            'usuario' => 'required',
            'caracteristicas' => 'required',
            'observaciones' => 'required',
            'fecha_inicio' => 'required',
            'fecha_final' => 'required'
        ]);
    
        // Actualizar el blog con los datos enviados desde el formulario
        // $blog->update($request->all());

        // Actualiza el registro en la tabla Blog
        $blog->update([
            
            'num_boleta' => $request->input('num_boleta'),
            'proveedor' => $request->input('proveedor'),
            'motivo' => $request->input('motivo'),
            'ejecutora' => $request->input('ejecutora'),

            'usuario' => Auth::user()->name,
        ]);
        // Busca el registro relacionado en la tabla waranty
        $waranty = waranty::where('blogs_id', $blog->id)->first();

        // Si se encontró el registro, actualiza sus datos
        if ($waranty) {
            $waranty->update([
                'caracteristicas' => $request->input('caracteristicas'),
                'observaciones' => $request->input('observaciones'),
                'fecha_inicio' => $request->input('fecha_inicio'),
                'fecha_final' => $request->input('fecha_final'),
            ]);           
        } else {
            // Si no se encontró el registro, crea uno nuevo
            waranty::create([
                'titulo' => $blog->num_boleta,
                'contenido' => $blog->motivo,
                'blogs_id' => $blog->id,
                'caracteristicas' => $request->input('caracteristicas'),
                'observaciones' => $request->input('observaciones'),
                'fecha_inicio' => $request->input('fecha_inicio'),
                'fecha_final' => $request->input('fecha_final'),            ]);
        }

        // // Crear el registro en la tabla de historial después de actualizar el blog
        // waranty::create([
        //     'blog_id' => $blog->id,
        //     'titulo' => $blog->num_boleta,
        //     'contenido' => $blog->motivo,
        //     'caracteristicas' => $request->input('caracteristicas'),
        //     'observaciones' => $request->input('observaciones'),
        //     'fecha_inicio' => $request->input('fecha_inicio'),
        //     'fecha_final' => $request->input('fecha_final'),
            
        // ]);
    
        return redirect()->route('tickets.index');
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
