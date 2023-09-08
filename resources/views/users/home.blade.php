@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
@stop

@section('content')
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Inicio</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h1> Página de Inicio </h1>
                        
                        <!-- Mostrar las alarmas -->
                        @foreach($alarms as $alarm)
                            <div class="alert alert-{{ $alarm['color'] }}" style="background-color: {{ $alarm['color'] }}">
                                <strong>
                                    @if ($alarm['color'] === 'red')
                                        Alarma Roja:
                                    @elseif ($alarm['color'] === 'orange')
                                        Alarma Naranja:
                                    @endif
                                </strong> Garantía a punto de expirar: {{ $alarm['warranty']->titulo }}
                                <a href="{{ route('blogs.edit', ['blog' => $alarm['warranty']->blog->id]) }}" class="btn btn-primary">Ver Blog</a>
                            <!-- Agrega un botón "X" para cerrar la alarma -->
                                <button class="close" data-dismiss="alert" aria-label="Cerrar" onclick="closeAlarm(this)"><span aria-hidden="true">&times;</span></button>
                            </div>
                        @endforeach

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
    function closeAlarm(button) {
        // Encuentra el elemento padre de la alarma (div.alert) y ocúltalo o elimínalo
        const alarm = button.closest('.alert');
        if (alarm) {
            alarm.style.display = 'none'; // Para ocultar la alarma
            // Opcionalmente, puedes eliminar la alarma: alarm.remove();
        }
    }
</script>
@stop
