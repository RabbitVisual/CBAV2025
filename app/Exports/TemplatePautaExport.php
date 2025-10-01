<?php

namespace App\Exports;

use App\Models\TemplatePauta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TemplatePautaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithProperties, WithColumnWidths
{
    protected $templates;

    public function __construct($templates = null)
    {
        $this->templates = $templates ?? TemplatePauta::with(['criadoPor', 'itens'])->get();
    }

    public function collection()
    {
        return $this->templates;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nome do Template',
            'Descrição',
            'Categoria',
            'Status',
            'Criado Por',
            'Data de Criação',
            'Número de Itens',
            'Número de Usos',
            'Última Atualização'
        ];
    }

    public function map($template): array
    {
        return [
            $template->id,
            $template->nome,
            $template->descricao ?? 'N/A',
            $template->categoria_text,
            $template->status_text,
            $template->criadoPor->name ?? 'N/A',
            $template->created_at->format('d/m/Y H:i'),
            $template->itens->count(),
            $template->conselhos->count(),
            $template->updated_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo do cabeçalho
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Estilo das linhas de dados
        $sheet->getStyle('A2:J' . ($sheet->getHighestRow()))->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Alternar cores das linhas
        for ($row = 2; $row <= $sheet->getHighestRow(); $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8FAFC']
                    ]
                ]);
            }
        }

        // Bordas
        $sheet->getStyle('A1:J' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0']
                ]
            ]
        ]);

        return $sheet;
    }

    public function properties(): array
    {
        return [
            'creator' => 'CBAV Sistema',
            'lastModifiedBy' => 'CBAV Sistema',
            'title' => 'Templates de Pauta',
            'description' => 'Relatório de templates de pauta do sistema',
            'subject' => 'Templates de Pauta',
            'keywords' => 'templates, pauta, conselho, reunião',
            'category' => 'Relatórios',
            'manager' => 'CBAV Sistema',
            'company' => 'CBAV'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 30,  // Nome
            'C' => 40,  // Descrição
            'D' => 20,  // Categoria
            'E' => 15,  // Status
            'F' => 25,  // Criado Por
            'G' => 20,  // Data Criação
            'H' => 15,  // Número Itens
            'I' => 15,  // Número Usos
            'J' => 20   // Última Atualização
        ];
    }
} 