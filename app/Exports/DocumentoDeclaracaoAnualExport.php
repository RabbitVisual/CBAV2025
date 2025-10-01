<?php

namespace App\Exports;

use App\Models\DocumentoDeclaracaoAnual;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DocumentoDeclaracaoAnualExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithProperties
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
            'Igreja',
            'CNPJ',
            'Tipo de Documento',
            'Número do Documento',
            'Ano Exercício',
            'Data de Emissão',
            'Data de Vencimento',
            'Valor Total',
            'Valor Doações',
            'Valor Dízimos',
            'Valor Outros',
            'Status',
            'Validado em',
            'Validado por',
            'Hash de Validação',
            'Observações'
        ];
    }

    public function map($documento): array
    {
        return [
            $documento->protocolo_receita,
            $documento->igreja->nome ?? 'N/A',
            $documento->igreja->cnpj_formatado ?? 'N/A',
            DocumentoDeclaracaoAnual::TIPOS_DOCUMENTO[$documento->tipo_documento] ?? $documento->tipo_documento,
            $documento->numero_documento,
            $documento->ano_exercicio,
            $documento->data_emissao ? $documento->data_emissao->format('d/m/Y') : 'N/A',
            $documento->data_vencimento ? $documento->data_vencimento->format('d/m/Y') : 'N/A',
            number_format($documento->valor_total, 2, ',', '.'),
            number_format($documento->valor_doacoes, 2, ',', '.'),
            number_format($documento->valor_dizimos, 2, ',', '.'),
            number_format($documento->valor_outros, 2, ',', '.'),
            DocumentoDeclaracaoAnual::STATUS[$documento->status] ?? $documento->status,
            $documento->validado_em ? $documento->validado_em->format('d/m/Y H:i:s') : 'Não validado',
            $documento->validadoPor->name ?? 'N/A',
            $documento->hash_documento,
            $documento->observacoes ?? ''
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo do cabeçalho
        $sheet->getStyle('A1:Q1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F2937'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        // Estilo para valores monetários
        $sheet->getStyle('I:L')->applyFromArray([
            'numberFormat' => [
                'formatCode' => 'R$ #,##0.00',
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
        ]);

        // Estilo para datas
        $sheet->getStyle('G:H')->applyFromArray([
            'numberFormat' => [
                'formatCode' => 'dd/mm/yyyy',
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Estilo para status
        $sheet->getStyle('M')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Estilo para hash
        $sheet->getStyle('P')->applyFromArray([
            'font' => [
                'name' => 'Courier New',
                'size' => 8,
            ],
        ]);

        // Auto-dimensionar colunas
        foreach (range('A', 'Q') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Altura das linhas
        $sheet->getDefaultRowDimension()->setRowHeight(20);

        // Bordas para todas as células com dados
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:Q{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        // Cor de fundo alternada para linhas
        for ($row = 2; $row <= $lastRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle("A{$row}:Q{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F9FAFB'],
                    ],
                ]);
            }
        }

        // Estilo para valores zero
        $sheet->getStyle('I:L')->getNumberFormat()->setFormatCode('R$ #,##0.00');
    }

    public function title(): string
    {
        return 'Documentos Declaração Anual';
    }

    public function properties(): array
    {
        return [
            'creator' => 'CBAV Sistema',
            'lastModifiedBy' => 'CBAV Sistema',
            'title' => 'Documentos de Declaração Anual',
            'description' => 'Relatório de documentos de declaração anual da igreja',
            'subject' => 'Documentos de Declaração Anual',
            'keywords' => 'documentos, declaração, anual, igreja, receita federal',
            'category' => 'Relatórios Financeiros',
            'manager' => 'CBAV Sistema',
            'company' => 'CBAV Sistema',
        ];
    }
} 