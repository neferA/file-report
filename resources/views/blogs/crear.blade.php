@extends('adminlte::page')

@section('title', 'File report')

@section('content_header')
@stop

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Boleta de garantia</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-dark alert-dismissible fade show" role="alert">
                                <strong>¡Revise los campos!</strong>
                                    @foreach ($errors->all() as $error)
                                        <span class="badge badge-danger">{{ $error }}</span>
                                    @endforeach
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            {!! Form::open(['route' => 'tickets.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                            <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="titulo">numero de boleta</label>
                                            {!! Form::text('num_boleta', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la boleta']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contenido">proveedor</label>
                                            {!! Form::text('proveedor', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el proveedor']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="titulo">motivo</label>
                                            {!! Form::text('motivo', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el motivo']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contenido">ejecutora</label>
                                            {!! Form::text('ejecutora', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la ejecutora']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="titulo">caracteristicas</label>
                                            {!! Form::text('caracteristicas', null, ['class' => 'form-control', 'placeholder' => 'Ingrese las caracteristicas']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contenido">observaciones</label>
                                            {!! Form::text('observaciones', null, ['class' => 'form-control', 'placeholder' => 'Ingrese las observaciones']) !!}
                                        </div>
                                    </div> <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="titulo">monto</label>
                                            {!! Form::text('monto', null, ['class' => 'form-control', 'placeholder' => 'Ingrese monto']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="estado">Estado</label>
                                        {!! Form::select('estado', [
                                            \App\Models\Blog::ESTADO_LIBERADO => 'Liberado',
                                            \App\Models\Blog::ESTADO_EJECUTADO => 'Ejecutado',
                                            \App\Models\Blog::ESTADO_RENOVADO => 'Renovado',
                                        ], null, ['class' => 'form-control', 'placeholder' => 'Seleccione un estado']) !!}
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contenido">boleta pdf</label>
                                            {!! Form::file('boleta_pdf', ['class' => 'form-control-file']) !!}
                                        </div>                                        
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contenido">nota pdf</label>
                                            {!! Form::file('nota_pdf', ['class' => 'form-control-file']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contenido">fecha inicial</label>
                                            {!! Form::text('fecha_inicio', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la fecha inicial']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contenido">fecha final</label>
                                            {!! Form::text('fecha_final', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la fecha final']) !!}
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                            <a class="btn btn-danger" href="{{ route('tickets.index') }}">Cancelar</a>
                                        </div>
                                    </div>
                                </div>

                            {!! Form::close() !!}
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
    <script> console.log('Hi!'); </script>
@stop
