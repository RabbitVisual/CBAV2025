<?php

namespace App\Exports;

use App\Models\DocumentoBaixa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DocumentosBaixaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithProperties
{
    protected $documentos;

    public function __construct($documentos)
    {
        $this->documentos = $documentos;
    }

    public function collection()
    {
        return $this->documentos;
    }

    public function headings(): array
    {
        return [
            'Protocolo RF',
            'Tipo Documento',
            'Número Documento',
            'Ano Exercício',
            'Data Emissão',
            'Data Vencimento',
            'Valor Documento',
            'Valor Pago',
            'Status',
            'Membro',
            'Campanha',
            'Observações',
            'Hash Documento',
            'Data Criação'
        ];
    }

    public function map($documento): array
    {
        return [
            $documento->protocolo_receita,
            DocumentoBaixa::TIPOS_DOCUMENTO[$documento->tipo_documento] ?? $documento->tipo_documento,
            $documento->numero_documento,
            $documento->ano_exercicio,
            $documento->data_emissao ? $documento->data_emissao->format('d/m/Y') : '',
            $documento->data_vencimento ? $documento->data_vencimento->format('d/m/Y') : '',
            'R$ ' . number_format($documento->valor_documento, 2, ',', '.'),
            'R$ ' . number_format($documento->valor_pago, 2, ',', '.'),
            DocumentoBaixa::STATUS[$documento->status] ?? $documento->status,
            $documento->transacao->membro->nome ?? 'N/A',
            $documento->transacao->campanha->titulo ?? 'N/A',
            $documento->observacoes,
            $documento->hash_documento,
            $documento->created_at ? $documento->created_at->format('d/m/Y H:i:s') : ''
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo do cabeçalho
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2C3E50'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Estilo para valores monetários
        $sheet->getStyle('G:H')->getNumberFormat()->setFormatCode('#,##0.00');

        // Estilo para datas
        $sheet->getStyle('E:F')->getNumberFormat()->setFormatCode('dd/mm/yyyy');

        // Auto-dimensionar colunas
        foreach (range('A', 'N') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Adicionar bordas
        $sheet->getStyle('A1:N' . ($this->documentos->count() + 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Estilo alternado para linhas
        for ($i = 2; $i <= $this->documentos->count() + 1; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle("A{$i}:N{$i}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8F9FA'],
                    ],
                ]);
            }
        }

        return $sheet;
    }

    public function title(): string
    {
        return 'Documentos de Baixa';
    }

    public function properties(): array
    {
        return [
            'creator' => 'CBAV - Sistema de Gestão',
            'lastModifiedBy' => 'CBAV',
            'title' => 'Relatório de Documentos de Baixa',
            'description' => 'Relatório de documentos de baixa para declaração de IR',
            'subject' => 'Documentos de Baixa',
            'keywords' => 'documentos, baixa, IR, receita federal',
            'category' => 'Relatório Financeiro',
            'manager' => 'CBAV',
            'company' => 'CBAV',
        ];
    }
} 