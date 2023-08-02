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
    public function index()
    {
        $blogs = Blog::paginate(10); //Paginacion de 5
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
            // 'usuario' => 'required'
        ]);

        $data = $request->all();

        // Obtén el nombre del usuario actualmente autenticado y guárdalo en el campo correspondiente
        $data['usuario'] = Auth::user()->name;

        $blog = Blog::create($data);

        // Crea un nuevo registro en la tabla BlogHistory vinculado al registro de Blog recién creado
        waranty::create([
            'blogs_id' => $blog->id,
            'titulo' => $blog->num_boleta,
            'contenido' => $blog->motivo,
            // Agrega otros campos relevantes para el historial si es necesario
        ]);
        Blog::create($data);
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
    public function edit(Blog $blog)
    {
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
            'usuario' => 'required'
        ]);
    
        // Crear el registro en la tabla de historial antes de actualizar el blog
        waranty::create([
            'blog_id' => $blog->id,
            'titulo' => $blog->titulo,
            'contenido' => $blog->contenido,
        ]);
    
        // Actualizar el blog con los datos enviados desde el formulario
        $blog->update($request->all());
    
        return redirect()->route('tickets.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        // Borra los registros asociados en la tabla waranty_histories
        $blog->history()->delete();
        
        // Borra el registro en la tabla blogs
        $blog->delete();
    
        return redirect()->route('tickets.index');
    }
}
