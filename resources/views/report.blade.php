<!-- resources/views/report.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Informe de Boletas de Garantía</title>
</head>
<body>
    <h1>Informe de Boletas de Garantía</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Número de Boleta</th>
                <!-- Otras columnas que desees mostrar -->
            </tr>
        </thead>
        <tbody>
            @foreach ($boletas as $boleta)
                <tr>
                    <td>{{ $boleta->id }}</td>
                    <td>{{ $boleta->num_boleta }}</td>
                    <!-- Otras celdas de datos -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
