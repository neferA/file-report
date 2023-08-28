@extends('adminlte::page')

@section('title', 'Historial de Garantías')

@section('content')
    <section class="section">
        <div class="section-header">
        @if(isset($blog))
            <h3 class="page__heading">Boleta- {{ $blog->num_boleta }}</h3>
            <h1 class="btn {{ $blog->estado_color }}" style="font-size: 24px;">{{ $blog->estado }}</h1>
        @endif
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <a class="btn btn-primary mt-3" href="{{ route('tickets.index') }}">Volver</a>

                            <!-- Pestañas para organizar la información -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#historial-tab">Historial</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#historial-tab">detalles pdf</a>
                                </li>
                                <!-- Otras pestañas si es necesario -->
                            </ul>

                            <div class="tab-content mt-3">
                                <!-- Contenido de la pestaña Historial -->
                                <div id="historial-tab" class="tab-pane fade show active">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Número de Boleta</th>                                                
                                                <th>Financiadora</th>
                                                <th>Garantía</th>
                                                <th>Motivo</th>
                                                <th>Características</th>
                                                <th>Observaciones</th>
                                                <th>Monto</th>
                                                <th>Fecha Inicio</th>
                                                <th>Fecha Final</th>
                                                <th>pdf</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($historial as $historia)
                                                <t  r>
                                                    <td>{{ $historia->id }}</td>
                                                    <td>{{ $historia->titulo }}</td>
                                                    <td>
                                                        @foreach ($historia->blog->financiadoras as $financiadora)
                                                            {{ $financiadora->nombre }}
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $historia->blog->tipoGarantia->nombre }}</td>
                                                    <td>{{ $historia->blog->motivo }}</td>
                                                    <td>{{ $historia->caracteristicas }}</td>
                                                    <td>{{ $historia->observaciones }}</td>
                                                    <td>{{ $historia->monto }}</td>
                                                    <td>{{ $historia->fecha_inicio }}</td>
                                                    <td>{{ $historia->fecha_final }}</td>
                                                    <td>
                                                        <a class="btn btn-info" href="#" data-toggle="modal" data-target="#pdfModal{{ $historia->id }}">Ver boleta</a>
                                                        <a class="btn btn-info" href="#" data-toggle="modal" data-target="#notaPdfModal{{ $historia->id }}">Ver Nota</a>
                                                    </td>
                                                    <td>
                                                        @can('editar_tickets')
                                                            <a class="btn btn-info" href="{{ route('tickets.edit', $blog->id) }}">Editar</a>
                                                        @endcan
                                                        @can('borrar_tickets')
                                                            <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este blog?')">Eliminar</button>
                                                            </form>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="pagination justify-content-end">
                                        {!! $historial->links() !!}
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
                <!-- Modal para la previsualización de PDF de boleta -->
                <div class="modal fade" id="pdfModal{{ $historia->id }}" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel{{ $historia->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pdfModalLabel{{ $historia->id }}">Vista Previa de boleta PDF</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @if ($historia->boleta_pdf)
                                    <iframe src="{{ Storage::url($historia->boleta_pdf) }}" frameborder="0" width="100%" height="500px"></iframe>
                                @else
                                    <p>No hay archivo adjunto.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal para la previsualización de PDF de nota -->
                <div class="modal fade" id="notaPdfModal{{ $historia->id }}" tabindex="-1" role="dialog" aria-labelledby="notaPdfModalLabel{{ $historia->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="notaPdfModalLabel{{ $historia->id }}">Vista Previa de Nota PDF</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @if ($historia->nota_pdf)
                                    <iframe src="{{ Storage::url($historia->nota_pdf) }}" frameborder="0" width="100%" height="500px"></iframe>
                                @else
                                    <p>No hay archivo adjunto.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
    @endsection

   
    
    
    
    
    
   