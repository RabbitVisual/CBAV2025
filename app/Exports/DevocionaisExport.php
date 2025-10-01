<?php

namespace App\Exports;

use App\Models\Devocional;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DevocionaisExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Devocional::orderBy('data', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Título',
            'Texto',
            'Versículo',
            'Reflexão',
            'Data',
            'Tipo',
            'Status',
            'Ordem',
            'Data Criação'
        ];
    }

    public function map($devocional): array
    {
        return [
            $devocional->id,
            $devocional->titulo,
            $devocional->texto,
            $devocional->versiculo,
            $devocional->reflexao,
            $devocional->data->format('d/m/Y'),
            ucfirst($devocional->tipo),
            $devocional->ativo ? 'Ativo' : 'Inativo',
            $devocional->ordem,
            $devocional->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ]
        ];
    }
} 