<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe en PDF</title>
    <style>
        /* Estilos CSS para el PDF */
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            font-size: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Informe de Boleta de Garantía</h1>
    <table>
        <thead>
            <tr>
                <th>Número de Boleta</th>
                <th>Usuario</th>
                <th>Tipo de Garantía</th>
                <th>Monto</th>
                <th>Unidad Ejecutora</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $data['num_boleta'] }}</td>
                <td>{{ $data['usuario'] }}</td>
                <td>{{ $data['tipo_garantia'] }}</td> <!-- Asegúrate de que la clave sea correcta según cómo la pasas desde el controlador -->
                <td>{{ $data['monto'] }}</td> <!-- Accede al monto directamente desde el array de datos -->
                <td>{{ $data['unidad_ejecutora'] }}</td> <!-- Accede al nombre de la unidad ejecutora desde el array de datos -->
            </tr>
        </tbody>
    </table>
</body>


</html>