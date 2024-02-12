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

                        <form action="{{ route('blogs.cambiar_estado', ['id' => $id, 'estado' => $estado]) }}" method="post" enctype="multipart/form-data">                            @csrf
                            <input type="hidden" name="estado" value="{{ $estado }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha_entrega">Fecha de Entrega:</label>
                                        <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control">
                                    </div>
                                </div>
                                                               
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pdf_file">Subir PDF:</label>
                                        <input type="file" name="pdf_file" id="pdf_file" class="form-control">
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
