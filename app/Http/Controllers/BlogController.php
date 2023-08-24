<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\waranty;
use App\Models\TipoGarantia;
use App\Models\Financiadora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
    $financiadoras = Financiadora::pluck('nombre', 'id');
    $garantias = TipoGarantia::pluck('nombre', 'id');
    
    return view('blogs.crear', compact('financiadoras', 'garantias'));
}
    
    private function uploadPDF($file, $folder)
        {
            if ($file) {
                $pdfPath = $file->store($folder, 'public');
                return $pdfPath;
            }
            return null;
        }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'num_boleta' => 'required',
            'tipo_garantia_id' => 'required',
            'proveedor' => 'required',
            'motivo' => 'required',
            'ejecutora' => 'required',
            'caracteristicas' => 'required',
            'observaciones' => 'required',
            'monto' => 'required',
            'fecha_inicio' => 'required',
            'fecha_final' => 'required',
            'estado' => 'required|in:' . implode(',', [
                Blog::ESTADO_LIBERADO,
                Blog::ESTADO_EJECUTADO,
                Blog::ESTADO_RENOVADO,
            ]),
            'boleta_pdf' => 'nullable|mimes:pdf|max:2048', // Validación para el archivo PDF
            'nota_pdf' => 'nullable|mimes:pdf|max:2048',   // Validación para el archivo PDF
        ]);

        // Cargar y almacenar el archivo PDF 'boleta_pdf'
        $data = $request->except(['boleta_pdf', 'nota_pdf']);

        if ($request->hasFile('boleta_pdf')) {
            $data['boleta_pdf'] = $this->uploadPDF($request->file('boleta_pdf'), 'boletas_pdfs');
        }
        
        // Verificar si se cargó un nuevo archivo nota_pdf
        if ($request->hasFile('nota_pdf')) {
            $data['nota_pdf'] = $this->uploadPDF($request->file('nota_pdf'), 'notas_pdfs');
        }

        // Obtén el nombre del usuario actualmente autenticado y guárdalo en el campo correspondiente
        $data['usuario'] = Auth::user()->name;

        $blog = Blog::create($data);
         // Obtén los IDs de las financiadoras seleccionadas
        $financiadoraIds = $request->input('financiadora_id');

        // Adjunta los IDs de las financiadoras relacionadas a la tabla pivote
        $blog->financiadoras()->attach($financiadoraIds);
        
        // Crea un nuevo registro en la tabla waranyt_Histories vinculado al registro de Blog recién creado
        $waranty = Waranty::create([
            'blogs_id' => $blog->id,
            'titulo' => $blog->num_boleta,
            'contenido' => $blog->motivo,
            'caracteristicas' => $request->input('caracteristicas'),    
            'observaciones' => $request->input('observaciones'),
            'monto' => $request->input('monto'),
            'boleta_pdf' => $data['boleta_pdf'] ?? null,
            'nota_pdf' => $data['nota_pdf'] ?? null,
            'fecha_inicio' => $request->input('fecha_inicio'),
            'fecha_final' => $request->input('fecha_final'),
        ]);
        
        // Verificar si se cargó un nuevo archivo boleta_pdf y actualizar si es necesario
        if ($request->hasFile('boleta_pdf')) {
            $waranty->update(['boleta_pdf' => $data['boleta_pdf']]);
        }
        
        // Verificar si se cargó un nuevo archivo nota_pdf y actualizar si es necesario
        if ($request->hasFile('nota_pdf')) {
            $waranty->update(['nota_pdf' => $data['nota_pdf']]);
        }
       // Asociar la garantía al blog creado
        $tipoGarantia = TipoGarantia::find($request->input('tipo_garantia_id'));
        $blog->tipoGarantia()->associate($tipoGarantia);

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
            'monto' => 'required',
            'observaciones' => 'required',
            'fecha_inicio' => 'required',
            'fecha_final' => 'required',
            'estado' => 'required|in:' . implode(',', [
                Blog::ESTADO_LIBERADO,
                Blog::ESTADO_EJECUTADO,
                Blog::ESTADO_RENOVADO,
            ]),
            'new_boleta_pdf' => 'nullable|mimes:pdf|max:2048', // Validación para el archivo PDF
            'new_nota_pdf' => 'nullable|mimes:pdf|max:2048',   // Validación para el archivo PDF
        ]);

        // Buscar el registro en la tabla blogs
        $blog = Blog::findOrFail($id);
        $data = $request->except(['new_boleta_pdf', 'new_nota_pdf']);

        // Verificar si se cargó un nuevo archivo new_boleta_pdf
        if ($request->hasFile('new_boleta_pdf')) {
            // Eliminar el archivo antiguo si existe
            if ($blog->waranty && $blog->waranty->boleta_pdf) {
                Storage::delete($blog->waranty->boleta_pdf);
            }
            // Subir el nuevo archivo
            $data['boleta_pdf'] = $this->uploadPDF($request->file('new_boleta_pdf'), 'boletas_pdfs');

        }

        // Verificar si se cargó un nuevo archivo new_nota_pdf
        if ($request->hasFile('new_nota_pdf')) {
            // Eliminar el archivo antiguo si existe
            if ($blog->waranty && $blog->waranty->nota_pdf) {
                Storage::delete($blog->waranty->nota_pdf);
            }
            // Subir el nuevo archivo
            $data['nota_pdf'] = $this->uploadPDF($request->file('new_nota_pdf'), 'notas_pdfs');
        }

        // Actualizar el registro en la tabla blogs
        $blog->update($data);

        // Obtener o crear el modelo "waranty" asociado
        $waranty = Waranty::where('blogs_id', $blog->id)->first();
        if ($waranty) {
            $waranty->update([
                'titulo' => $blog->num_boleta,
                'contenido' => $blog->motivo,
                'caracteristicas' => $request->input('caracteristicas'),
                'observaciones' => $request->input('observaciones'),
                'monto' => $request->input('monto'),
                'boleta_pdf' => $data['boleta_pdf'] ?? $waranty->boleta_pdf,
                'nota_pdf' => $data['nota_pdf'] ?? $waranty->nota_pdf,
                'fecha_inicio' => $request->input('fecha_inicio'),
                'fecha_final' => $request->input('fecha_final'),
            ]);
        } else {
            Waranty::create([
                'blogs_id' => $blog->id,
                'titulo' => $blog->num_boleta,
                'contenido' => $blog->motivo,
                'caracteristicas' => $request->input('caracteristicas'),
                'observaciones' => $request->input('observaciones'),
                'monto' => $request->input('monto'),
                'boleta_pdf' => $data['boleta_pdf'] ?? null,
                'nota_pdf' => $data['nota_pdf'] ?? null,
                'fecha_inicio' => $request->input('fecha_inicio'),
                'fecha_final' => $request->input('fecha_final'),
            ]);
        }

        // Redireccionar a la página de detalles o a donde prefieras después de la actualización.
        return redirect()->route('tickets.index')->with('success', 'Blog actualizado exitosamente.');
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
    
        if ($blog->waranty) {
            // Definir rutas de almacenamiento de PDFs
            $boletaPdfPath = $blog->waranty->boleta_pdf;
            $notaPdfPath = $blog->waranty->nota_pdf;
    
            // Verificar y eliminar archivos PDF si existen
            $this->deletePDF($boletaPdfPath);
            $this->deletePDF($notaPdfPath);
        }
    
        // Eliminar el registro del blog
        $blog->delete();
    
        return redirect()->route('tickets.index')->with('success', 'Blog eliminado exitosamente.');
    }
    
    /**
     * Elimina un archivo PDF del almacenamiento si existe.
     *
     * @param string|null $pdfPath
     * @return void
     */
    protected function deletePDF($pdfPath)
    {
        if ($pdfPath) {
            $fullPath = 'public/' . $pdfPath;
            if (Storage::exists($fullPath)) {
                Storage::delete($fullPath);
            }
        }
    }

   
}

    

