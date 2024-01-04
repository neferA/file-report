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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px; /* Ajusta el padding para reducir el espacio en las celdas */
            font-size: 12px; /* Ajusta el tamaño de la letra */
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Reporte de Boletas Seleccionados</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Número de Boleta</th>
                <th>Usuario</th>
                <th>empresa/afianzado</th>
                <th>motivo</th>
                <th>financiadora</th>
                <th>Tipo de Garantía</th>
                <th>Monto</th>
                <th>Unidad Ejecutora</th>
                <th >caracteristicas</th>
                <th>Fecha Inicio</th>
                <th>Fecha Final</th>
                <th>observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($selectedBlogs as $blog)
                <tr>
                    <td>{{ $blog->id }}</td>
                    <td>{{ $blog->num_boleta }}</td>
                    <td>{{ $blog->usuario }}</td>
                    <td>{{ $blog->empresa}}</td>    
                    <td>{{ $blog->motivo}}</td>    
                    <td>{{ $blog->financiadoras->pluck('nombre')->implode(', ')}}</td>    
                    <td>{{ $blog->tipoGarantia->nombre}}</td>
                    <td>{{ $blog->waranty->monto }}</td>
                    <td>{{ $blog->unidadEjecutora->nombre }}</td>
                    <td>{{ $blog->waranty->caracteristicas }}</td>
                    <td>{{ $blog->waranty->fecha_inicio }}</td>
                    <td>{{ $blog->waranty->fecha_final }}</td>
                    <td>{{ $blog->waranty->observaciones }}</td>
                    {{-- <td>{{ $blog->afianzado->nombre }}</td> --}}
                    <!-- Agrega más celdas según tus necesidades -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
