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
                        <form action="{{ route('suppliers.index') }}" method="GET">
                            <div class="input-group mt-2">
                                <input type="text" name="search" class="form-control" placeholder="Buscar" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <select name="order" class="form-control">
                                        <option value="">Ordenar por</option>
                                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Más antiguo primero</option>
                                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Más reciente primero</option>
                                    </select>
                                </div>
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-primary">Buscar</button>
                                </div>
                            </div>
                        </form>

                            @can('crear_tickets')
                            <a class="btn btn-warning" href="{{ route('suppliers.create')}}">Nuevo</a>
                            @endcan
                                <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display: none;">ID</th>
                                    <th style="display: #fff;">nombre</th>
                                    <th style="display: #fff;">descripcion</th>
                                    <th style="display: #fff;">Boletas Asociadas</th>
                                    <th style="display: #fff;">Acciones</th>

                                <tbody>
                                @foreach($afianzadoras as $afianzado)                               
                                <tr>
                                            <td style="display: none;">{{ $afianzado->id }}</td>
                                            <td>{{ $afianzado->nombre }}</td>
                                            <td>{{ $afianzado->descripcion }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-{{ $afianzado->id }}">
                                                   Ver Detalles
                                                </button>
                                             </td>
                                                                                                                         
                                            <td>
                                                @can('editar_tickets')
                                                    <a class="btn btn-info" href="{{ route('suppliers.edit', $afianzado->id) }}">Editar</a>
                                                @endcan
                                                @can('borrar_tickets')
                                                <form action="{{ route('suppliers.destroy', $afianzado->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta financiadora?')">Eliminar</button>
                                                </form>
                                                @endcan
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="modal-{{ $afianzado->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel-{{ $afianzado->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                               <div class="modal-content">
                                                  <div class="modal-header">
                                                     <h5 class="modal-title" id="modalLabel-{{ $afianzado->id }}">Detalles de la Afianzadora</h5>
                                                     <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                        <span aria-hidden="true">&times;</span>
                                                     </button>
                                                  </div>
                                                  <div class="modal-body">
                                                     <p>Nombre: {{ $afianzado->nombre }}</p>
                                                     <p>Descripción: {{ $afianzado->descripcion }}</p>
                                                     <h6>Boletas Asociadas:</h6>
                                                     <ul>
                                                        @foreach ($afianzado->blogs as $blog)
                                                           <li>
                                                              Número de Boleta: {{ $blog->num_boleta }}<br>
                                                              Ejecutora: {{ $blog->unidadEjecutora->nombre }}<br>
                                                              Monto: {{ $blog->waranty->monto }}<br>
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
