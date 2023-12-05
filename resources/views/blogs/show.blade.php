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
            <!-- Muestra las renovaciones -->
            <div class="col-md-6">
                <div class="card mt-3">
                    <div class="card-header bg-success text-white">
                        <h2>Renovaciones</h2>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="renovatedBlogsAccordion">
                            @if($renovatedBlogs->isNotEmpty())
                                @foreach($renovatedBlogs as $renovatedBlog)
                                    <div class="card mt-3">
                                        <div class="card-header bg-info" id="renovatedBlogHeading{{ $renovatedBlog->renewed_blog_id }}">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link text-white" type="button" data-toggle="collapse" data-target="#renovatedBlogCollapse{{ $renovatedBlog->renewed_blog_id }}" aria-expanded="true" aria-controls="renovatedBlogCollapse{{ $renovatedBlog->renewed_blog_id }}">
                                                    <strong>Blog Renovado (ID: {{ $renovatedBlog->renewed_blog_id }})</strong>
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="renovatedBlogCollapse{{ $renovatedBlog->renewed_blog_id }}" class="collapse" aria-labelledby="renovatedBlogHeading{{ $renovatedBlog->renewed_blog_id }}" data-parent="#renovatedBlogsAccordion">
                                            <div class="card-body">
                                                <p><strong>ID Renovado:</strong> {{ $renovatedBlog->renewed_blog_id }}</p>
                                                <!-- Otros campos del blog renovado -->
                                                <!-- Recuperar el blog renovado y su garantía -->
                                                @php
                                                    $blogRenovado = \App\Models\Blog::find($renovatedBlog->renewed_blog_id);
                                                    $warantyHistory = \App\Models\Waranty::where('blogs_id', $renovatedBlog->renewed_blog_id)->first();
                                                @endphp
                                                <!-- Mostrar otros campos del blog renovado -->
                                                @if($blogRenovado)
                                                    <p><strong>Estado:</strong> {{ $blogRenovado->estado }}</p>
                                                    <p><strong>Num Boleta:</strong> {{ $blogRenovado->num_boleta }}</p>
                                                    <p><strong>Empresa:</strong> {{ $blogRenovado->empresa }}</p>
                                                    <p><strong>Motivo:</strong> {{ $blogRenovado->motivo }}</p>
                                                @endif

                                                <!-- También mostrar detalles de garantía si está presente -->
                                                @if($warantyHistory)
                                                    <p><strong>Características:</strong> {{ $warantyHistory->caracteristicas }}</p>
                                                    <p><strong>Observaciones:</strong> {{ $warantyHistory->observaciones }}</p>
                                                    <p><strong>Monto:</strong> {{ $warantyHistory->monto }}</p>
                                                    <p><strong>Fecha Inicial:</strong> {{ $warantyHistory->fecha_inicio }}</p>
                                                    <p><strong>Fecha Final:</strong> {{ $warantyHistory->fecha_final }}</p>
                                                @endif
                                                <!-- Comprueba si hay hijos antes de intentar iterar -->
                                                @if($renovatedBlog->hijos && $renovatedBlog->hijos->isNotEmpty())
                                                    <!-- Muestra los hijos de cada renovación -->
                                                    @foreach($renovatedBlog->hijos as $hijo)
                                                        <div class="card mt-3">
                                                            <div class="card-header bg-warning" id="hijoHeading{{ $hijo->renewed_blog_id }}">
                                                                <h2 class="mb-0">
                                                                    <button class="btn btn-link text-white" type="button" data-toggle="collapse" data-target="#hijoCollapse{{ $hijo->renewed_blog_id }}" aria-expanded="true" aria-controls="hijoCollapse{{ $hijo->renewed_blog_id }}">
                                                                        <strong>Blog Hijo (ID: {{ $hijo->renewed_blog_id }})</strong>
                                                                    </button>
                                                                </h2>
                                                            </div>
                                                            <div id="hijoCollapse{{ $hijo->renewed_blog_id }}" class="collapse" aria-labelledby="hijoHeading{{ $hijo->renewed_blog_id }}" data-parent="#renovatedBlogCollapse{{ $renovatedBlog->renewed_blog_id }}">
                                                                <div class="card-body">
                                                                    <p><strong>ID Renovado:</strong> {{ $hijo->renewed_blog_id }}</p>
                                                                    
                                                                    <!-- Comprueba si hay nietos antes de intentar iterar -->
                                                                    @if($hijo->hijos && $hijo->hijos->isNotEmpty())
                                                                        <!-- Muestra los nietos de cada hijo -->
                                                                        @foreach($hijo->hijos as $nieto)
                                                                            <div class="card mt-3">
                                                                                <div class="card-header bg-danger" id="nietoHeading{{ $nieto->renewed_blog_id }}">
                                                                                    <h2 class="mb-0">
                                                                                        <button class="btn btn-link text-white" type="button" data-toggle="collapse" data-target="#nietoCollapse{{ $nieto->renewed_blog_id }}" aria-expanded="true" aria-controls="nietoCollapse{{ $nieto->renewed_blog_id }}">
                                                                                            <strong>Blog Nieto (ID: {{ $nieto->renewed_blog_id }})</strong>
                                                                                        </button>
                                                                                    </h2>
                                                                                </div>
                                                                                <div id="nietoCollapse{{ $nieto->renewed_blog_id }}" class="collapse" aria-labelledby="nietoHeading{{ $nieto->renewed_blog_id }}" data-parent="#hijoCollapse{{ $hijo->renewed_blog_id }}">
                                                                                    <div class="card-body">
                                                                                        <p><strong>ID Renovado:</strong> {{ $nieto->renewed_blog_id }}</p>
                                                                                        <!-- Otros campos del blog nieto -->
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection