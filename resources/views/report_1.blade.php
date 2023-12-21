<!DOCTYPE html>
<html>
<head>
    <title>Informe de Blogs Seleccionados</title>
    <style>
        /* Agrega estilos CSS según sea necesario para tu PDF */
        body {
            font-family: Arial, sans-serif;
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
    <h1>Informe de Blogs Seleccionados</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Número de Boleta</th>
                <th>Usuario</th>
                <!-- Agrega más encabezados según sea necesario -->
            </tr>
        </thead>
        <tbody>
            @foreach ($blogsData as $blog)
                <tr>
                    <td>{{ $blog['id'] }}</td>
                    <td>{{ $blog['num_boleta'] }}</td>
                    <td>{{ $blog['usuario'] }}</td>
                    <!-- Agrega más columnas según sea necesario -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
