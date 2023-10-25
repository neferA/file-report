@extends('adminlte::page')

@section('title', 'File report')

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Renovar Blog</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('blogs.renovar', $blog->id) }}">
                        @csrf

                        <!-- Campos para la nueva boleta renovada -->
                        <div class="form-group">
                            <label for="nuevos_campos">Nuevos Campos:</label>
                            <input type="text" class="form-control" id="nuevos_campos" name="nuevos_campos" required>
                        </div>

                        <!-- Otros campos adicionales necesarios para la boleta renovada -->

                        <button type="submit" class="btn btn-primary">Renovar Blog</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
