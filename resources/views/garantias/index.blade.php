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
                            <a class="btn btn-warning" href="{{ route('waranty.create')}}">Nuevo</a>
                            @endcan

                                <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display: none;">ID</th>
                                    <th style="display: #fff;">nombre</th>
                                    <th style="display: #fff;">descripcion</th>
                                    <th style="display: #fff;">Acciones</th>

                                <tbody>
                                @foreach ($tiposGarantias as $tipoGarantia)<tr>
                                            <td style="display: none;">{{ $tipoGarantia->id }}</td>
                                            <td>{{ $tipoGarantia->nombre }}</td>
                                            <td>{{ $tipoGarantia->descripcion }}</td>
                                                                             
                                            <td>
                                                @can('editar_tickets')
                                                    <a class="btn btn-info" href="{{ route('waranty.edit', $tipoGarantia->id) }}">Editar</a>
                                                @endcan
                                                @can('borrar_tickets')
                                                <form action="{{ route('waranty.destroy', $tipoGarantia->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta financiadora?')">Eliminar</button>
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
            
        </div>
   
@endsection
