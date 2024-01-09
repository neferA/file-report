@extends('adminlte::page')

@section('title', 'File report')

@section('content_header')
@stop

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Ejecutoras</h3>
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

                            {!! Form::model($ejecutora, ['method' => 'PUT', 'route' => ['executor.update', $ejecutora->id]]) !!}
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="num_boleta">Nombre</label>
                                        {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="num_boleta">descripcion</label>
                                        {!! Form::text('descripcion', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la descripcion']) !!}
                                    </div>
                                </div>
                              
                            {!! Form::submit('Actualizar', ['class' => 'btn btn-primary']) !!}
                            <button class="btn btn-danger" onclick="window.history.back()">Cancelar</button>
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

