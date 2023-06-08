@extends('adminlte::page')

@section('title', 'File Management')

@section('content_header')

@section('css')
<link rel="stylesheet" href="../../public/vendor/adminlte/dist/css/adminlte.min.css">
@stop

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Dashboard</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                        <div class="card bg-c-blue order-card">
                            <div class="card-block">
                                <h5>Usuarios</h5>
                                <h2 class="text-right"><i class="fa fa-users f-left"></i><span></span></h2>
                                <p class="m-b-0 text-right"><a href="/usuarios" class="text-white">Ver más</a></p>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

<!-- @section('css') -->
<!--     <link rel="stylesheet" href="/css/admin_custom.css"> -->
<!-- @stop -->

@section('js')
    <script> console.log('Hi!'); </script>
@stop
