@extends('adminlte::page')

@section('title', 'File report')

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalles del Blog</h1>

    <h2>Blog Original</h2>
    <p>ID: {{ $blog->id }}</p>
    <!-- Mostrar otros detalles del blog original según tu estructura de datos -->

    @if($renovatedBlog)
    <h2>Boleta Renovada</h2>
    <p>ID: {{ $renovatedBlog->id }}</p>
    <!-- Mostrar otros detalles de la boleta renovada según tu estructura de datos -->
    @else
    <p>No hay boleta renovada asociada a este blog.</p>
    @endif

    <!-- Agregar aquí más detalles del blog original y la boleta renovada según tu estructura de datos -->

</div>
@endsection
