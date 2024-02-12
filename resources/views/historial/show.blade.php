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

    <!-- Mostrar las modificaciones de PDF -->
    @if ($pdfModifications->count() > 0)
        @foreach ($pdfModifications as $pdfModification)
        <div>
            <!-- Otras propiedades de $pdfModification -->
            <p>Ruta del archivo histórico de PDF: {{ storage_path("app/public/{$pdfModification->pdf_path}") }}</p>

            <!-- Botones para abrir los modales de PDF -->
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#pdfModal{{ $pdfModification->id }}">
                Ver Boleta PDF
            </button>

            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#notaPdfModal{{ $pdfModification->id }}">
                Ver Nota PDF
            </button>

            <!-- Modales para la previsualización de PDF -->
            <div class="modal fade" id="pdfModal{{ $pdfModification->id }}" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel{{ $pdfModification->id }}" aria-hidden="true">
                <!-- Contenido del modal -->
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="pdfModalLabel{{ $pdfModification->id }}">Vista Previa de boleta PDF</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if ($pdfModification->pdf_path)
                                <iframe src="{{ Storage::url($pdfModification->pdf_path) }}" frameborder="0" width="100%" height="500px"></iframe>
                            @else
                                <p>No hay archivo adjunto.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="notaPdfModal{{ $pdfModification->id }}" tabindex="-1" role="dialog" aria-labelledby="notaPdfModalLabel{{ $pdfModification->id }}" aria-hidden="true">
                <!-- Contenido del modal -->
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="notaPdfModalLabel{{ $pdfModification->id }}">Vista Previa de Nota PDF</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if ($pdfModification->pdf_path)
                                <iframe src="{{ Storage::url($pdfModification->pdf_path) }}" frameborder="0" width="100%" height="500px"></iframe>
                            @else
                                <p>No hay archivo adjunto.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <p>No hay modificaciones de PDF disponibles.</p>
    @endif

</div>
@endsection
