<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\ejecutora;
use App\Models\waranty;
use App\Models\TipoGarantia;
use App\Models\Financiadora;
use App\Events\BlogUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Modification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

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
    
        // Filtro por estado
        $estado = $request->input('estado');
        if ($estado) {
            $query->where('estado', $estado);
        }
    
        $search = $request->input('search');
    
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('num_boleta', 'like', '%' . $search . '%')
                  ->orWhere('proveedor', 'like', '%' . $search . '%')
                  ->orWhere('motivo', 'like', '%' . $search . '%')
                  ->orWhere('unidad_ejecutora_id', 'like', '%' . $search . '%');
            });
        }
    
        // Ordenamiento
        $orden = $request->input('orden');
        $ordenColumna = 'created_at';
        $ordenDireccion = 'desc';
        
        if ($orden === 'creacion_asc') {
            $ordenDireccion = 'asc';
        }
        elseif ($orden === 'modificacion_desc') {
            $ordenColumna = 'updated_at';
            $ordenDireccion = 'desc';
        }
    
        $query->orderBy($ordenColumna, $ordenDireccion);
    
        // Cache de resultados
        $cacheKey = 'search_results_' . $search . '_' . $estado . '_' . $orden;
        $minutes = 60; // Tiempo de cache en minutos
    
        $blogs = Cache::remember($cacheKey, $minutes, function () use ($query) {
            return $query->simplePaginate(5);
        });
        
          
        return view('blogs.index', compact('blogs'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $financiadoras = Financiadora::pluck('nombre', 'id');
        $garantias = TipoGarantia::pluck('nombre', 'id');
        $ejecutoras = ejecutora::pluck('nombre', 'id');
        
        return view('blogs.crear', compact('financiadoras','garantias','ejecutoras'));
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
            'unidad_ejecutora_id' => 'required',
            'proveedor' => 'required',
            'motivo' => 'required',
            'caracteristicas' => 'required',
            'observaciones' => 'required',
            'monto' => 'required',
            'fecha_inicio' => 'required|date_format:Y-m-d',
            'fecha_final' => 'required|date_format:Y-m-d',
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

        // Parsear las fechas usando Carbon
        $data['fecha_inicio'] = Carbon::parse($request->input('fecha_inicio'));
        $data['fecha_final'] = Carbon::parse($request->input('fecha_final'));

        // Obtén el nombre del usuario actualmente autenticado y guárdalo en el campo correspondiente
        $data['usuario'] = Auth::user()->name;

        $blog = Blog::create($data);

         // Obtén los IDs de las financiadoras seleccionadas
        $financiadoraIds = $request->input('financiadora_id');

        // Adjunta los IDs de las financiadoras relacionadas a la tabla pivote
        $blog->financiadoras()->attach($financiadoraIds);
        
        // Crea un nuevo registro en la tabla waranty_Histories vinculado al registro de Blog recién creado
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

        // Asociar la ejecutora al blog creado
        $unidadEjecutora = ejecutora::find($request->input('unidad_ejecutora_id'));
        $blog->unidadEjecutora()->associate($unidadEjecutora);

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
        $financiadoras = Financiadora::pluck('nombre', 'id'); // Obtener las financiadoras para el campo select
        $garantias = TipoGarantia::pluck('nombre', 'id'); // Obtener los tipos de garantía para el campo select
        $ejecutoras = ejecutora::pluck('nombre', 'id'); // Obtener las ejecutoras para el campo select

        return view('blogs.editar', compact('blog', 'financiadoras', 'garantias','ejecutoras'));
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

        // Almacenar los nombres de archivo antiguos
        $oldBoletaPdf = $blog->waranty ? $blog->waranty->boleta_pdf : null;
        $oldNotaPdf = $blog->waranty ? $blog->waranty->nota_pdf : null;

        // Verificar si se cargó un nuevo archivo new_boleta_pdf
        if ($request->hasFile('new_boleta_pdf')) {
            if ($oldBoletaPdf) {
                $this->deletePDF($oldBoletaPdf);
            }
            // Subir el nuevo archivo
            $data['boleta_pdf'] = $this->uploadPDF($request->file('new_boleta_pdf'), 'boletas_pdfs');
        }

        // Verificar si se cargó un nuevo archivo new_nota_pdf
        if ($request->hasFile('new_nota_pdf')) {
            if ($oldNotaPdf) {
                $this->deletePDF($oldNotaPdf);
            }
            // Subir el nuevo archivo
            $data['nota_pdf'] = $this->uploadPDF($request->file('new_nota_pdf'), 'notas_pdfs');
        }

    // Resto de la lógica para actualizar campos en el registro
   
    // Actualizar el registro en la tabla blogs
    $blog->update($data);

    // Llama al nuevo método para registrar las modificaciones
    $modifications = $this->Modifications($blog, $request->all());

    // Dispara el evento BlogUpdated con los datos relevantes
    event(new BlogUpdated($blog, $request->all(), $modifications));

    // Eliminar registros de archivos PDF que se desean reemplazar
    if ($request->hasFile('new_boleta_pdf') && $oldBoletaPdf) {
        $this->deletePDF($oldBoletaPdf);
    }

    if ($request->hasFile('new_nota_pdf') && $oldNotaPdf) {
        $this->deletePDF($oldNotaPdf);
    }

    // Actualizar el registro en la tabla blogs
    $blog->update($data);

    // Verificar si hay una garantía asociada
    if ($blog->waranty) {
        // Actualizar boleta_pdf si es necesario
        if (isset($data['boleta_pdf'])) {
            $blog->waranty->update(['boleta_pdf' => $data['boleta_pdf']]);
        }
    
        // Actualizar nota_pdf si es necesario
        if (isset($data['nota_pdf'])) {
            $blog->waranty->update(['nota_pdf' => $data['nota_pdf']]);
        }
    }
        // Actualizar la asociación de financiadoras
        $financiadoraIds = $request->input('financiadora_id');
        $blog->financiadoras()->sync($financiadoraIds);
    
        // Actualizar la asociación del tipo de garantía
        $tipoGarantia = TipoGarantia::find($request->input('tipo_garantia_id'));
        $blog->tipoGarantia()->associate($tipoGarantia);

        // Actualizar la ejecutora de la ejecutora
        $unidadEjecutora = ejecutora::find($request->input('unidad_ejecutora_id'));
        $blog->unidadEjecutora()->associate($unidadEjecutora);

        // Parsear las fechas usando Carbon
        $data['fecha_inicio'] = Carbon::parse($request->input('fecha_inicio'));
        $data['fecha_final'] = Carbon::parse($request->input('fecha_final'));
 
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
    private function Modifications($blog, $newData)
    {
        // Obtén los valores antiguos y calcula las modificaciones
        $oldData = $this->getOldData($blog);
        $modifications = $this->calculateModifications($oldData, $newData);

        // Registra las modificaciones en la tabla modifications
        $modificationDetails = implode(". ", $modifications);
        $this->registerModification($blog->id, $modificationDetails);

        return $modifications;
    }
    private function getOldData($blog)
    {
        return [
            'num_boleta' => $blog->num_boleta,
            'proveedor' => $blog->proveedor,
            'motivo' => $blog->motivo,
            'estado' => $blog->estado,
            'caracteristicas' => $blog->waranty->caracteristicas,
            'observaciones' => $blog->waranty->observaciones,
            'monto' => $blog->waranty->monto,
            'unidad_ejecutora_id' => $blog->unidad_ejecutora_id,
            'fecha_inicio' => $blog->waranty->fecha_inicio instanceof \Carbon\Carbon ? $blog->waranty->fecha_inicio->format('Y-m-d') : $blog->waranty->fecha_inicio,
            'fecha_final' => $blog->waranty->fecha_final instanceof \Carbon\Carbon ? $blog->waranty->fecha_final->format('Y-m-d') : $blog->waranty->fecha_final,
            'boleta_pdf' => $blog->waranty->boleta_pdf,
            'nota_pdf' => $blog->waranty->nota_pdf,
        ];
    }

    private function calculateModifications($oldData, $newData)
    {
        $modifications = [];

        foreach ($newData as $field => $value) {
            if (array_key_exists($field, $oldData)) {
                $oldValue = $oldData[$field];

                if ($value !== $oldValue) {
                    $modifications[] = "$field modificado: $oldValue => $value";
                }
            }
        }

    return $modifications;
    }
    // Método para registrar la modificación en la base de datos
    private function registerModification($blogId, $modificationDetails)
    {
        // Verifica si tanto el blogId como los detalles de la modificación son válidos
        if ($blogId && $modificationDetails) {
            // Crear una nueva instancia del modelo Modification y asignar los valores
            $modification = new Modification([
                'blogs_id' => $blogId,
                'modification_details' => $modificationDetails,
                'modification_time' => now(),
                'usuario' => Auth::user()->name,
            ]);
    
            // Guardar la instancia en la base de datos
            $modification->save();
            
            // Puedes agregar un registro en el archivo de registro (log) para rastrear
            // cuándo se realiza una modificación, si lo deseas
            Log::info("Modificación registrada en el blog #$blogId: $modificationDetails");
        } else {
            // Si los datos no son válidos, puedes registrar un mensaje de advertencia
            // en el archivo de registro (log) o realizar alguna otra acción apropiada
            Log::warning("Intento de registro de modificación fallido. Datos inválidos.");
        }
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
                 // Agrega un mensaje para verificar si el archivo se eliminó
                Log::info("Archivo eliminado: $fullPath");
            }
        }
    }

   
}

    

