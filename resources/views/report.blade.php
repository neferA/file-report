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
    <h1>Informe de Boletas de Garantía</h1>
    <table>
        <thead>
            <tr>
                <th>Número de Boleta</th>
                <th>Usuario</th>
                <th>Tipo de Garantía</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $boleta)
                <tr>
                    <td>{{ $boleta->num_boleta }}</td>
                    <td>{{ $boleta->usuario }}</td>
                    <td>{{ $boleta->tipo_garantia_id }}</td>
                    <td>{{ $boleta->monto }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
