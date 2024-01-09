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
    <h2>Reporte de garanias seleccionadas</h2>

    <table>
        <thead>
            <tr>
                <!-- <th>ID</th> -->
                <th>Número de boleta</th>
                <th>Empresa/afianzado</th>
                <th>Motivo</th>
                <th>Afianzadora</th>
                <th>Tipo de Garantía</th>
                <th>Monto</th>
                <th>Unidad Ejecutora</th>
                <th>Caracteristicas</th>
                <th>Fecha de vencimiento</th>
                <th>Observaciones</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($selectedBlogs as $blog)
                <tr>
                    <!-- <td>{{ $blog->id }}</td> -->
                    <td>{{ $blog->num_boleta }}</td>
                    <td>{{ $blog->afianzado->nombre}}</td>    
                    <td>{{ $blog->motivo}}</td>    
                    <td>{{ $blog->financiadoras->pluck('nombre')->implode(', ')}}</td>    
                    <td>{{ $blog->tipoGarantia->nombre}}</td>
                    <td>{{ number_format($blog->waranty->monto, 2, ',', '.') }}</td>
                    <td>{{ $blog->unidadEjecutora->nombre }}</td>
                    <td>{{ $blog->waranty->caracteristicas }}</td>
                    <td>{{ $blog->waranty->fecha_final }}</td>
                    <td>{{ $blog->waranty->observaciones }}</td>
                </tr>
                
            @endforeach
        </tbody>
    </table>
    <div class="page-break"></div>
</body>
</html>
