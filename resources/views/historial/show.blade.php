
@extends('adminlte::page')
@section('content')
<div class="container">
    <h1>Detalles del Blog</h1>
    
    <div class="card">
        <div class="card-body">
            <h3>{{ $blog->titulo }}</h3>
            <!-- Mostrar otros detalles del blog según sea necesario -->
        </div>
    </div>

    <h2>Modificaciones</h2>
    
    <table class="table">
        <thead>
            <tr>
                <th>Fecha y Hora</th>
                <th>Detalles de la Modificación</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($modifications as $modification)
            <tr>
                <td>{{ $modification->created_at }}</td>
                <td>{{ $modification->modification_details }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Otras secciones o detalles que desees mostrar -->
</div>
@endsection
