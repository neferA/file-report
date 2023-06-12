@extends('adminlte::page')

@section('title', 'File Management')

@section('content_header')

@stop

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Detalle de la Cuenta</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <section style="background-color: #eee;">
                            <div class="container py-5">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card mb-4">
                                            <div class="card-body text-center">
                                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                                                <h5 class="my-3">{{ $user->name }}</h5>
                                                <p class="text-muted mb-1">{{ $user-> email }}</p>
                                                @if ($role)
                                                    <p class="text-muted mb-4">{{ $role->name }}</p>
                                                @else
                                                    <p>No tiene un rol asignado. Por favor, contacte al administrador.</p>
                                                @endif
                                                <div class="d-flex justify-content-center mb-2">
                                                    <!-- <button type="button" class="btn btn-primary">Follow</button> -->
                                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-outline-primary ms-1"><i class="fa fa-power-off"></i> Cerrar sesión</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-8"> -->
                                    <!--     <div class="card mb-4"> -->
                                    <!--         <div class="card-body"> -->
                                    <!--             <div class="row"> -->
                                    <!--                 <div class="col-sm-3"> -->
                                    <!--                     <p class="mb-0">Full Name</p> -->
                                    <!--                 </div> -->
                                    <!--                 <div class="col-sm-9"> -->
                                    <!--                     <p class="text-muted mb-0">Johnatan Smith</p> -->
                                    <!--                 </div> -->
                                    <!--             </div> -->
                                    <!--             <hr> -->
                                    <!--             <div class="row"> -->
                                    <!--                 <div class="col-sm-3"> -->
                                    <!--                     <p class="mb-0">Email</p> -->
                                    <!--                 </div> -->
                                    <!--                 <div class="col-sm-9"> -->
                                    <!--                     <p class="text-muted mb-0">example@example.com</p> -->
                                    <!--                 </div> -->
                                    <!--             </div> -->
                                    <!--             <hr> -->
                                    <!--             <div class="row"> -->
                                    <!--                 <div class="col-sm-3"> -->
                                    <!--                     <p class="mb-0">Phone</p> -->
                                    <!--                 </div> -->
                                    <!--                 <div class="col-sm-9"> -->
                                    <!--                     <p class="text-muted mb-0">(097) 234-5678</p> -->
                                    <!--                 </div> -->
                                    <!--             </div> -->
                                    <!--             <hr> -->
                                    <!--             <div class="row"> -->
                                    <!--                 <div class="col-sm-3"> -->
                                    <!--                     <p class="mb-0">Mobile</p> -->
                                    <!--                 </div> -->
                                    <!--                 <div class="col-sm-9"> -->
                                    <!--                     <p class="text-muted mb-0">(098) 765-4321</p> -->
                                    <!--                 </div> -->
                                    <!--             </div> -->
                                    <!--             <hr> -->
                                    <!--             <div class="row"> -->
                                    <!--                 <div class="col-sm-3"> -->
                                    <!--                     <p class="mb-0">Address</p> -->
                                    <!--                 </div> -->
                                    <!--                 <div class="col-sm-9"> -->
                                    <!--                     <p class="text-muted mb-0">Bay Area, San Francisco, CA</p> -->
                                    <!--                 </div> -->
                                    <!--             </div> -->
                                    <!--         </div> -->
                                    <!--     </div> -->
                                    <!--     <div class="row"> -->
                                    <!--     </div> -->
                                    <!-- </div> -->
                                </div>
                            </div>
                        </section>
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
