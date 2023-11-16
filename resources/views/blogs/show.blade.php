@extends('adminlte::page')

@section('title', 'File report')

@section('content_header')

@stop

@section('content')
<h2>Blog</h2>
<p>ID: {{ $blog->id }}</p>
<p>Estado: {{ $blog->estado }}</p>
{{-- Agrega más campos según sea necesario --}}
<p>Num Boleta: {{ $blog->num_boleta }}</p>
<p>Empresa: {{ $blog->empresa }}</p>
<p>Motivo: {{ $blog->motivo }}</p>

{{-- Mostrar información de renovaciones, si existen --}}
@if($renovatedBlogs->isNotEmpty())
    <h3>Renovaciones</h3>
    @foreach($renovatedBlogs as $renovatedBlog)
        <p>ID Renovado: {{ $renovatedBlog->renewed_blog_id }}</p>
        {{-- Agrega más campos según sea necesario --}}
    @endforeach
@endif

{{-- Mostrar información de waranty --}}
@if($blog->waranty)
    <h3>Waranty</h3>
    <p>ID Waranty: {{ $blog->waranty->id }}</p>
    <p>Título: {{ $blog->waranty->titulo }}</p>
    <p>Contenido: {{ $blog->waranty->contenido }}</p>
    <p>Contenido: {{ $blog->waranty->caracteristicas }}</p>

    {{-- Agrega más campos según sea necesario --}}
@endif

    @endsection
