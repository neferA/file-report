@extends('adminlte::page')

@section('title', 'File report')

@section('content_header')
    <!-- Header content goes here -->
@stop

@php
    use App\Models\Blog;
@endphp

@section('content')
    <div class="container">
        <div class="row">
            <!-- Blog Original -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2>Blog Original</h2>
                    </div>
                    <div class="card-body">
                        <p><strong>ID:</strong> {{ $blog->id }}</p>
                        <p><strong>Estado:</strong> {{ $blog->estado }}</p>
                        <p><strong>Num Boleta:</strong> {{ $blog->num_boleta }}</p>
                        <p><strong>Empresa:</strong> {{ $blog->empresa }}</p>
                        <p><strong>Motivo:</strong> {{ $blog->motivo }}</p>
                        <!-- Agrega más campos según sea necesario -->
                        @if($blog->waranty)
                            <p><strong>Características:</strong> {{ $blog->waranty->caracteristicas }}</p>
                            <p><strong>Observaciones:</strong> {{ $blog->waranty->observaciones }}</p>
                            <p><strong>Monto:</strong> {{ $blog->waranty->monto }}</p>
                            <p><strong>Fecha Inicial:</strong> {{ $blog->waranty->fecha_inicio }}</p>
                            <p><strong>Fecha Final:</strong> {{ $blog->waranty->fecha_final }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Renovaciones Descendentes y Ascendentes -->
            <div class="col-md-6 mb-3">
                <!-- Renovaciones Descendentes -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h2>Renovaciones Descendentes</h2>
                    </div>
                    <div class="card-body">
                        @if($renovatedBlogDataDescendant)
                            @foreach($renovatedBlogDataDescendant as $renovatedBlog)
                                <div class="mb-3">
                                    <button class="btn btn-link bg-primary text-white" data-toggle="collapse" data-target="#descendantDetails{{ $renovatedBlog['id'] }}">
                                        <strong>ID Renovado: {{ $renovatedBlog['id'] }}</strong>
                                    </button>
                                    <div id="descendantDetails{{ $renovatedBlog['id'] }}" class="collapse">
                                        <!-- Detalles adicionales ocultos por defecto -->
                                        <p><strong>Num Boleta:</strong> {{ $renovatedBlog['num_boleta'] }}</p>
                                        <p><strong>Estado:</strong> {{ $renovatedBlog['estado'] }}</p>
                                        <p><strong>Usuario:</strong> {{ $renovatedBlog['usuario'] }}</p>
                                        <p><strong>Tipo Garantía:</strong> {{ $renovatedBlog['tipo_garantia'] }}</p>
                                        <p><strong>Monto:</strong> {{ $renovatedBlog['monto'] }}</p>
                                        <p><strong>Unidad Ejecutora:</strong> {{ $renovatedBlog['unidad_ejecutora'] }}</p>
                                        <p><strong>Características:</strong> {{ $renovatedBlog['caracteristicas'] }}</p>
                                        <p><strong>Observaciones:</strong> {{ $renovatedBlog['observaciones'] }}</p>
                                        <p><strong>Fecha Inicio:</strong> {{ $renovatedBlog['fecha_inicio'] }}</p>
                                        <p><strong>Fecha Final:</strong> {{ $renovatedBlog['fecha_final'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                            <p><strong>Total Monto Descendente:</strong> {{ $totalMontoDescendant }}</p>
                        @else
                            <p>No hay renovaciones descendentes</p>
                        @endif
                    </div>
                </div>

                <!-- Renovaciones Ascendentes -->
                <div class="card mt-3">
                    <div class="card-header bg-info text-white">
                        <h2>Renovaciones Ascendentes</h2>
                    </div>
                    <div class="card-body">
                        @if($renovatedBlogDataAscendant)
                            @foreach($renovatedBlogDataAscendant as $renovatedBlog)
                                <div class="mb-3">
                                    <button class="btn btn-link bg-primary text-white" data-toggle="collapse" data-target="#ascendantDetails{{ $renovatedBlog['id'] }}">
                                        <strong>ID Renovado: {{ $renovatedBlog['id'] }}</strong>
                                    </button>
                                    <div id="ascendantDetails{{ $renovatedBlog['id'] }}" class="collapse">
                                        <!-- Detalles adicionales ocultos por defecto -->
                                        <p><strong>Num Boleta:</strong> {{ $renovatedBlog['num_boleta'] }}</p>
                                        <p><strong>Usuario:</strong> {{ $renovatedBlog['usuario'] }}</p>
                                        <p><strong>Estado:</strong> {{ $renovatedBlog['estado'] }}</p>
                                        <p><strong>Monto:</strong> {{ $renovatedBlog['monto'] }}</p>
                                        <p><strong>Unidad Ejecutora:</strong> {{ $renovatedBlog['unidad_ejecutora'] }}</p>
                                        <p><strong>Características:</strong> {{ $renovatedBlog['caracteristicas'] }}</p>
                                        <p><strong>Observaciones:</strong> {{ $renovatedBlog['observaciones'] }}</p>
                                        <p><strong>Fecha Inicio:</strong> {{ $renovatedBlog['fecha_inicio'] }}</p>
                                        <p><strong>Fecha Final:</strong> {{ $renovatedBlog['fecha_final'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                            <p><strong>Total Monto Ascendente:</strong> {{ $totalMontoAscendant }}</p>
                        @else
                            <p>No hay renovaciones ascendentes</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
