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
@if(isset($data['descendant_renovated_blog_data']) && count($data['descendant_renovated_blog_data']) > 0)
    <h2>Blogs Renovados Descendientes</h2>
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
            @foreach($data['descendant_renovated_blog_data'] as $descendantRenovatedBlog)
                <tr>
                    <td>{{ $descendantRenovatedBlog['num_boleta'] }}</td>
                    <td>{{ $descendantRenovatedBlog['usuario'] }}</td>
                    <td>{{ $descendantRenovatedBlog['tipo_garantia'] }}</td>
                    <td>{{ $descendantRenovatedBlog['monto'] }}</td>
                    <td>{{ $descendantRenovatedBlog['unidad_ejecutora'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No hay blogs renovados descendientes.</p>
@endif

@if(isset($data['ascendant_renovated_blog_data']) && count($data['ascendant_renovated_blog_data']) > 0)
    <h2>Blogs Renovados Ascendientes</h2>
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
            @foreach($data['ascendant_renovated_blog_data'] as $ascendantRenovatedBlog)
                <tr>
                    <td>{{ $ascendantRenovatedBlog['num_boleta'] }}</td>
                    <td>{{ $ascendantRenovatedBlog['usuario'] }}</td>
                    <td>{{ $ascendantRenovatedBlog['tipo_garantia'] }}</td>
                    <td>{{ $ascendantRenovatedBlog['monto'] }}</td>
                    <td>{{ $ascendantRenovatedBlog['unidad_ejecutora'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No hay blogs renovados ascendientes.</p>
@endif

<!-- Muestra la suma de montos -->
<p>Total Monto: {{ $data['total_monto'] }}</p>

</body>
</html>
