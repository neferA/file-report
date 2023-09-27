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
                        
                        <div>
                            <h2>Alarmas Rojas</h2>
                            <!-- Agrupa elementos relacionados en un contenedor -->
                            <div class="filter-container">
                                <form id="redAlarmsFilterForm" method="get" action="{{ route('home') }}">
                                    <input type="hidden" name="alarm_color" value="red">
                                    <div class="input-group">
                                        <input type="text" class="form-control search-alarm" data-color="red" placeholder="Buscar en alarmas rojas" value="{{ request('search') }}">
                                        <select name="orden" id="orderRedAlarm" class="form-control">
                                            <option value="">Ordenar por</option>
                                            <option value="creacion_asc"{{ $orden === 'creacion_asc' ? ' selected' : '' }}>Más antiguos primero</option>
                                            <option value="creacion_desc"{{ $orden === 'creacion_desc' ? ' selected' : '' }}>Más recientes primero</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary search-button">Buscar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div>
                                @foreach($redAlarmsPaginator->items() as $alarm)
                                    @if($alarm['color'] === 'red')
                                        <div class="card alarm-card" style="border: 2px solid #FF0000; background-color: #FFEAEA;">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="card-title"><i class="fas fa-exclamation-circle" style="color: #FF0000;"></i> Alarma Roja</h5>
                                                    {{-- <span class="text-muted">{{ $alarm['timestamp'] }}</span> <!-- Agrega la hora y fecha de la alarma --> --}}
                                                </div>
                                                <p class="card-text"><strong>Garantía a punto de expirar:</strong> {{ $alarm['warranty']->titulo }}</p>
                                                <a href="{{ route('blogs.edit', ['blog' => $alarm['warranty']->blog->id]) }}" class="btn btn-primary">Ver Blog</a>
                                                <button class="btn btn-danger alarm-close-button" onclick="closeAlarm(this)" style="transition: opacity 0.3s;">Cerrar</button>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            {{ $redAlarmsPaginator->links() }}
                        </div>
                        
                        <div>
                            <h2>Alarmas Naranjas</h2>
                            <!-- Agrupa elementos relacionados en un contenedor -->
                            <div class="filter-container">
                                <form id="orangeAlarmsFilterForm" method="get" action="{{ route('home') }}">
                                    <input type="hidden" name="alarm_color" value="orange">
                                    <div class="input-group">
                                        <input type="text" class="form-control search-alarm" data-color="orange" placeholder="Buscar en alarmas naranjas" value="{{ request('search') }}">
                                        <select name="orden" id="orderOrangeAlarm" class="form-control">
                                            <option value="">Ordenar por</option>
                                            <option value="creacion_asc"{{ $orden === 'creacion_asc' ? ' selected' : '' }}>Más antiguos primero</option>
                                            <option value="creacion_desc"{{ $orden === 'creacion_desc' ? ' selected' : '' }}>Más recientes primero</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary search-button" onclick="searchAlarm('orange')">Buscar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div>
                                @foreach($orangeAlarmsPaginator->items() as $alarm)
                                    @if($alarm['color'] === 'orange')
                                        <div class="card alarm-card" style="border: 2px solid #FFA500; background-color: #FFF3E0;">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="card-title"><i class="fas fa-exclamation-circle" style="color: #FFA500;"></i> Alarma Naranja</h5>
                                                    {{-- <span class="text-muted">{{ $alarm['timestamp'] }}</span> <!-- Agrega la hora y fecha de la alarma --> --}}
                                                </div>
                                                <p class="card-text"><strong>Garantía a punto de expirar:</strong> {{ $alarm['warranty']->titulo }}</p>
                                                <a href="{{ route('blogs.edit', ['blog' => $alarm['warranty']->blog->id]) }}" class="btn btn-primary">Ver Blog</a>
                                                <button class="btn btn-danger alarm-close-button" onclick="closeAlarm(this)" style="transition: opacity 0.3s;">Cerrar</button>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            {{ $orangeAlarmsPaginator->links() }}
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
    function changeSorting(color, order) {
        const orderField = document.querySelector(`#order${color.charAt(0).toUpperCase() + color.slice(1)}Alarm`);
        if (orderField) {
            orderField.value = order;
            // Llamar a la función de búsqueda después de cambiar el orden
            searchAlarm(color);
        }
    }

    function searchAlarm(color) {
    const searchField = document.querySelector(`.search-alarm[data-color="${color}"]`);
    const searchTerm = searchField.value.toLowerCase();

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
