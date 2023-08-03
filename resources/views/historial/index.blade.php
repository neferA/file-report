@extends('adminlte::page') {{-- O la plantilla que estés utilizando para las vistas --}}

@section('content')
<!-- Agregar el botón para redireccionar a la vista index de blogs -->

<a class="btn btn-primary mt-3" href="{{ route('tickets.index') }}">Volver</a>

    <div class="container">
        
        {{-- Código para mostrar la tabla de historial aquí --}}
        <div class="container">
        <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display: none;">ID</th>
                                    <th style="display: #fff;">ID</th>
                                    <th style="display: #fff;">titulo</th>
                                    <th style="display: #fff;">contenido</th>
                                    <th style="display: #fff;">caracteristicas</th>
                                    <th style="display: #fff;">observaciones</th>
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
        <div class="pagination justify-content-end">
            {!! $historial->links() !!}
        </div>
    </div>
@endsection