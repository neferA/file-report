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
                                    <option value="vigente"{{ request('estado') === 'vigente' ? ' selected' : '' }}>vigente</option>
                                    <option value="liberado"{{ request('estado') === 'liberado' ? ' selected' : '' }}>Liberado</option>
                                    <option value="ejecutado"{{ request('estado') === 'ejecutado' ? ' selected' : '' }}>Ejecutado</option>
                                    <option value="renovado"{{ request('estado') === 'renovado' ? ' selected' : '' }}>Renovado</option>
                                    <option value="vencido"{{ request('estado') === 'vencido' ? ' selected' : '' }}>vencido</option>
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
                                    <option value="black"{{ request('alarma') === 'black' ? ' selected' : '' }}>negra</option>
                                    <!-- Agrega más opciones si es necesario -->
                                </select>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Buscar</button>
                                </div>
                            </div>
                        </form>
                         <!-- Formulario de filtro de fechas debajo del formulario de búsqueda -->
                         <form action="{{ route('tickets.index') }}" method="GET">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="start_date">Fecha Inicial:</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="end_date">Fecha Final:</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Filtrar por Fecha</button>
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
                                                    @if ($alarms[$blog->id]['color'] === 'red')
                                                        <div class="alert alert-danger text-center">
                                                            <i class="fas fa-exclamation-circle"></i> <strong>Alarma Roja</strong>
                                                        </div>
                                                    @elseif ($alarms[$blog->id]['color'] === 'orange')
                                                        <div class="alert alert-warning text-center">
                                                            <i class="fas fa-exclamation-triangle"></i> <strong>Alarma Naranja</strong>
                                                        </div>
                                                    @elseif ($alarms[$blog->id]['color'] === 'black')
                                                        <div class="alert alert-dark text-center text-white">
                                                            <i class="fas fa-exclamation-triangle"></i> <strong>Alarma Negra</strong>
                                                            <br>
                                                            <!-- Botón para abrir el modal de renovación -->
                                                            <button type="button" class="btn btn-warning mt-3" data-toggle="modal" data-target="#renovarModal">
                                                                renovar
                                                            </button>
                                                        </div>
                                                    @endif
                                                @else
                                                    <!-- Si no hay alarma, muestra un mensaje informativo -->
                                                    <div class="alert alert-info text-center">Sin alarma</div>
                                                @endif
                                            </td>
                                            <!-- Modal para la renovación -->
                                            <div class="modal fade" id="renovarModal" tabindex="-1" role="dialog" aria-labelledby="renovarModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="renovarModalLabel">Estado de Boleta</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Botones para los estados Liberado y Renovado -->
                                                            <div class="form-group">
<<<<<<< HEAD
                                                                <label for="estado">¿Qué desea hacer?</label>
                                                                <div class="btn-group" role="group" aria-label="Estado_liberado">
                                                                    <button type="button" class="btn btn-success" data-action="liberado" data-entryid="{{ $blog->id }}">Liberado</button>
                                                                </div>
                                                                <div class="btn-group" role="group" aria-label="Estado_renovado">
                                                                    <button type="button" class="btn btn-warning" data-action="renovado" data-entryid="{{ $blog->id }}">Renovado</button>
                                                                </div>
                                                                <div class="btn-group" role="group" aria-label="Estado_ejecutado">
                                                                    <button type="button" class="btn btn-warning" data-action="ejecutado" data-entryid="{{ $blog->id }}">ejecutado</button>
                                                                </div>
=======
                                                                <label for="estado"></label>
>>>>>>> origin/file_history
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            <button type="button" class="btn btn-warning" id="btnRenovado">Renovado</button>
                                                            <!-- Agrega el botón de enviar el formulario para la renovación -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Agregar mensajes de depuración --> 
                                            <!-- <td>Blog ID: {{ $blog->id }}</td>
                                            <td>Alarmas: {{ json_encode($alarms) }}</td>  -->
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
                                                    <a href="{{ route('blogs.generarpdf', ['id' => $blog->id]) }}" class="btn btn-success">
                                                        Generar PDF
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
