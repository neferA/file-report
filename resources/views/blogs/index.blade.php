@extends('adminlte::page')

@section('title', 'File report')

@section('content_header')

@stop

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Boletas de Garantía</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                        <form action="{{ route('tickets.index') }}" method="GET">
                            <div class="input-group mb-3">
                                <input type="text" name="search" class="form-control" placeholder="Buscar" value="{{ request('search') }}">
                                <select name="estado" class="form-control">
                                    <option value="">Seleccionar estado</option>
                                    <option value="liberado"{{ request('estado') === 'liberado' ? ' selected' : '' }}>Liberado</option>
                                    <option value="ejecutado"{{ request('estado') === 'ejecutado' ? ' selected' : '' }}>Ejecutado</option>
                                    <option value="renovado"{{ request('estado') === 'renovado' ? ' selected' : '' }}>Renovado</option>
                                </select>
                                <select name="orden" class="form-control">
                                    <option value="">Ordenar por</option>
                                    <option value="creacion_asc"{{ request('orden') === 'creacion_asc' ? ' selected' : '' }}>Más antiguos primero</option>
                                    <option value="creacion_desc"{{ request('orden') === 'creacion_desc' ? ' selected' : '' }}>Más recientes primero</option>
                                    <option value="modificacion_desc"{{ request('orden') === 'modificacion_desc' ? ' selected' : '' }}>Modificados recientemente</option>
                                </select>                   
                                <select name="alarma" class="form-control">
                                    <option value="">Seleccionar Alarma</option>
                                    <option value="red"{{ request('alarma') === 'red' ? ' selected' : '' }}>Roja</option>
                                    <option value="orange"{{ request('alarma') === 'orange' ? ' selected' : '' }}>Naranja</option>
                                    <!-- Agrega más opciones si es necesario -->
                                </select>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Buscar</button>
                                </div>
                            </div>
                        </form>

                        @can('crear_tickets')
                            <a class="btn btn-warning" href="{{ route('tickets.create')}}">
                                <i class="fas fa-plus"></i> Nueva boleta
                            </a>
                        @endcan


                            <!-- Pestañas para organizar la información -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#historial-tab">Historial</a>
                                </li>
                                <!-- Otras pestañas si es necesario -->
                            </ul>                 
                        <div class="table-responsive">
                                                    
                            <table class="table table-striped mt-2">
                                <thead>
                                    <tr>
                                        <th style="display: none;">ID</th>
                                        <th style="display: #fff;">Número de Boleta</th>
                                        <th style="display: #fff;">Proveedor</th>
                                        <th style="display: #fff;">Motivo</th>
                                        <th style="display: #fff;">Ejecutora</th>
                                        <th style="display: #fff;">Creado por</th>
                                        <th style="display: #fff;">Estado</th>
                                        <th style="display: #fff;">Fecha de Creación</th>
                                        <th style="display: #fff;">Alarma</th> 
                                        <th style="display: #fff;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($blogs as $blog)
                                        <tr>
                                            <td style="display: none;">{{ $blog->id }}</td>
                                            <td>{{ $blog->num_boleta }}</td>
                                            <td>{{ $blog->proveedor }}</td>    
                                            <td>{{ $blog->motivo }}</td>    
                                            <td>{{ $blog->unidadEjecutora->nombre }}</td>
                                            <td>{{ $blog->usuario }}</td>
                                            <td>
                                                <h1 class="badge {{ $blog->estado_color }}" style="font-size: 14px;">{{ $blog->estado }}</h1>
                                            </td>                                                                                           
                                            <td>{{ $blog->created_at->format('d/m/Y H:i') }}</td>

                                            <td>
                                                @if(isset($alarms[$blog->id]))
                                                    <div class="alert alert-{{ $alarms[$blog->id]['color'] }} text-center" style="background-color: {{ $alarms[$blog->id]['color'] }}">
                                                        @if ($alarms[$blog->id]['color'] === 'red')
                                                            <i class="fas fa-exclamation-circle"></i> <strong>Alarma Roja</strong>
                                                        @else
                                                            <i class="fas fa-exclamation-triangle"></i> <strong>Alarma Naranja</strong>
                                                        @endif
                                                    </div>
                                                @else
                                                    <!-- Si no hay alarma, muestra un mensaje informativo -->
                                                    <div class="alert alert-info text-center">Sin alarma</div>
                                                @endif
                                            </td>
                                            <!-- Agregar mensajes de depuración -->
                                            <!-- <td>Blog ID: {{ $blog->id }}</td>
                                            <td>Alarmas: {{ json_encode($alarms) }}</td> -->
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Acciones">
                                                    <a class="btn btn-primary" href="{{ route('historial.index', $blog->id) }}">
                                                        <i class="fas fa-history"></i> Ver Historial
                                                    </a>
                                                    
                                                    @can('editar_tickets')
                                                        <a class="btn btn-info" href="{{ route('tickets.edit', $blog->id) }}">
                                                            <i class="fas fa-edit"></i> Editar
                                                        </a>
                                                    @endcan
                                                    
                                                    @can('borrar_tickets')
                                                        <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este blog?')">
                                                                <i class="fas fa-trash"></i> Eliminar
                                                            </button>
                                                        </form>
                                                    @endcan

                                                    <!-- Botón para generar el informe con ícono PDF -->
                                                    <a href="{{ route('generate-pdf', [
                                                        'fecha_inicio' => request('fecha_inicio'),
                                                        'fecha_final' => request('fecha_final'),
                                                        'unidad_ejecutora_id' => request('unidad_ejecutora_id'),
                                                    ]) }}" class="btn btn-success">
                                                        <i class="fas fa-file-pdf"></i> Generar PDF
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                            <div class="pagination justify-content-end">
                                {!! $blogs->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
