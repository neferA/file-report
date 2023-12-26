<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Blogs</title>
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
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Reporte de Blogs Seleccionados</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Número de Boleta</th>
                <th>Afianzado</th>
                <th>Empresa</th>
                <th>Ejecutora</th>
                <th>monto</th>
                <th>Creado por</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($selectedBlogs as $blog)
                <tr>
                    <td>{{ $blog->id }}</td>
                    <td>{{ $blog->num_boleta }}</td>
                    <td>{{ $blog->afianzado->nombre }}</td>
                    <td>{{ $blog->empresa}}</td>    
                    <td>{{ $blog->unidadEjecutora->nombre }}</td>
                    <td>{{ $blog->waranty->monto }}</td>
                    <td>{{ $blog->usuario }}</td>
                    <!-- Agrega más celdas según tus necesidades -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
