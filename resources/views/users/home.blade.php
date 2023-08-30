@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
@stop

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Inicio</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h1> Página de Inicio </h1>
                        
                        <!-- Mostrar las alarmas -->
                        @foreach($alarms as $alarm)
                            <div class="alert alert-{{ $alarm['color'] }}" style="background-color: {{ $alarm['color'] === 'red' ? 'red' : 'orange' }}">
                                @if ($alarm['color'] === 'red')
                                    <strong>Alarma Roja:</strong> Garantía a punto de expirar: {{ $alarm['warranty']->titulo }}
                                @elseif ($alarm['color'] === 'orange')
                                    <strong>Alarma Naranja:</strong> Garantía a punto de expirar: {{ $alarm['warranty']->titulo }}
                                @endif
                                <a href="{{ route('blogs.edit', ['blog' => $alarm['warranty']->blog->id]) }}" class="btn btn-primary">Ver Blog</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    console.log('Hi!');
</script>
@stop
