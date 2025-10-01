<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class RelatorioFinanceiroExport implements FromArray, WithHeadings, WithStyles, WithTitle, WithProperties, WithColumnWidths
{
    private $dados;
    private $periodo;
    private $ano;
    private $mes;

    public function __construct($dados, $periodo, $ano, $mes)
    {
        $this->dados = $dados;
        $this->periodo = $periodo;
        $this->ano = $ano;
        $this->mes = $mes;
    }

    public function array(): array
    {
        $dados = [];

        // Cabeçalho do relatório
        $dados[] = ['RELATÓRIO FINANCEIRO'];
        $dados[] = ['Período: ' . $this->getPeriodoText()];
        $dados[] = ['Gerado em: ' . Carbon::now()->format('d/m/Y H:i:s')];
        $dados[] = [];

        // Resumo geral
        $dados[] = ['RESUMO GERAL'];
        $dados[] = ['Arrecadação Total', 'R$ ' . number_format($this->dados['arrecadacao_total'], 2, ',', '.')];
        $dados[] = [];

        // Detalhamento por tipo
        if (isset($this->dados['por_tipo'])) {
            $dados[] = ['DETALHAMENTO POR TIPO'];
            $dados[] = ['Tipo', 'Total (R$)', 'Quantidade', 'Média (R$)', '% do Total'];
            
            foreach ($this->dados['por_tipo'] as $tipo) {
                $dados[] = [
                    ucfirst($tipo->tipo),
                    number_format($tipo->total, 2, ',', '.'),
                    $tipo->quantidade,
                    number_format($tipo->total / $tipo->quantidade, 2, ',', '.'),
                    number_format(($tipo->total / $this->dados['arrecadacao_total']) * 100, 1) . '%'
                ];
            }
            $dados[] = [];
        }

        // Evolução temporal
        if (isset($this->dados['por_dia'])) {
            $dados[] = ['EVOLUÇÃO TEMPORAL'];
            $dados[] = ['Data', 'Arrecadação (R$)'];
            
            foreach ($this->dados['por_dia'] as $dia) {
                $dados[] = [
                    Carbon::parse($dia->dia)->format('d/m/Y'),
                    number_format($dia->total, 2, ',', '.')
                ];
            }
            $dados[] = [];
        }

        // Top doadores
        if (isset($this->dados['top_doadores'])) {
            $dados[] = ['TOP 10 DOADORES'];
            $dados[] = ['Posição', 'Membro', 'Total Doado (R$)', '% do Total'];
            
            foreach ($this->dados['top_doadores'] as $index => $doador) {
                $dados[] = [
                    '#' . ($index + 1),
                    $doador->membro->nome ?? 'Anônimo',
                    number_format($doador->total, 2, ',', '.'),
                    number_format(($doador->total / $this->dados['arrecadacao_total']) * 100, 1) . '%'
                ];
            }
        }

        return $dados;
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'Relatório Financeiro';
    }

    public function properties(): array
    {
        return [
            'creator' => 'CBAV CRM Ministerial',
            'title' => 'Relatório Financeiro - ' . $this->getPeriodoText(),
            'description' => 'Relatório financeiro detalhado da igreja',
            'subject' => 'Finanças',
            'keywords' => 'relatório, financeiro, igreja',
            'category' => 'Relatórios',
            'manager' => 'Administrador',
            'company' => 'CBAV',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 20,
            'C' => 15,
            'D' => 20,
            'E' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Estilo do título principal
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '2E7D32']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // Estilo dos subtítulos
        $sheet->getStyle('A2:A3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => '424242']
            ]
        ]);

        // Estilo dos cabeçalhos das seções
        $sectionRows = [6, 10, 18, 32]; // Linhas dos títulos das seções
        foreach ($sectionRows as $row) {
            if ($row <= $highestRow) {
                $sheet->getStyle("A{$row}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '1976D2']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E3F2FD']
                    ]
                ]);
            }
        }

        // Estilo dos cabeçalhos das tabelas
        $tableHeaderRows = [11, 19, 33]; // Linhas dos cabeçalhos das tabelas
        foreach ($tableHeaderRows as $row) {
            if ($row <= $highestRow) {
                $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1976D2']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ]);
            }
        }

        // Estilo dos dados das tabelas
        for ($row = 12; $row <= $highestRow; $row++) {
            $sheet->getStyle("A{$row}:E{$row}")->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT
                ]
            ]);
        }

        // Bordas para todas as células
        $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E0E0E0']
                ]
            ]
        ]);

        // Formatação de números
        $sheet->getStyle("B:C")->getNumberFormat()->setFormatCode('#,##0.00');

        return $sheet;
    }

    private function getPeriodoText()
    {
        switch ($this->periodo) {
            case 'mes':
                $mesNome = Carbon::create($this->ano, $this->mes, 1)->format('F');
                return "Mensal - {$mesNome}/{$this->ano}";
            case 'ano':
                return "Anual - {$this->ano}";
            case 'personalizado':
                return "Personalizado";
            default:
                return "Período não especificado";
        }
    }
} 