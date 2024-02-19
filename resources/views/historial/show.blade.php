@extends('adminlte::page')
@section('title', 'Historial de modificaciones')
@section('content')
<div class="container">
    <h1>Detalles de la boleta</h1>

    <div class="card">
        <div class="card-body">
            <h3>{{ $blog->titulo }}</h3>
            <!-- Mostrar otros detalles del blog según sea necesario -->
        </div>
    </div>

    <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver Atrás</a>

    <h2>Modificaciones</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Fecha y Hora</th>
                <th>Detalles de la Modificación</th>
                <th>Fecha y Hora Boletas</th>
                <th>Tiempo de Modificación</th>
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($modifications as $modification)
            <tr>
                <td>{{ $modification->created_at }}</td>
                <td>{{ $modification->modification_details }}</td>
                <td>{{ $blog->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $modification->modification_time }}</td>
                <td>{{ $modification->usuario }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if ($pdfModifications->count() > 0)
    @php
        // Agrupar por la fecha y hora exactas de creación
        $groupedModifications = $pdfModifications->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d H:i:s');
        });
    @endphp

    @foreach ($groupedModifications as $dateTime => $modifications)
        <div class="card mb-3">
            <div class="card-body">
                <p class="card-text">Fecha y Hora: {{ $dateTime }}</p>
                @foreach ($modifications as $pdfModification)
                    @if ($pdfModification->pdf_path)
                        @php
                            // Determinar el tipo desde la ruta del archivo
                            $tipo = Str::contains($pdfModification->pdf_path, 'boletas_pdfs') ? 'Boleta' : 'Nota';
                        @endphp

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Ver {{ $tipo }} PDF</h5>
                                <br>
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#pdfModal{{ $pdfModification->id }}">
                                    Ver PDF
                                </button>              
                                <div class="modal fade" id="pdfModal{{ $pdfModification->id }}" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel{{ $pdfModification->id }}" aria-hidden="true">
                                    <!-- Contenido del modal -->
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="pdfModalLabel{{ $pdfModification->id }}">Vista Previa de {{ $tipo }} PDF</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                {{-- <p>Ruta del archivo PDF: {{ storage_path("app/public/{$pdfModification->pdf_path}") }}</p> --}}
                                                <iframe src="{{ asset("storage/{$pdfModification->pdf_path}") }}" frameborder="0" width="100%" height="500px"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
@else
    <p>No hay modificaciones de PDF disponibles.</p>
@endif





</div>
@endsection
