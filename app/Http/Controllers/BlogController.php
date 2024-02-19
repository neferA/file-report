<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\ejecutora;
use App\Models\waranty;
use App\Models\TipoGarantia;
use App\Models\Financiadora;
use App\Models\Modification;
use App\Models\afianzadora;
use App\Models\RenewedBlog;
use App\Models\Entrega;
use App\Models\ModificationsPdf;

use App\Events\BlogUpdated;
use App\Events\WarrantyExpired;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;  
use Barryvdh\DomPDF\PDF as DomPDF;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BlogExport;

use App\Notifications\BlackWarrantyExpiredNotification;
use App\Notifications\RedWarrantyExpiredNotification;
use App\Notifications\OrangeWarrantyExpiredNotification;

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
    // Construye la consulta base
    $baseQuery = $this->buildQuery($request);
    
    // Obtén las fechas del formulario
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    
    // Clona la consulta base para aplicar el filtro de fechas
    $dateFilteredQuery = clone $baseQuery;
    
    // Aplica el filtro de fechas
    $this->applyDateFilter($dateFilteredQuery, $startDate, $endDate);
    
    // Ejecuta la consulta filtrada por fechas y obtén los resultados paginados
    $blogs = $dateFilteredQuery->orderBy('created_at', 'asc')->simplePaginate(20);
    
    // Itera a través de los blogs y maneja las alarmas
    $expiringBlogs = $this->getExpiringBlogs();
    foreach ($expiringBlogs as $blog) {
        $this->handleBlogAlarm($blog);
    }   
    // Obtén las alarmas para mostrar en la vista
    $alarms = $this->getAlarms();
    
    // Almacena los resultados filtrados en la sesión
    session(['filtered_blogs' => $blogs]);
    
    // Devuelve la vista con los datos necesarios
    return view('blogs.index', compact('blogs', 'alarms'));
}
       
    private function applyDateFilter($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            $query->whereHas('waranty', function ($subquery) use ($startDate, $endDate) {
                $subquery->whereBetween('fecha_final', [$startDate, $endDate]);
            });
        }
    }

    private function getExpiringBlogs() 
    {
        return waranty::whereDate('fecha_final', '>=', now())
            ->whereDate('fecha_final', '<=', now()->addDays(13)) // Cambiar a 13 días si es naranja
            ->get();
    }
    
    private function isBlackAlarm($fechaFinal)
    {
        $daysRemaining = now()->diffInDays($fechaFinal);
        return $daysRemaining === 0;
    }

    private function isRedAlarm($fechaFinal)
    {
        $daysRemaining = now()->diffInDays($fechaFinal);
        return ($daysRemaining <= 11 && $daysRemaining > 0) || round($daysRemaining) === 2;
    }
    
    private function isOrangeAlarm($fechaFinal)
    {
        $daysRemaining = now()->diffInDays($fechaFinal);
        $isRed = ($daysRemaining <= 11 && $daysRemaining > 0) || round($daysRemaining) === 2;
        return $daysRemaining <= 13 && $daysRemaining > 0 && !$isRed;
    }

    private function handleBlogAlarm($blog)
    {
        // Lógica para determinar si es una alarma roja, naranja y negra
        $isRedAlarm = $this->isRedAlarm($blog->fecha_final);
        $isOrangeAlarm = $this->isOrangeAlarm($blog->fecha_final);
        $isBlackAlarm = $this->isBlackAlarm($blog->fecha_final);
    
        // Si es una alarma negra, cambia el estado de los blogs con alarma negra a "vencido"
        if ($isBlackAlarm) {
            // Blog::where('id', $blog->id)
            //     ->update([
            //         'estado' => Blog::ESTADO_VENCIDO,
            //         'updated_at' => now(),  
            //     ]);
    
            // Crear una instancia de WarrantyExpired con los valores correctos
            $event = new WarrantyExpired($blog, $isRedAlarm, $isOrangeAlarm, $isBlackAlarm);
            event($event); // Disparar el evento
    
        //     // Enviar notificación de alarma negra
        //     $blog->user->notify(new BlackWarrantyExpiredNotification($blog));
        // } else {
        //     // Si no es una alarma negra, podrías realizar acciones adicionales según sea necesario
    
        //     // Enviar notificación de alarma roja
        //     if ($isRedAlarm) {
        //         $blog->user->notify(new RedWarrantyExpiredNotification($blog));
        //     }
    
        //     // Enviar notificación de alarma naranja
        //     if ($isOrangeAlarm) {
        //         $blog->user->notify(new OrangeWarrantyExpiredNotification($blog));
        //     }
        }
    }
    

    private function getAlarms()
    {
        $alarms = [];

        // Obtener los blogs que están a punto de expirar
        $expiringBlogs = Waranty::whereDate('fecha_final', '>=', now())
            ->whereDate('fecha_final', '<=', now()->addDays(13))
            ->get();

        foreach ($expiringBlogs as $blog) {
            // Lógica para determinar si es una alarma roja, naranja o negra
            $isRedAlarm = $this->isRedAlarm($blog->fecha_final);
            $isOrangeAlarm = $this->isOrangeAlarm($blog->fecha_final);
            $isBlackAlarm = $this->isBlackAlarm($blog->fecha_final, $blog);

            // Almacenar la alarma en el array asociativo usando el ID del blog como clave
            if ($isRedAlarm || $isOrangeAlarm || $isBlackAlarm) {
                $alarms[$blog->id] = [
                    'color' => $isRedAlarm ? 'red' : ($isOrangeAlarm ? 'orange' : 'black'),
                ];
            }
        }

        return $alarms;
    }
    
    private function buildQuery(Request $request)
    {
        $query = Blog::query();

        // Filtro por estado
        $estado = $request->input('estado');
        if ($estado) {
            $query->where('estado', $estado);
        }

        // Filtro de búsqueda
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('num_boleta', 'like', '%' . $search . '%')
                    ->orWhere('empresa', 'like', '%' . $search . '%')
                    ->orWhere('motivo', 'like', '%' . $search . '%')
                    ->orWhereHas('unidadEjecutora', function ($subq) use ($search) {
                        $subq->where('nombre', 'like', '%' . $search . '%');
                    });
            });
        }

       // Filtro por alarma
        $alarma = $request->input('alarma');
        $query->whereHas('waranty', function ($q) use ($alarma) {
            $q->where(function ($subq) use ($alarma) {
                if ($alarma === 'red') {
                    $subq->where('fecha_final', '<=', now()->addDays(11));
                } elseif ($alarma === 'orange') {
                    $subq->where('fecha_final', '>', now()->addDays(11))
                        ->where('fecha_final', '<=', now()->addDays(13));
                } elseif ($alarma === 'black') {
                    $subq->whereDate('fecha_final', '=', now());
                } elseif ($alarma === 'none') {
                    // Para blogs sin alarma, asegúrate de que no tengan una alarma configurada
                    $subq->where(function ($subsubq) {
                        $subsubq->where('fecha_final', '>', now()->addDays(13))
                                ->orWhere('fecha_final', '<', now());
                    });
                }
            });
        });

        // Ordenamiento
        $orden = $request->input('orden');
        $ordenColumna = 'created_at';
        $ordenDireccion = 'desc';

        if ($orden === 'creacion_asc') {
            $ordenDireccion = 'asc';
        } elseif ($orden === 'modificacion_desc') {
            $ordenColumna = 'updated_at';
            $ordenDireccion = 'desc';
        }

        // Aplicar el ordenamiento
        $query->orderBy($ordenColumna, $ordenDireccion);

        return $query;
    }
    private function uploadPDF($file, $folder)
    {
        if ($file)
        {
            $pdfPath = $file->store($folder, 'public');
            return $pdfPath;
        }
            return null;
    }
      
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $financiadoras = Financiadora::pluck('nombre', 'id');
        $garantias = TipoGarantia::pluck('nombre', 'id');
        $ejecutoras = ejecutora::pluck('nombre', 'id');
        $afianzadoras = afianzadora::pluck('nombre', 'id');
        
        return view('blogs.crear', compact('financiadoras','garantias','ejecutoras','afianzadoras'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $originalBlogId = null)
    {
        // Validaciones del formulario
        $request->validate([
            'num_boleta' => 'required',
            'tipo_garantia_id' => 'required',
            'unidad_ejecutora_id' => 'required',
            'afianzadora_id' => 'required',
            'motivo' => 'required',
            'caracteristicas' => 'required',
            'observaciones' => 'required',
            'monto' => 'required',
            'fecha_inicio' => 'required|date_format:Y-m-d',
            'fecha_final' => 'required|date_format:Y-m-d',
            'estado' => 'required|in:' . implode(',', [
                Blog::ESTADO_VIGENTE, 
            ]),
            'boleta_pdf' => 'nullable|mimes:pdf|max:10240', // Validación para el archivo PDF
            'nota_pdf' => 'nullable|mimes:pdf|max:10240',   // Validación para el archivo PDF
        ]);

        // Lógica para almacenar los datos del formulario en la tabla Blog
        $data = $request->except(['boleta_pdf', 'nota_pdf']);

        // Cargar y almacenar el archivo PDF 'boleta_pdf'
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

        // Obtener el nombre del usuario actualmente autenticado y guardarlo en el campo correspondiente
        $data['usuario'] = Auth::user()->name;
        
        // Si se proporciona el ID del blog original, realizar la renovación
        if ($originalBlogId !== null) {
            $this->renovarBlog($originalBlogId);
        } else {
            // Si no se proporciona el ID del blog original, crear un nuevo blog
            $blog = Blog::create($data);

            // Obtener los IDs de las financiadoras seleccionadas
            $financiadoraIds = $request->input('financiadora_id');

            // Adjuntar los IDs de las financiadoras relacionadas a la tabla pivote
            $blog->financiadoras()->attach($financiadoraIds);

            // Crear un nuevo registro en la tabla Waranty_Histories vinculado al registro de Blog recién creado
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

            // Asociar las afianzadoras al blog creado
            $afianzadora = afianzadora::find($request->input('afianzadora_id'));
            $blog->afianzado()->associate($afianzadora);
        }

        return redirect()->route('tickets.index');
    }

    private function renovarBlog($originalBlogId)
    {
        // Obtener el blog original que se va a renovar
        $originalBlog = Blog::find($originalBlogId);

        // Obtener el próximo ID disponible en la tabla blogs
        $nextId = DB::table('blogs')->max('id') + 1;

        // Insertar un nuevo registro en la tabla renewed_blogs
        $newRenewedBlog = RenewedBlog::create([
            'parent_blog_id' => $originalBlog->id,
            'renewed_blog_id' => $nextId,
            'original_blog_id' => $originalBlog->id,
        ]);
    
        // Actualizar la columna 'next_renewed_blog_id' en la tabla blogs con el próximo ID disponible
        DB::table('blogs')->where('id', $originalBlog->id)->update(['next_renewed_blog_id' => $nextId]);
               
        // Realizar la búsqueda recursiva y asignar el valor a 'original_blog_id'
        $originalBlogIdRecursivo = $this->realizarBusquedaRecursiva($newRenewedBlog);
        $newRenewedBlog->update(['original_blog_id' => $originalBlogIdRecursivo]);

        // Actualizar el estado del blog original a "Renovado"
        $originalBlog->estado = Blog::ESTADO_RENOVADO;
        $originalBlog->save();
        
        return $newRenewedBlog;
    }

    private function realizarBusquedaRecursiva($currentRenewedBlog)
    {
        // Guardar el ID original inicial
        $originalId = $currentRenewedBlog->original_blog_id;

        // Iterar hasta encontrar el abuelo original
        while ($currentRenewedBlog->parent_blog_id !== null) {
            // Obtener el siguiente blog renovado
            $currentRenewedBlog = RenewedBlog::where('renewed_blog_id', $currentRenewedBlog->parent_blog_id)->first();

            // Si no se encuentra el siguiente blog renovado, romper el bucle
            if (!$currentRenewedBlog) {
                break;
            }

            // Actualizar el ID original
            $originalId = $currentRenewedBlog->original_blog_id;
        }

        // Devolver el ID original encontrado durante la búsqueda recursiva
        return $originalId;
    }
    
    public function renovar($id)
    {
        //dd($id);
        $originalBlog = Blog::findOrFail($id);
        //dd($originalBlog);
        $financiadoras = Financiadora::pluck('nombre', 'id');
        $garantias = TipoGarantia::pluck('nombre', 'id');
        $ejecutoras = ejecutora::pluck('nombre', 'id');
        $afianzadoras = afianzadora::pluck('nombre', 'id');

        // Pasar el ID del blog original al método renovarBlog
        $blogRenovado = $this->renovarBlog($id);

        return view('blogs.crear', compact('financiadoras', 'garantias', 'ejecutoras', 'afianzadoras', 'blogRenovado'));
    }  
    public function cambiarEstadoForm($id, $estado)
    {
        // Lógica para mostrar el formulario con fecha de entrega y carga de PDF
        return view('blogs.cambiar_estado_form', compact('id', 'estado'));
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'fecha_entrega' => 'required|date',
            'pdf_file' => 'required|mimes:pdf|max:10240',
            'estado' => 'required',
        ]);

        // Obtener el blog por su ID
        $blog = Blog::findOrFail($id);

        // Crear una nueva entrega
        $entrega = new Entrega();
        $entrega->fecha_entrega = $request->input('fecha_entrega');

        // Procesar el archivo PDF y almacenarlo en la carpeta adecuada según el estado
        $estadoFolder = strtolower($request->input('estado'));
        $pdfPath = $request->file('pdf_file')->store("public/estados_pdfs/{$estadoFolder}");

        // Almacenar la ruta relativa en la base de datos (sin el prefijo "public/")
        $entrega->pdf_path = str_replace('public/', '', $pdfPath);

        // Asignar el estado proporcionado desde el formulario
        $entrega->estado = $request->input('estado');

        // Relacionar la entrega con el blog
        $blog->entregas()->save($entrega); // Cambiado a entregas()

        // Actualizar el estado del blog
        $blog->estado = $request->input('estado');
        $blog->save();
       
        return redirect()->route('tickets.index')->with('success', 'Estado cambiado exitosamente.');
    }
    

    /**
     * Display the specified resource.
     */
    
    public function generarPDF($id)
    {
        // Obtener el blog basado en el ID proporcionado
        $blog = Blog::with('waranty', 'unidadEjecutora', 'tipoGarantia', 'afianzado', 'financiadoras')->find($id);
    
        // Verificar si el blog existe
        if (!$blog) {
            abort(404); // Muestra una página 404 si el blog no se encuentra
        }
     
        // Obtener los valores necesarios del blog
        $fechaInicio = $blog->waranty->fecha_inicio;
        $fechaFinal = $blog->waranty->fecha_final;
        $empresa = $blog->empresa;
        $caracteristicas = $blog->waranty->caracteristicas;
        $observaciones = $blog->waranty->observaciones;
        $warantyMonto = $blog->waranty->monto;
        $unidadEjecutoraId = $blog->unidad_ejecutora_id;
        $unidadEjecutoraNombre = $blog->unidadEjecutora->nombre;
     
        // Obtener los IDs de los blogs renovados descendientes y sus datos
        $descendantRenovatedBlogData = $this->getRenovatedBlogData($blog, 'descendant')['renovatedBlogData'];
        $totalMontoDescendant = $this->getRenovatedBlogData($blog, 'descendant')['totalMonto'];
     
        // Obtener los IDs de los blogs renovados ascendientes y sus datos
        $ascendantRenovatedBlogData = $this->getRenovatedBlogData($blog, 'ascendant')['renovatedBlogData'];
        $totalMontoAscendant = $this->getRenovatedBlogData($blog, 'ascendant')['totalMonto'];
    
        // Calcular la suma de montos
        $totalMonto = $totalMontoDescendant + $totalMontoAscendant + $warantyMonto ;
    
        // Obtener los datos necesarios para el informe en PDF
        $data = [
            'num_boleta' => $blog->num_boleta,
            'usuario' => $blog->usuario,
            'afianzado' => $blog->afianzado->nombre,
            'motivo' => $blog->motivo,
            'estado' => $blog->estado,
            'tipo_garantia' => $blog->tipoGarantia->nombre,
            'financiadoras' => $blog->financiadoras->pluck('nombre')->implode(', '),
            'monto' => $warantyMonto,
            'unidad_ejecutora' => $unidadEjecutoraNombre,
            'descendant_renovated_blog_data' => $descendantRenovatedBlogData,
            'ascendant_renovated_blog_data' => $ascendantRenovatedBlogData,
            'caracteristicas' => $blog->waranty->caracteristicas,
            'observaciones' => $blog->waranty->observaciones,
            'fecha_inicio' => $blog->waranty->fecha_inicio,
            'fecha_final' => $blog->waranty->fecha_final,
            'total_monto' => $totalMonto, 
        ];
     
        // Generar el informe en PDF usando Laravel PDF
        $pdf = PDF::loadView('report', compact('data'))
                ->setPaper('letter', 'landscape');
 
     
        // Descargar el PDF
        return $pdf->download('informe.pdf');
    }
     

    // Función para obtener los IDs de los blogs renovados y sus datos (descendientes o ascendientes)
    private function getRenovatedBlogData($blog, $direction)
    {
        $renovatedBlogData = [];
        $processedBlogIds = []; // Conjunto de IDs de blogs procesados
        $totalMonto = 0; // Inicializar la suma total

        // Función recursiva para obtener datos de blogs renovados y la suma total
        $getRenovatedBlogDataRecursive = function ($currentBlogId, $direction) use (&$getRenovatedBlogDataRecursive, &$renovatedBlogData, &$processedBlogIds, &$totalMonto) {
            // Verificar si el blog ya ha sido procesado para la dirección actual
            if (in_array($currentBlogId, $processedBlogIds)) {
                return;
            }

            $currentBlog = Blog::with('waranty', 'unidadEjecutora', 'tipoGarantia', 'afianzado', 'financiadoras')->find($currentBlogId);

            // Agregar datos de blogs renovados
            $renovatedBlogData[] = [
                'id' => $currentBlog->id,
                'num_boleta' => $currentBlog->num_boleta,
                'usuario' => $currentBlog->usuario,
                'afianzado' => $currentBlog->afianzado->nombre,
                'motivo' => $currentBlog->motivo,
                'financiadoras' => $currentBlog->financiadoras->pluck('nombre')->implode(', '),
                'tipo_garantia' => $currentBlog->tipoGarantia->nombre,
                'monto' => $currentBlog->waranty->monto,
                'unidad_ejecutora' => $currentBlog->unidadEjecutora->nombre,
                'caracteristicas' => $currentBlog->waranty->caracteristicas,
                'observaciones' => $currentBlog->waranty->observaciones,
                'fecha_inicio' => $currentBlog->waranty->fecha_inicio,
                'fecha_final' => $currentBlog->waranty->fecha_final,
                'estado' => $currentBlog->estado,
            ];

            $totalMonto += $currentBlog->waranty->monto; // Sumar al total

            $processedBlogIds[] = $currentBlogId; // Agregar el blog actual al conjunto de blogs procesados

            $nextRenewedBlog = null;

            // Obtener el siguiente blog según la dirección
            if ($direction === 'descendant') {
                $nextRenewedBlog = RenewedBlog::where('parent_blog_id', $currentBlogId)->first();
            } elseif ($direction === 'ascendant') {
                $nextRenewedBlog = RenewedBlog::where('renewed_blog_id', $currentBlogId)->first();
            }

            if ($nextRenewedBlog) {
                $nextBlogId = $direction === 'descendant' ? $nextRenewedBlog->renewed_blog_id : $nextRenewedBlog->parent_blog_id;
                $getRenovatedBlogDataRecursive($nextBlogId, $direction);
            }
        };

        // Determinar la dirección y obtener el blog inicial
        $currentRenewedBlog = null;

        if ($direction === 'descendant') {
            $currentRenewedBlog = RenewedBlog::where('parent_blog_id', $blog->id)->first();
        } elseif ($direction === 'ascendant') {
            $currentRenewedBlog = RenewedBlog::where('renewed_blog_id', $blog->id)->first();
        }

        if ($currentRenewedBlog) {
            $nextBlogId = $direction === 'descendant' ? $currentRenewedBlog->renewed_blog_id : $currentRenewedBlog->parent_blog_id;
            $getRenovatedBlogDataRecursive($nextBlogId, $direction);
        }

        return ['renovatedBlogData' => $renovatedBlogData, 'totalMonto' => $totalMonto];
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
        $afianzadoras = afianzadora::pluck('nombre', 'id');// Obtener las ejecutoras para el campo select

        return view('blogs.editar', compact('blog','financiadoras','garantias','ejecutoras','afianzadoras'));
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
            'motivo' => 'required',
            'caracteristicas' => 'required',
            'monto' => 'required',
            'observaciones' => 'required',
            'fecha_inicio' => 'required',
            'fecha_final' => 'required',
            'estado' => 'required|in:' . implode(',', [
                Blog::ESTADO_VIGENTE,
                Blog::ESTADO_LIBERADO,
                Blog::ESTADO_EJECUTADO,
                Blog::ESTADO_RENOVADO,
                Blog::ESTADO_VENCIDO,
                Blog::ESTADO_ENTREGADO,
            ]),
            'new_boleta_pdf' => 'nullable|mimes:pdf|max:10240', // 10240 kilobytes = 10 megabytes
            'new_nota_pdf' => 'nullable|mimes:pdf|max:10240',   // 10240 kilobytes = 10 megabytes
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
                // Mover el archivo antiguo a una carpeta de historial preexistente
                $this->moveToHistory($oldBoletaPdf, 'historial_pdfs/boletas_pdfs', $blog->id, 'boleta');            }
            // Subir el nuevo archivo a la carpeta de boletas_pdfs
            $data['boleta_pdf'] = $this->uploadPDF($request->file('new_boleta_pdf'), 'boletas_pdfs', $blog->id);
        }

        // Verificar si se cargó un nuevo archivo new_nota_pdf
        if ($request->hasFile('new_nota_pdf')) {
            if ($oldNotaPdf) {
                // Mover el archivo antiguo a una carpeta de historial preexistente
                $this->moveToHistory($oldNotaPdf, 'historial_pdfs/notas_pdfs', $blog->id, 'nota');            }
            // Subir el nuevo archivo a la carpeta de notas_pdfs
            $data['nota_pdf'] = $this->uploadPDF($request->file('new_nota_pdf'), 'notas_pdfs', $blog->id);
        }

        // Resto de la lógica para actualizar campos en el registro

        // Actualizar el registro en la tabla blogs
        $blog->update($data);

        // Llama al nuevo método para registrar las modificaciones
        $modifications = $this->Modifications($blog, $request->all());

        // Dispara el evento BlogUpdated con los datos relevantes
        event(new BlogUpdated($blog, $request->all(), $modifications));

        // Actualizar la asociación de financiadoras
        $financiadoraIds = $request->input('financiadora_id');
        $blog->financiadoras()->sync($financiadoraIds);

        // Actualizar la asociación del tipo de garantía
        $tipoGarantia = TipoGarantia::find($request->input('tipo_garantia_id'));
        $blog->tipoGarantia()->associate($tipoGarantia);

        // Actualizar la ejecutora de la ejecutora
        $unidadEjecutora = ejecutora::find($request->input('unidad_ejecutora_id'));
        $blog->unidadEjecutora()->associate($unidadEjecutora);

        // Actualizar la ejecutora de la afianzadora
        $afianzadora = afianzadora::find($request->input('afianzadora_id'));
        $blog->afianzado()->associate($afianzadora);

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
    private function moveToHistory($filePath, $historyFolder, $blogId)
    {
        // Obtener el nombre del archivo sin la ruta
        $fileName = pathinfo($filePath, PATHINFO_BASENAME);

        // Construir la nueva ruta con la carpeta de historial y la marca de tiempo
        $newFilePath = $historyFolder . '/' . now()->format('YmdHis') . '_' . $fileName;

        // Mover el archivo a la carpeta de historial
        if (rename(storage_path("app/public/{$filePath}"), storage_path("app/public/{$newFilePath}"))) {
            // Registrar la información en la tabla modifications_pdf
            $modificationPdf = new ModificationsPdf();
            $modificationPdf->blogs_id = $blogId;
            $modificationPdf->pdf_path = $newFilePath;
            $modificationPdf->save();

            // Devolver la nueva ruta del archivo en el historial
            return $newFilePath;
        } else {
            // Devolver null o lanzar una excepción según sea necesario
            return null;
        }
    }


    public function show($id)
    {
        // Recupera el blog original por su ID
        $blog = Blog::findOrFail($id);
    
        // Obtén todas las renovaciones para el blog original y sus hijos (descendientes)
        $resultDescendant = $this->getRenovatedBlogData($blog, 'descendant');
        $renovatedBlogDataDescendant = $resultDescendant['renovatedBlogData'];
        $totalMontoDescendant = $resultDescendant['totalMonto'];
    
        // Obtén todas las renovaciones para el blog original y sus padres (ascendentes)
        $resultAscendant = $this->getRenovatedBlogData($blog, 'ascendant');
        $renovatedBlogDataAscendant = $resultAscendant['renovatedBlogData'];
        $totalMontoAscendant = $resultAscendant['totalMonto'];
    
        // Pasa los datos del blog original y las renovaciones a la vista
        return view('blogs.show', compact('blog', 'renovatedBlogDataDescendant', 'renovatedBlogDataAscendant', 'totalMontoDescendant', 'totalMontoAscendant'));
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
   
    public function Selecteditems(Request $request, DomPDF $dompdf)
    {
        // Recupera los resultados filtrados de la sesión
        $filteredBlogs = session('filtered_blogs');

        // Verifica si la casilla "select_all" está marcada
        $selectAll = $request->has('select_all');

        // Si "select_all" está marcada, selecciona todos los IDs desde los resultados filtrados o desde la base de datos
        $selectedBlogIds = $selectAll ? $this->getAllBlogIds($filteredBlogs) : $request->input('selected_blogs');
        //dd($selectedBlogIds);
        // Si hay resultados filtrados, aplica la selección solo a esos blogs
        $selectedBlogs = $filteredBlogs ? $filteredBlogs->whereIn('id', $selectedBlogIds) : $this->getSelectedBlogs($selectedBlogIds);

        // Recupera el valor de submit_action
        $submitAction = $request->input('submit_action');

        // Llama a métodos privados para realizar funciones específicas según la acción seleccionada
        switch ($submitAction) {
            case 'generar_pdf':
                // Configuración de Dompdf para hoja horizontal y tamaño carta
                $dompdf->setPaper('letter', 'landscape');
                // Genera el PDF con los datos seleccionados
                $pdf = $dompdf->loadView('inform', ['selectedBlogs' => $selectedBlogs]);

                // Define el nombre del archivo PDF
                $pdfFileName = 'reporte_select.pdf';

                // Devuelve una respuesta con el archivo PDF para descarga
                return response($pdf->output())
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="' . $pdfFileName . '"');

            case 'generar_excel':
                // dd($selectedBlogs->toArray());
                // Nombre del archivo Excel
                $excelFileName = 'reporte_select.xlsx';
                // Descarga el archivo Excel 
                return Excel::download(new BlogExport($selectedBlogs), $excelFileName);
                        
            case 'eliminar':
                $this->deleteSelectedBlogs($selectedBlogs);
                break;
            default:
                // Acción por defecto o manejo de otras acciones si es necesario
                break;
        }

        return redirect()->back()->with('success', 'Blogs seleccionados procesados correctamente.');
    }

    // Método para obtener todos los IDs de blogs desde la base de datos o desde los resultados filtrados
    private function getAllBlogIds($filteredBlogs = null)
    {
        // Verifica si "select_all" está marcada
        if (request()->has('select_all')) {
            // Si hay resultados filtrados y "select_all" está marcada, devuelve todos los IDs de los resultados filtrados
            if ($filteredBlogs && is_array($selectedBlogs = request()->input('selected_blogs'))) {
                //dd($selectedBlogs); // Agrega un dd para verificar los IDs seleccionados del formulario
                return $selectedBlogs;
            }

            // Si no hay resultados filtrados, y "select_all" está marcada, obtiene todos los IDs de la base de datos
            $allIds = Blog::pluck('id')->toArray();
            //dd($allIds); // Agrega un dd para verificar todos los IDs de la base de datos
            return $allIds;
        }

        // Si hay resultados filtrados, devuelve sus IDs
        if ($filteredBlogs) {
            $filteredIds = $filteredBlogs->pluck('id')->toArray();
            //dd($filteredIds); // Agrega un dd para verificar los IDs filtrados
            return $filteredIds;
        }

        // Si no hay "select_all" y no hay resultados filtrados, devuelve un array vacío
        return [];
    }
    // Dentro de tu controlador (por ejemplo, BlogController)
    public function getSelectedBlogs($blogIds)
    {
        return Blog::with('waranty', 'unidadEjecutora', 'tipoGarantia')
            ->whereIn('id', $blogIds)
            ->get();
    }


    // Método para eliminar blogs seleccionados
    private function deleteSelectedBlogs($selectedBlogIds)
    {
        // Verifica si $selectedBlogIds es una colección y la convierte a un array de IDs
        if ($selectedBlogIds instanceof \Illuminate\Database\Eloquent\Collection) {
            $selectedBlogIds = $selectedBlogIds->pluck('id')->toArray();
        }

        // Lógica para eliminar los blogs seleccionados
        // Utiliza los IDs de $selectedBlogIds según sea necesario

        // Elimina todos los blogs seleccionados en una sola consulta
        Blog::whereIn('id', $selectedBlogIds)->delete();
    }

    
}
   


    

