@extends('adminlte::page')

@section('title', 'File report')

@section('content_header')
@stop

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Garantia</h3>
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
                                            <label for="afianzadora_id">afianzado</label>
                                            {!! Form::select('afianzadora_id', $afianzadoras, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un afianzado']) !!}
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
                                            <label for="titulo">caracteristicas</label>
                                            {!! Form::text('caracteristicas', null, ['class' => 'form-control', 'placeholder' => 'Ingrese las caracteristicas']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contenido">observaciones</label> 
                                            {!! Form::text('observaciones', null, ['class' => 'form-control', 'placeholder' => 'Ingrese las observaciones']) !!}
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="titulo">monto</label>
                                            {!! Form::text('monto', null, ['class' => 'form-control', 'placeholder' => 'Ingrese monto']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="estado">Estado</label>
                                            {!! Form::select('estado', [
                                                \App\Models\Blog::ESTADO_VIGENTE => 'Vigente', 
                                                // \App\Models\Blog::ESTADO_EJECUTADO => 'Ejecutado',
                                                // \App\Models\Blog::ESTADO_LIBERADO => 'Liberado',
                                                // \App\Models\Blog::ESTADO_RENOVADO => 'Renovado',
                                                // \App\Models\Blog::ESTADO_VENCIDO => 'Vencido',
                                            ], null, ['class' => 'form-control', 'placeholder' => 'Seleccione un estado']) !!}
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="unidad_ejecutora_id">Ejecutora</label>
                                            {!! Form::select('unidad_ejecutora_id', $ejecutoras, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una ejecutora']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="financiadora_id">Financiadora</label>
                                            {!! Form::select('financiadora_id', $financiadoras, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una financiadora']) !!}
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="garantia_id">tipo de garantía</label>
                                            {!! Form::select('tipo_garantia_id', $garantias, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una garantía']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="boleta_pdf">boleta pdf</label>
                                            {!! Form::file('boleta_pdf', ['class' => 'form-control-file', 'id' => 'boleta_pdf']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nota_pdf">nota pdf</label>
                                            {!! Form::file('nota_pdf', ['class' => 'form-control-file', 'id' => 'nota_pdf']) !!}
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_inicio">Fecha inicial</label>
                                            <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" placeholder="Ingrese la fecha inicial" required value="{{ now()->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_final">Fecha final</label>
                                            <input type="date" id="fecha_final" name="fecha_final" class="form-control" placeholder="Ingrese la fecha final" required value="{{ now()->format('Y-m-d') }}">
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

@section('scripts')

@endsection

