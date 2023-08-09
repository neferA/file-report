@extends('adminlte::page') {{-- O la plantilla que estés utilizando para las vistas --}}

@section('content')
<!-- Agregar el botón para redireccionar a la vista index de blogs -->


<section class="section">
        <div class="section-header">
            <h3 class="page__heading">Boleta- {{ $blog->num_boleta }}</h3>
            <h1 class="btn {{ $blog->estado_color }}" style="font-size: 24px;">{{ $blog->estado }}</h1>
        </div>
        </div>  
    </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    
                    <div class="card">
                        
                        <div class="card-body">
                            
                            <a class="btn btn-primary mt-3" href="{{ route('tickets.index') }}">Volver</a>

                                <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display: none;">ID</th>
                                    <th style="display: #fff;">ID</th>
                                    <th style="display: #fff;">numero de boleta</th>
                                    <th style="display: #fff;">motivo</th>
                                    <th style="display: #fff;">caracteristicas</th>
                                    <th style="display: #fff;">observaciones</th>
                                    <th style="display: #fff;">monto</th>
                                    <th style="display: #fff;">boleta</th>
                                    <th style="display: #fff;">nota</th>


                                    <th style="display: #fff;">fecha inicio</th>
                                    <th style="display: #fff;">fecha final</th>

                                    <th style="display: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                @foreach ($historial as $historia)
                                        <tr>
                                            <td style="display: none;">{{ $blog->id }}</td>
                                            <td>{{ $historia->id }}</td>
                                            <td>{{ $historia->titulo }}</td>    
                                            <td>{{ $historia->contenido }}</td>
                                            <td>{{ $historia->caracteristicas }}</td>    
                                            <td>{{ $historia->observaciones }}</td>
                                            <td>{{ $historia->monto }}</td>
                                            <td>
                                                @if ($historia->boleta_pdf)
                                                    <embed src="{{ Storage::url($historia->boleta_pdf) }}" type="application/pdf" width="100%" height="300px" />
                                                @else
                                                    <p>No hay archivo adjunto.</p>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($historia->nota_pdf)
                                                    <embed src="{{ Storage::url($historia->nota_pdf) }}" type="application/pdf" width="100%" height="300px" />
                                                @else
                                                    <p>No hay archivo adjunto.</p>
                                                @endif
                                            </td>  
                                            <td>{{ $historia->fecha_inicio }}</td>    
                                            <td>{{ $historia->fecha_final}}</td>    
                                         
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
                        </div>
                    </div>
                </div>
            </div>        
            <div class="pagination justify-content-end">
                {!! $historial->links() !!}
            </div>
        </div>
    </section>
@endsection