<?php

namespace App\Exports;

use App\Models\Campanha;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CampanhasExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Campanha::where('ativo', true)
            ->with(['transacoes'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Título',
            'Descrição',
            'Meta (R$)',
            'Arrecadado (R$)',
            'Progresso (%)',
            'Status',
            'Data Início',
            'Data Fim',
            'Dias Restantes',
            'Total Transações',
            'Data Criação'
        ];
    }

    public function map($campanha): array
    {
        return [
            $campanha->id,
            $campanha->titulo,
            $campanha->descricao,
            number_format($campanha->meta_valor, 2, ',', '.'),
            number_format($campanha->valor_arrecadado, 2, ',', '.'),
            number_format($campanha->progresso, 1) . '%',
            ucfirst($campanha->status),
            $campanha->data_inicio->format('d/m/Y'),
            $campanha->data_fim ? $campanha->data_fim->format('d/m/Y') : 'Sem data fim',
            $campanha->dias_restantes,
            $campanha->transacoes->count(),
            $campanha->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E3F2FD']
                ]
            ]
        ];
    }
} 