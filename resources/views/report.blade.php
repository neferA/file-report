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
                <td>{{ $data['tipo_garantia'] }}</td>
                <td>{{ $data['monto'] }}</td>
                <td>{{ $data['unidad_ejecutora'] }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Sección para mostrar hijos y nietos -->
    @if(isset($data['renovated_blog_data']) && count($data['renovated_blog_data']) > 0)
        <h2>Blogs Renovados</h2>
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
                @foreach($data['renovated_blog_data'] as $renovatedBlog)
                    <tr>
                        <td>{{ $renovatedBlog['num_boleta'] }}</td>
                        <td>{{ $renovatedBlog['usuario'] }}</td>
                        <td>{{ $renovatedBlog['tipo_garantia'] }}</td>
                        <td>{{ $renovatedBlog['monto'] }}</td>
                        <td>{{ $renovatedBlog['unidad_ejecutora'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay blogs renovados.</p>
    @endif
    <!-- Muestra la suma de montos -->
    <p>Total Monto: {{ $data['total_monto'] }}</p>
</body>
</html>
