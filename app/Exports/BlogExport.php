<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Blog;

class BlogExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $blogs;
    
    public function __construct($blogs)
    {
        $this->blogs = $blogs;
    }
    
    public function headings(): array
    {
        return [
            'Número de boleta',
            'Empresa/afianzado',
            'Motivo',
            'Afianzadora',
            'Tipo de Garantía',
            'Monto',
            'Unidad Ejecutora',
            'Caracteristicas',
            'Fecha de vencimiento',
            'Observaciones',
        ];
    }
    public function collection()
    {
        // Transforma la estructura de datos según tus necesidades
        $formattedBlogs = collect($this->blogs)->map(function ($blog) {
            return [
                'Número de Boleta' => $blog['num_boleta'],
                'Afianzado' => $blog['afianzado']['nombre'],
                'Motivo' => $blog['motivo'],
                'Financiadoras' => $blog['financiadoras']->pluck('nombre')->implode(', '),
                'Tipo Garantía' => $blog['tipoGarantia']['nombre'],
                'Monto' => number_format($blog['waranty']['monto'], 2, ',', '.'),
                'Unidad Ejecutora' => $blog['unidadEjecutora']['nombre'],
                'Características' => $blog['waranty']['caracteristicas'],
                'Fecha Final' => $blog['waranty']['fecha_final'],
                'Observaciones' => $blog['waranty']['observaciones'],
                // Agrega más campos según sea necesario
            ];
        });

        return $formattedBlogs;
    }
    public function styles(Worksheet $sheet)
    {
        // Aplicar estilos al encabezado (primera fila)
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'DDDDDD',
                ],
            ],
        ];

        $sheet->getStyle('A1:J1')->applyFromArray($styleArray);

        // Ajustar la altura de la primera fila (encabezado)
        $sheet->getRowDimension(1)->setRowHeight(30); // Ajusta la altura según tus necesidades

         //Ajustar la altura de las filas de datos (por ejemplo, a la fila 2)
        $sheet->getRowDimension(2)->setRowHeight(20); // Puedes ajustar la altura según tus necesidades
    }
    
}