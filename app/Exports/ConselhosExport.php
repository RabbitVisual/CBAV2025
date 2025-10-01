<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ConselhosExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $conselhos;

    public function __construct($conselhos)
    {
        $this->conselhos = $conselhos;
    }

    public function collection()
    {
        return $this->conselhos;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Título',
            'Tipo',
            'Data da Reunião',
            'Hora Início',
            'Hora Fim',
            'Local',
            'Status',
            'Quórum Mínimo',
            'Quórum Atual',
            'Presidente',
            'Secretário',
            'Criado Por',
            'Data Criação'
        ];
    }

    public function map($conselho): array
    {
        return [
            $conselho->id,
            $conselho->titulo,
            $conselho->tipo_text,
            $conselho->data_reuniao->format('d/m/Y'),
            $conselho->hora_inicio,
            $conselho->hora_fim,
            $conselho->local ?? 'Não informado',
            $conselho->status_text,
            $conselho->quorum_minimo,
            $conselho->quorum_atual,
            $conselho->presidente->name ?? 'Não definido',
            $conselho->secretario->name ?? 'Não definido',
            $conselho->criadoPor->name,
            $conselho->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ]
        ];
    }
} 