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

                            {!! Form::model($blog, ['method' => 'PUT', 'route' => ['tickets.update', $blog->id], 'enctype' => 'multipart/form-data']) !!}
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="num_boleta">Número de boleta</label>
                                        {!! Form::text('num_boleta', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la boleta']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="num_boleta">proveedor</label>
                                        {!! Form::text('proveedor', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la boleta']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="num_boleta">motivo</label>
                                        {!! Form::text('motivo', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la boleta']) !!}
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="caracteristicas">Características</label>
                                        {!! Form::text('caracteristicas', $blog->waranty ? $blog->waranty->caracteristicas : null, ['class' => 'form-control', 'placeholder' => 'Ingrese las características']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="observaciones">Observaciones</label>
                                        {!! Form::text('observaciones', $blog->waranty ? $blog->waranty->observaciones : null, ['class' => 'form-control', 'placeholder' => 'Ingrese las observaciones']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="observaciones">monto</label>
                                        {!! Form::text('monto', $blog->waranty ? $blog->waranty->monto : null, ['class' => 'form-control', 'placeholder' => 'Ingrese el monto']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha_final">estado</label>
                                    <select name="estado" class="form-control">
                                        <option value="{{ App\Models\Blog::ESTADO_LIBERADO }}" {{ $blog->estado === App\Models\Blog::ESTADO_LIBERADO ? 'selected' : '' }}>Liberado</option>
                                        <option value="{{ App\Models\Blog::ESTADO_EJECUTADO }}" {{ $blog->estado === App\Models\Blog::ESTADO_EJECUTADO ? 'selected' : '' }}>Ejecutado</option>
                                        <option value="{{ App\Models\Blog::ESTADO_RENOVADO }}" {{ $blog->estado === App\Models\Blog::ESTADO_RENOVADO ? 'selected' : '' }}>Renovado</option>
                                        <option value="{{ App\Models\Blog::ESTADO_VENCIDO }}" {{ $blog->estado === App\Models\Blog::ESTADO_VENCIDO ? 'selected' : '' }}>vencido</option>
                                    </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="unidad_ejecutora_id">Ejecutora</label>
                                            {!! Form::select('unidad_ejecutora_id', $ejecutoras, $blog->unidadEjecutora->id, ['class' => 'form-control', 'placeholder' => 'Seleccione una ejecutora']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="financiadora_id">Financiadoras</label>
                                        {!! Form::select('financiadora_id[]', $financiadoras, $blog->financiadoras->pluck('id'), ['class' => 'form-control select2', 'multiple' => 'multiple']) !!}
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipo_garantia_id">Tipo de Garantía</label>
                                        {!! Form::select('tipo_garantia_id', $garantias, $blog->tipoGarantia->id, ['class' => 'form-control']) !!}
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="boleta_pdf">boleta pdf actual</label>
                                            @if($blog->waranty && $blog->waranty->boleta_pdf)
                                                <embed src="{{ Storage::url($blog->waranty->boleta_pdf) }}" type="application/pdf" width="100%" height="300px" />
                                            @else
                                                <p>No hay archivo adjunto.</p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="new_boleta_pdf">Subir nuevo boleta pdf</label>
                                            {!! Form::file('new_boleta_pdf', ['class' => 'form-control-file', 'id' => 'new_boleta_pdf']) !!}
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nota_pdf">nota pdf actual</label>
                                            @if($blog->waranty && $blog->waranty->nota_pdf)
                                                <embed src="{{ Storage::url($blog->waranty->nota_pdf) }}" type="application/pdf" width="100%" height="300px" />
                                            @else
                                                <p>No hay archivo adjunto.</p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="new_nota_pdf">Subir nuevo nota pdf</label>
                                            {!! Form::file('new_nota_pdf', ['class' => 'form-control-file', 'id' => 'new_nota_pdf']) !!}
                                        </div>
                                    </div>
                                 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_inicio">Fecha inicial</label>
                                            <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" placeholder="Ingrese la fecha inicial" 
                                                value="{{ old('fecha_inicio', $blog->waranty ? $blog->waranty->fecha_inicio : now()->format('Y-m-d')) }}" 
                                                min="{{ $blog->waranty ? $blog->waranty->fecha_inicio : now()->format('Y-m-d') }}" 
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_final">Fecha final</label>
                                            <input type="date" id="fecha_final" name="fecha_final" class="form-control" placeholder="Ingrese la fecha final" 
                                                value="{{ old('fecha_final', $blog->waranty ? $blog->waranty->fecha_final : now()->format('Y-m-d')) }}" 
                                                min="{{ $blog->waranty ? $blog->waranty->fecha_final : now()->format('Y-m-d') }}" 
                                                required>
                                        </div>
                                    </div>

                                <!-- Agrega el campo oculto con el valor de blogs_id -->
                                {!! Form::hidden('blogs_id', $blog->id) !!}
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

