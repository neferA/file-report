@extends('adminlte::page')

@section('title', 'File report')

@section('content_header')
@stop

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">boletas de garantia</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            @can('crear-blog')
                            <a class="btn btn-warning" href="{{ route('blogs.create')}}">Nuevo</a>
                            @endcan
                            <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display: none;">ID</th>
                                    <th style="display: #fff;">Titulo</th>
                                    <th style="display: #fff;">Contenido</th>
                                    <th style="display: #fff;">Fecha de Creación</th>
                                    <th style="display: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($blogs as $blog)
                                        <tr>
                                            <td style="display: none;">{{ $blog->id }}</td>
                                            <td>{{ $blog->titulo }}</td>
                                            <td>{{ $blog->contenido }}</td>
                                            <td>{{ $blog->created_at->format('d/m/Y H:i') }}</td></td>

                                            <td>
                                                @can('editar-blog')
                                                    <a class="btn btn-info" href="{{ route('blogs.edit', $blog->id) }}">Editar</a>
                                                @endcan
                                                @can('borrar-blog')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['blogs.destroy', $blog->id], 'style'=>'display:inline']) !!}
                                                        {!! Form::submit('Borrar', ['class' => 'btn btn-danger']) !!}
                                                    {!! Form::close() !!}
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination justify-content-end">
                                {!! $blogs->links() !!}
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
