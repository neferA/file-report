<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Boletas</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        h2 {
            page-break-after: avoid; /* Evita que el título se divida entre páginas */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: avoid;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .page-break {
            page-break-before: always; /* Forzar un salto de página antes de la tabla */
        }
    </style>
</head>
<body>
    <h2>Reporte de Boletas Seleccionados</h2>

    <table>
        <thead>
            <tr>
                <!-- <th>ID</th> -->
                <th>Número de Boleta</th>
                <th>Usuario</th>
                <th>empresa/afianzado</th>
                <th>motivo</th>
                <th>financiadora</th>
                <th>Tipo de Garantía</th>
                <th>Monto</th>
                <th>Unidad Ejecutora</th>
                <th >caracteristicas</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($selectedBlogs as $blog)
                <tr>
                    <!-- <td>{{ $blog->id }}</td> -->
                    <td>{{ $blog->num_boleta }}</td>
                    <td>{{ $blog->usuario }}</td>
                    <td>{{ $blog->afianzado->nombre}}</td>    
                    <td>{{ $blog->motivo}}</td>    
                    <td>{{ $blog->financiadoras->pluck('nombre')->implode(', ')}}</td>    
                    <td>{{ $blog->tipoGarantia->nombre}}</td>
                    <td>{{ number_format($blog->waranty->monto, 2, ',', '.') }}</td>
                    <td>{{ $blog->unidadEjecutora->nombre }}</td>
                    <td>{{ $blog->waranty->caracteristicas }}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p><strong>Fecha Inicio:</strong> {{ $blog->waranty->fecha_inicio }}</p>
                        <p><strong>Fecha Final:</strong> {{ $blog->waranty->fecha_final }}</p>
                    </td>
                    <td colspan="2">
                        <p><strong>Observaciones:</strong> {{ $blog->waranty->observaciones }}</p>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="page-break"></div>
</body>
</html>
