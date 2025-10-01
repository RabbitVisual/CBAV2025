<?php

namespace App\Exports;

use App\Models\Transacao;
use App\Models\Configuracao;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class TransacoesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithProperties, WithColumnWidths
{
    protected $filtros;

    public function __construct($filtros = [])
    {
        $this->filtros = $filtros;
    }

    public function collection()
    {
        $query = Transacao::with(['membro', 'campanha']);

        // Aplicar filtros
        if (isset($this->filtros['search'])) {
            $query->where(function($q) {
                $q->where('id', 'like', '%' . $this->filtros['search'] . '%')
                  ->orWhere('descricao', 'like', '%' . $this->filtros['search'] . '%');
            });
        }

        if (isset($this->filtros['status'])) {
            $query->where('status', $this->filtros['status']);
        }

        if (isset($this->filtros['tipo'])) {
            $query->where('tipo', $this->filtros['tipo']);
        }

        if (isset($this->filtros['data_inicio'])) {
            $query->whereDate('created_at', '>=', $this->filtros['data_inicio']);
        }

        if (isset($this->filtros['data_fim'])) {
            $query->whereDate('created_at', '<=', $this->filtros['data_fim']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Descrição',
            'Valor (R$)',
            'Tipo',
            'Status',
            'Membro',
            'Campanha',
            'Data Transação',
            'Data Registro',
            'Observações'
        ];
    }

    public function map($transacao): array
    {
        $tipoColor = $transacao->tipo === 'entrada' ? 'Verde' : 'Vermelho';
        $statusColor = $this->getStatusColor($transacao->status);
        
        return [
            $transacao->id,
            $transacao->descricao,
            number_format($transacao->valor, 2, ',', '.'),
            ucfirst($transacao->tipo) . ' (' . $tipoColor . ')',
            ucfirst($transacao->status) . ' (' . $statusColor . ')',
            $transacao->membro ? $transacao->membro->nome : 'Anônimo',
            $transacao->campanha ? $transacao->campanha->titulo : 'Sem campanha',
            $transacao->data ? $transacao->data->format('d/m/Y') : 'N/A',
            $transacao->created_at->format('d/m/Y H:i'),
            $transacao->observacoes ?? 'Nenhuma observação'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Configurar larguras das colunas otimizadas
        $sheet->getColumnDimension('A')->setWidth(6);   // ID
        $sheet->getColumnDimension('B')->setWidth(45);  // Descrição
        $sheet->getColumnDimension('C')->setWidth(15);  // Valor
        $sheet->getColumnDimension('D')->setWidth(18);  // Tipo
        $sheet->getColumnDimension('E')->setWidth(18);  // Status
        $sheet->getColumnDimension('F')->setWidth(30);  // Membro
        $sheet->getColumnDimension('G')->setWidth(30);  // Campanha
        $sheet->getColumnDimension('H')->setWidth(15);  // Data Transação
        $sheet->getColumnDimension('I')->setWidth(18);  // Data Registro
        $sheet->getColumnDimension('J')->setWidth(35);  // Observações

        // Obter dados da igreja
        $igrejaNome = Configuracao::get('igreja_nome', 'Igreja');
        $igrejaEndereco = Configuracao::get('igreja_endereco', '');
        $igrejaTelefone = Configuracao::get('igreja_telefone', '');
        $igrejaEmail = Configuracao::get('igreja_email', '');

        // Adicionar cabeçalho da igreja
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', $igrejaNome);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '1976D2']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // Adicionar informações da igreja
        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', $igrejaEndereco);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'size' => 10,
                'color' => ['rgb' => '666666']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ]);

        $sheet->mergeCells('A3:J3');
        $sheet->setCellValue('A3', "Telefone: {$igrejaTelefone} | Email: {$igrejaEmail}");
        $sheet->getStyle('A3')->applyFromArray([
            'font' => [
                'size' => 10,
                'color' => ['rgb' => '666666']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // Adicionar título do relatório
        $sheet->mergeCells('A4:J4');
        $sheet->setCellValue('A4', 'RELATÓRIO DE TRANSAÇÕES FINANCEIRAS');
        $sheet->getStyle('A4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => '333333']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // Adicionar período do relatório
        $periodo = $this->getPeriodoText();
        $sheet->mergeCells('A5:J5');
        $sheet->setCellValue('A5', $periodo);
        $sheet->getStyle('A5')->applyFromArray([
            'font' => [
                'size' => 11,
                'color' => ['rgb' => '666666']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // Adicionar linha em branco
        $sheet->mergeCells('A6:J6');
        $sheet->setCellValue('A6', '');

        // Estilo para cabeçalho da tabela (agora na linha 7)
        $sheet->getStyle('A7:J7')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1976D2']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'FFFFFF']
                ]
            ]
        ]);

        // Obter última linha de dados
        $lastRow = $sheet->getHighestRow();

        // Estilo para dados (a partir da linha 8)
        if ($lastRow > 7) {
            $sheet->getStyle('A8:J' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'E0E0E0']
                    ]
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ]
            ]);

            // Alternar cores das linhas
            for ($row = 8; $row <= $lastRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F5F5F5']
                        ]
                    ]);
                }
            }
        }

        // Adicionar linha em branco antes das estatísticas
        $estatisticasRow = $lastRow + 2;
        $sheet->mergeCells('A' . $estatisticasRow . ':J' . $estatisticasRow);
        $sheet->setCellValue('A' . $estatisticasRow, '');

        // Calcular estatísticas
        $estatisticas = $this->calcularEstatisticas();

        // Adicionar estatísticas no final
        $estatisticasRow++;
        $sheet->mergeCells('A' . $estatisticasRow . ':J' . $estatisticasRow);
        $sheet->setCellValue('A' . $estatisticasRow, 'ESTATÍSTICAS DO PERÍODO');
        $sheet->getStyle('A' . $estatisticasRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => '333333']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // Receitas
        $estatisticasRow++;
        $sheet->setCellValue('A' . $estatisticasRow, 'Receitas:');
        $sheet->setCellValue('B' . $estatisticasRow, 'R$ ' . number_format($estatisticas['receitas'], 2, ',', '.'));
        $sheet->getStyle('B' . $estatisticasRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '2E7D32']
            ]
        ]);

        // Despesas
        $estatisticasRow++;
        $sheet->setCellValue('A' . $estatisticasRow, 'Despesas:');
        $sheet->setCellValue('B' . $estatisticasRow, 'R$ ' . number_format($estatisticas['despesas'], 2, ',', '.'));
        $sheet->getStyle('B' . $estatisticasRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'D32F2F']
            ]
        ]);

        // Saldo
        $estatisticasRow++;
        $sheet->setCellValue('A' . $estatisticasRow, 'Saldo:');
        $sheet->setCellValue('B' . $estatisticasRow, 'R$ ' . number_format($estatisticas['saldo'], 2, ',', '.'));
        $sheet->getStyle('B' . $estatisticasRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => $estatisticas['saldo'] >= 0 ? ['rgb' => '2E7D32'] : ['rgb' => 'D32F2F']
            ]
        ]);

        // Total de transações
        $estatisticasRow++;
        $sheet->setCellValue('A' . $estatisticasRow, 'Total de Transações:');
        $sheet->setCellValue('B' . $estatisticasRow, $estatisticas['total_transacoes']);
        $sheet->getStyle('B' . $estatisticasRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '1976D2']
            ]
        ]);

        // Adicionar linha em branco antes do resumo
        $resumoRow = $estatisticasRow + 2;
        $sheet->mergeCells('A' . $resumoRow . ':J' . $resumoRow);
        $sheet->setCellValue('A' . $resumoRow, '');

        // Adicionar resumo final
        $resumoRow++;
        $sheet->mergeCells('A' . $resumoRow . ':J' . $resumoRow);
        $sheet->setCellValue('A' . $resumoRow, 'RESUMO FINAL');
        $sheet->getStyle('A' . $resumoRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => '333333']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ]);

        $resumoRow++;
        $sheet->mergeCells('A' . $resumoRow . ':J' . $resumoRow);
        $sheet->setCellValue('A' . $resumoRow, 'Este relatório foi gerado automaticamente pelo sistema de gestão da igreja.');
        $sheet->getStyle('A' . $resumoRow)->applyFromArray([
            'font' => [
                'size' => 10,
                'color' => ['rgb' => '666666']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ]);

        $resumoRow++;
        $sheet->mergeCells('A' . $resumoRow . ':J' . $resumoRow);
        $sheet->setCellValue('A' . $resumoRow, 'Data de geração: ' . now()->format('d/m/Y H:i:s'));
        $sheet->getStyle('A' . $resumoRow)->applyFromArray([
            'font' => [
                'size' => 10,
                'color' => ['rgb' => '666666']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ]);
    }

    public function properties(): array
    {
        return [
            'creator' => 'Sistema CBAV',
            'lastModifiedBy' => 'Administrador',
            'title' => 'Relatório de Transações',
            'description' => 'Relatório detalhado de transações financeiras',
            'subject' => 'Transações Financeiras',
            'keywords' => 'transações, financeiro, relatório',
            'category' => 'Relatórios',
            'manager' => 'CBAV',
            'company' => 'Congregação Batista Avenida',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 35,  // Descrição
            'C' => 15,  // Valor
            'D' => 15,  // Tipo
            'E' => 15,  // Status
            'F' => 25,  // Membro
            'G' => 25,  // Campanha
            'H' => 15,  // Data Transação
            'I' => 18,  // Data Registro
            'J' => 30,  // Observações
        ];
    }

    private function getStatusColor($status)
    {
        switch ($status) {
            case 'confirmado':
                return 'Verde';
            case 'pendente':
                return 'Amarelo';
            case 'cancelado':
                return 'Vermelho';
            default:
                return 'Cinza';
        }
    }

    private function getPeriodoText()
    {
        $texto = 'Período: ';
        
        if (isset($this->filtros['data_inicio']) && isset($this->filtros['data_fim'])) {
            $texto .= Carbon::parse($this->filtros['data_inicio'])->format('d/m/Y') . ' a ' . Carbon::parse($this->filtros['data_fim'])->format('d/m/Y');
        } elseif (isset($this->filtros['data_inicio'])) {
            $texto .= 'A partir de ' . Carbon::parse($this->filtros['data_inicio'])->format('d/m/Y');
        } elseif (isset($this->filtros['data_fim'])) {
            $texto .= 'Até ' . Carbon::parse($this->filtros['data_fim'])->format('d/m/Y');
        } else {
            $texto .= 'Todos os períodos';
        }

        if (isset($this->filtros['status'])) {
            $texto .= ' | Status: ' . ucfirst($this->filtros['status']);
        }

        if (isset($this->filtros['tipo'])) {
            $texto .= ' | Tipo: ' . ucfirst($this->filtros['tipo']);
        }

        return $texto;
    }

    private function calcularEstatisticas()
    {
        $query = Transacao::query();

        // Aplicar os mesmos filtros
        if (isset($this->filtros['search'])) {
            $query->where(function($q) {
                $q->where('id', 'like', '%' . $this->filtros['search'] . '%')
                  ->orWhere('descricao', 'like', '%' . $this->filtros['search'] . '%');
            });
        }

        if (isset($this->filtros['status'])) {
            $query->where('status', $this->filtros['status']);
        }

        if (isset($this->filtros['tipo'])) {
            $query->where('tipo', $this->filtros['tipo']);
        }

        if (isset($this->filtros['data_inicio'])) {
            $query->whereDate('created_at', '>=', $this->filtros['data_inicio']);
        }

        if (isset($this->filtros['data_fim'])) {
            $query->whereDate('created_at', '<=', $this->filtros['data_fim']);
        }

        $transacoes = $query->get();

        $receitas = $transacoes->where('tipo', 'entrada')->where('status', 'confirmado')->sum('valor');
        $despesas = $transacoes->where('tipo', 'saida')->where('status', 'confirmado')->sum('valor');
        $saldo = $receitas - $despesas;
        $total_transacoes = $transacoes->count();

        return [
            'receitas' => $receitas,
            'despesas' => $despesas,
            'saldo' => $saldo,
            'total_transacoes' => $total_transacoes
        ];
    }
} 