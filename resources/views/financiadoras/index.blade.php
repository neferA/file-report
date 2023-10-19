@extends('adminlte::page')

@section('title', 'File report')

@section('content_header')

@stop
@section('content')
</div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    
                    <div class="card">
                        <div class="card-body">                        

                            @can('crear_tickets')
                            <a class="btn btn-warning" href="{{ route('financiers.create')}}">Nuevo</a>
                            @endcan

                                <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display: none;">ID</th>
                                    <th style="display: #fff;">nombre</th>
                                    <th style="display: #fff;">descripcion</th>
                                    <th style="display: #fff;">Boletas Asociadas</th>

                                    <th style="display: #fff;">Acciones</th>

                                <tbody>
                                @foreach ($financiadoras as $financiadora)
                                        <tr>
                                            <td style="display: none;">{{ $financiadora->id }}</td>
                                            <td>{{ $financiadora->nombre }}</td>
                                            <td>{{ $financiadora->descripcion }}</td>
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#modal-{{ $financiadora->id }}" class="btn btn-primary">Ver Detalles</a>
                                            </td>                               
                                            <td>
                                                @can('editar_tickets')
                                                    <a class="btn btn-info" href="{{ route('financiers.edit', $financiadora->id) }}">Editar</a>
                                                @endcan
                                                @can('borrar_tickets')
                                                <form action="{{ route('financiers.destroy', $financiadora->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta financiadora?')">Eliminar</button>
                                                </form>
                                                @endcan
                                            </td>
                                        </tr>
                                             <!-- Modal -->
                                        <div class="modal fade" id="modal-{{ $financiadora->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel-{{ $financiadora->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel-{{ $financiadora->id }}">Detalles de la Financiadora</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Nombre: {{ $financiadora->nombre }}</p>
                                                        <p>Descripción: {{ $financiadora->descripcion }}</p>
                                                        <h6>Blogs Asociados:</h6>
                                                        <ul>
                                                            @foreach ($financiadora->blogs as $blog)
                                                                <li>
                                                                    Número de Boleta: {{ $blog->num_boleta }}<br>
                                                                    Ejecutora: {{ $blog->unidadEjecutora->nombre }}<br>
                                                                    Estado: {{ $blog->estado }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>        
            
        </div>
   
@endsection
