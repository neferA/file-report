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
                        <div>
                            <h2>Alarmas Rojas</h2>
                            <!-- Agregar un campo de búsqueda para las alarmas rojas -->
                            <div class="input-group mb-3">
                                <input type="hidden" name="orden" value="{{ request('orden') }}">
                                <input type="hidden" name="alarm_color" value="red">
                                <input type="text" id="searchRedAlarm" class="form-control" placeholder="Buscar en alarmas rojas" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" onclick="searchAlarm('red')">Buscar</button>
                                </div>
                            </div>
                            @foreach($alarms as $alarm)
                                @if($alarm['color'] === 'red')
                                    <div class="alert alert-{{ $alarm['color'] }}" style="background-color: {{ $alarm['color'] }}">
                                        <strong>Alarma Roja:</strong> Garantía a punto de expirar: {{ $alarm['warranty']->titulo }}
                                        <a href="{{ route('blogs.edit', ['blog' => $alarm['warranty']->blog->id]) }}" class="btn btn-primary">Ver Blog</a>
                                        <!-- Agrega un botón "X" para cerrar la alarma -->
                                        <button class="close" data-dismiss="alert" aria-label="Cerrar" onclick="closeAlarm(this)"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                @endif
                            @endforeach
                            {{$alarmsPaginator->links()}}                        
                        </div>
                        <div>
                            <h2>Alarmas Naranjas</h2>
                             <!-- Agregar un campo de búsqueda para las alarmas naranjas -->
                            <div class="input-group mb-3">
                                <input type="hidden" name="orden" value="{{ request('orden') }}">
                                <input type="hidden" name="alarm_color" value="orange">
                                <input type="text" id="searchOrangeAlarm" class="form-control" placeholder="Buscar en alarmas naranjas" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" onclick="searchAlarm('orange')">Buscar</button>
                                </div>
                            </div>
                            @foreach($alarms as $alarm)
                                @if($alarm['color'] === 'orange')
                                    <div class="alert alert-{{ $alarm['color'] }}" style="background-color: {{ $alarm['color'] }}">
                                        <strong>Alarma Naranja:</strong> Garantía a punto de expirar: {{ $alarm['warranty']->titulo }}
                                        <a href="{{ route('blogs.edit', ['blog' => $alarm['warranty']->blog->id]) }}" class="btn btn-primary">Ver Blog</a>
                                        <!-- Agrega un botón "X" para cerrar la alarma -->
                                        <button class="close" data-dismiss="alert" aria-label="Cerrar" onclick="closeAlarm(this)"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                @endif
                            @endforeach
                            {{$alarmsPaginator->links()}}
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
<script>
    function closeAlarm(button) {
        // Encuentra el elemento padre de la alarma (div.alert) y ocúltalo o elimínalo
        const alarm = button.closest('.alert');
        if (alarm) {
            alarm.style.display = 'none'; // Para ocultar la alarma
            // Opcionalmente, puedes eliminar la alarma: alarm.remove();
        }
    }
    function searchAlarm(color) {
        const searchTerm = document.querySelector(`#search${color.charAt(0).toUpperCase() + color.slice(1)}Alarm`).value.toLowerCase();
        const alarmContainers = document.querySelectorAll(`.alert-${color}`);

        alarmContainers.forEach((alarmContainer) => {
            const alarmText = alarmContainer.textContent.toLowerCase();
            if (alarmText.includes(searchTerm)) {
                alarmContainer.style.display = 'block';
            } else {
                alarmContainer.style.display = 'none';
            }
        });
    }
</script>
@stop
