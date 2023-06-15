@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@stop

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Panel Administrador</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-xl-4">
                                    <div class="card bg-gradient-primary mb-3" style="max-width: 12rem;">
                                        <div class="card-header">Usuarios</div>
                                        @php
                                            use App\Models\User;
                                            $quant_user = User::count();
                                        @endphp
                                        <div class="card-body">
                                            <!-- <h5 class="card-title"><i class="fa fa-users f-right"><span>{{$quant_user}}</span></i></h5> -->
                                            <h5 class="card-title col-sm-3"><i class="fa fa-users f-right"><span class="col-sm-8">{{$quant_user}}</span></i></h5>
                                            <p class="card-text text-right"><a href="/usuarios" class="text-white">Ver más</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="card bg-gradient-success mb-3" style="max-width: 12rem;">
                                        <div class="card-header">Roles</div>
                                        @php
                                            use Spatie\Permission\Models\Role;
                                            $quant_roles = Role::count();
                                        @endphp
                                        <div class="card-body">
                                            <h5 class="card-title col-sm-3"><i class="fa fa-user-lock f-left"><span class="col-sm-8">{{$quant_roles}}</span></i></h5>
                                            <p class="card-text text-right"><a href="/roles" class="text-white">Ver más</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="card bg-gradient-danger mb-3" style="max-width: 12rem;">
                                        <div class="card-header">Blogs</div>
                                        @php
                                            use App\Models\Blog;
                                            $quant_blogs = Blog::count();
                                        @endphp
                                        <div class="card-body">
                                            <h5 class="card-title col-sm-3"><i class="fa fa-blog f-left"><span class="col-sm-8">{{$quant_blogs}}</span></i></h5>
                                            <p class="card-text text-right"><a href="/blogs" class="text-white">Ver más</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="card bg-gradient-danger mb-3 rounded" style="max-width: 12rem;">
                                        <div class="card-header">Blogs</div>
                                        <div class="card-body">
                                            <h5 class="card-title col-sm-3"><i class="fa fa-blog f-left"><span class="col-sm-8"></span></i></h5>
                                            <p class="card-text text-right"><a href="/blogs" class="text-white">Ver más</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
