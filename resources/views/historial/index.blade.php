@extends('adminlte::page') {{-- O la plantilla que estés utilizando para las vistas --}}

@section('content')
    <div class="container">
        {{-- Código para mostrar la tabla de historial aquí --}}
        <table class="table">
            <!-- Contenido de la tabla de historial -->
        </table>
        <div class="pagination justify-content-end">
            {!! $historial->links() !!}
        </div>
    </div>
@endsection