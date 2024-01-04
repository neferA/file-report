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
        h1, h2 {
            font-size: 18px;
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
    <h1>Informe de Boleta de Garantía</h1>
    <table>
        <thead>
            <tr>
                <th>Número de Boleta</th>
                <th>Usuario</th>
                <th>empresa/afianzado</th>
                <th>motivo</th>
                <th>financiadora</th>
                <th>Tipo de Garantía</th>
                <th>Monto</th>
                <th>Unidad Ejecutora</th>
                <th>caracteristicas</th>
                <th>Fecha Inicio</th>
                <th>Fecha Final</th>
                <th>observaciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $data['num_boleta'] }}</td>
                <td>{{ $data['usuario'] }}</td>
                <td>{{ $data['afianzado'] }}</td>
                <td>{{ $data['motivo'] }}</td>
                <td>{{ isset($data['financiadoras']) ? $data['financiadoras'] : '' }}</td>
                <td>{{ $data['tipo_garantia'] }}</td>
                <td>{{ number_format($data['monto'], 2, ',', '.') }}</td>
                <td>{{ $data['unidad_ejecutora'] }}</td>
                <td>{{ $data['caracteristicas'] }}</td>
                <td>{{ $data['fecha_inicio'] }}</td>
                <td>{{ $data['fecha_final'] }}</td>
                <td>{{ $data['observaciones'] }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Sección para mostrar hijos -->
    @if(isset($data['descendant_renovated_blog_data']) && count($data['descendant_renovated_blog_data']) > 0)
        <h2>Boletas Renovadas Descendientes</h2>
        <table>
            <thead>
                <tr>
                    <th>Número de Boleta</th>
                    <th>Usuario</th>
                    <th>empresa/afianzado</th>
                    <th>motivo</th>
                    <th>financiadora</th>
                    <th>Tipo de Garantía</th>
                    <th>Monto</th>
                    <th>Unidad Ejecutora</th>
                    <th>caracteristicas</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Final</th>
                    <th>observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['descendant_renovated_blog_data'] as $descendantRenovatedBlog)
                    <tr>
                        <td>{{ $descendantRenovatedBlog['num_boleta'] }}</td>
                        <td>{{ $descendantRenovatedBlog['usuario'] }}</td>
                        <td>{{ $descendantRenovatedBlog['afianzado'] }}</td>
                        <td>{{ $descendantRenovatedBlog['motivo'] }}</td>
                        <td>{{ $descendantRenovatedBlog['financiadoras'] }}</td>
                        <td>{{ $descendantRenovatedBlog['tipo_garantia'] }}</td>
                        <td>{{ number_format($descendantRenovatedBlog['monto'], 2, ',', '.') }}</td>
                        <td>{{ $descendantRenovatedBlog['unidad_ejecutora'] }}</td>
                        <td>{{ $descendantRenovatedBlog['caracteristicas'] }}</td>
                        <td>{{ $descendantRenovatedBlog['fecha_inicio'] }}</td>
                        <td>{{ $descendantRenovatedBlog['fecha_final'] }}</td>
                        <td>{{ $descendantRenovatedBlog['observaciones'] }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay Boletas renovados descendientes.</p>
    @endif

    <!-- Sección para mostrar ascendientes -->
    @if(isset($data['ascendant_renovated_blog_data']) && count($data['ascendant_renovated_blog_data']) > 0)
        <h2>Boletas Renovadas Ascendientes</h2>
        <table>
            <thead>
                <tr>
                    <th>Número de Boleta</th>
                    <th>Usuario</th>
                    <th>empresa/afianzado</th>
                    <th>motivo</th>
                    <th>financiadora</th>
                    <th>Tipo de Garantía</th>
                    <th>Monto</th>
                    <th>Unidad Ejecutora</th>
                    <th>caracteristicas</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Final</th>
                    <th>observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['ascendant_renovated_blog_data'] as $ascendantRenovatedBlog)
                    <tr>
                        <td>{{ $ascendantRenovatedBlog['num_boleta'] }}</td>
                        <td>{{ $ascendantRenovatedBlog['usuario'] }}</td>
                        <td>{{ $ascendantRenovatedBlog['afianzado'] }}</td>
                        <td>{{ $ascendantRenovatedBlog['motivo'] }}</td>
                        <td>{{ $ascendantRenovatedBlog['financiadoras']}}</td>
                        <td>{{ $ascendantRenovatedBlog['tipo_garantia'] }}</td>
                        <td>{{ number_format($ascendantRenovatedBlog['monto'], 2, ',', '.') }}</td>
                        <td>{{ $ascendantRenovatedBlog['unidad_ejecutora'] }}</td>
                        <td>{{ $ascendantRenovatedBlog['caracteristicas'] }}</td>
                        <td>{{ $ascendantRenovatedBlog['fecha_inicio'] }}</td>
                        <td>{{ $ascendantRenovatedBlog['fecha_final'] }}</td>
                        <td>{{ $ascendantRenovatedBlog['observaciones'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay blogs renovados ascendientes.</p>
    @endif

    <!-- Muestra la suma de montos -->
    <p>Total Monto: {{ number_format($data['total_monto'], 2, ',', '.') }}</p>


</body>
</html>
