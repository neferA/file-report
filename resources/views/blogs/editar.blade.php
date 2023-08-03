@extends('adminlte::page')

@section('title', 'File report')

@section('content_header')
@stop

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">boleta de garantia</h3>
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

                            {!! Form::model($blog, ['method' => 'PUT', 'route' => ['tickets.update', $blog->id]]) !!}                            @csrf
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
                                            <label for="contenido">usuario</label>
                                            {!! Form::text('usuario', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el usuario']) !!}
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
                                            <label for="titulo">fecha inicial</label>
                                            {!! Form::text('fecha_inicio', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la fecha inicial']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contenido">fecha final</label>
                                            {!! Form::text('fecha_final', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la fecha final']) !!}
                                        </div>
                                    </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                    <a class="btn btn-danger" href="{{ route('tickets.index') }}">Cancelar</a>
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

