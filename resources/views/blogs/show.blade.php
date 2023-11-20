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
            <div class="col-md-6">
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

            <div class="col-md-6">
                @if($renovatedBlogs->isNotEmpty())
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h2>Renovaciones</h2>
                        </div>
                        <div class="card-body">
                            @foreach($renovatedBlogs as $renovatedBlog)
                                <p><strong>ID Renovado:</strong> {{ $renovatedBlog->renewed_blog_id }}</p>
                                @php
                                    $blogRenovado = \App\Models\Blog::find($renovatedBlog->renewed_blog_id);
                                    $warantyHistory = \App\Models\Waranty::where('blogs_id', $renovatedBlog->renewed_blog_id)->first();
                                @endphp

                                @if($blogRenovado && $warantyHistory)
                                    <div class="card mt-3">
                                        <div class="card-header bg-info text-white">
                                            <h3>Información del Blog Renovado (ID: {{ $renovatedBlog->renewed_blog_id }})</h3>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Num Boleta:</strong> {{ $blogRenovado->num_boleta }}</p>
                                            <p><strong>Empresa:</strong> {{ $blogRenovado->empresa }}</p>
                                            <p><strong>Motivo:</strong> {{ $blogRenovado->motivo }}</p>                                            
                                            <details>
                                                <!-- <summary><h4>Historial de Garantía</h4></summary> -->
                                                <p><strong>Características:</strong> {{ $warantyHistory->caracteristicas }}</p>
                                                <p><strong>Observaciones:</strong> {{ $warantyHistory->observaciones }}</p>
                                                <p><strong>Monto:</strong> {{ $warantyHistory->monto }}</p>
                                                <p><strong>Fecha Inicial:</strong> {{ $warantyHistory->fecha_inicio }}</p>
                                                <p><strong>Fecha Final:</strong> {{ $warantyHistory->fecha_final }}</p>
                                            </details>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection