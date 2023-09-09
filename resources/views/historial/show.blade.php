<<<<<<< HEAD

=======
>>>>>>> f409266bc330ef589ea1e53ca90c8b134563fa39
@extends('adminlte::page')
@section('content')
<div class="container">
    <h1>Detalles del Blog</h1>
<<<<<<< HEAD
    
=======


>>>>>>> f409266bc330ef589ea1e53ca90c8b134563fa39
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
<<<<<<< HEAD
                <th>Fecha y Hora</th>
                <th>Detalles de la Modificación</th>
=======
                <th>Fecha y Hora Boletas</th>
                <th>Detalles de la Modificación</th>
                <th>tiempo de Modificación</th>
                <th>usuario</th>

>>>>>>> f409266bc330ef589ea1e53ca90c8b134563fa39
            </tr>
        </thead>
        <tbody>
            @foreach ($modifications as $modification)
            <tr>
<<<<<<< HEAD
                <td>{{ $modification->created_at }}</td>
                <td>{{ $modification->modification_details }}</td>
=======
                <td>{{ $blog->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $modification->modification_details }}</td>
                <td>{{ $modification->modification_time }}</td>
                <td>{{ $modification->usuario }}</td>

>>>>>>> f409266bc330ef589ea1e53ca90c8b134563fa39
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Otras secciones o detalles que desees mostrar -->
</div>
@endsection
