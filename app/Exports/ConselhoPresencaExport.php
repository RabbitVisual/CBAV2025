<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\Configuracao;

class ConselhoPresencaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithCustomStartCell, WithEvents
{
    protected $conselho;
    protected $participantes;
    protected $configuracoes;

    public function __construct($conselho, $participantes)
    {
        $this->conselho = $conselho;
        $this->participantes = $participantes;
        
        // Carregar configurações do sistema
        $this->configuracoes = [
            'app_name' => Configuracao::get('app_name', config('app.name', 'CBAV')),
            'igreja_nome' => Configuracao::get('igreja_nome', 'Congregação Batista Avenida'),
            'contact_email' => Configuracao::get('contact_email', ''),
            'contact_phone' => Configuracao::get('contact_phone', ''),
            'address' => Configuracao::get('address', ''),
        ];
    }

    public function collection()
    {
        return $this->participantes;
    }

    public function headings(): array
    {
        return [
            'Nome do Participante',
            'E-mail',
            'Cargo no Conselho',
            'Presente',
            'Observações'
        ];
    }

    public function map($participante): array
    {
        return [
            $participante->user->name ?? 'N/A',
            $participante->user->email ?? 'N/A',
            $participante->cargo ?? 'Membro',
            $participante->presente ? 'SIM' : 'NÃO',
            $participante->observacoes ?? ''
        ];
    }

    public function startCell(): string
    {
        return 'A8'; // Começar na linha 8 para deixar espaço para cabeçalho
    }

    public function title(): string
    {
        return 'Presença - ' . $this->conselho->titulo;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo do cabeçalho dos dados
            8 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0284C7']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Configurar largura das colunas
                $sheet->getColumnDimension('A')->setWidth(25);
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(20);
                $sheet->getColumnDimension('D')->setWidth(12);
                $sheet->getColumnDimension('E')->setWidth(30);
                
                // Cabeçalho do relatório
                $sheet->mergeCells('A1:E1');
                $sheet->setCellValue('A1', $this->configuracoes['app_name']);
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
                
                $sheet->mergeCells('A2:E2');
                $sheet->setCellValue('A2', $this->configuracoes['igreja_nome']);
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
                
                $sheet->mergeCells('A3:E3');
                $sheet->setCellValue('A3', 'RELATÓRIO DE PRESENÇA - CONSELHO');
                $sheet->getStyle('A3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
                
                // Informações da reunião
                $sheet->mergeCells('A5:B5');
                $sheet->setCellValue('A5', 'Reunião:');
                $sheet->setCellValue('C5', $this->conselho->titulo);
                $sheet->getStyle('A5')->getFont()->setBold(true);
                
                $sheet->mergeCells('A6:B6');
                $sheet->setCellValue('A6', 'Data/Hora:');
                $sheet->setCellValue('C6', $this->conselho->data_reuniao->format('d/m/Y') . ' às ' . $this->conselho->hora_inicio);
                $sheet->getStyle('A6')->getFont()->setBold(true);
                
                $sheet->setCellValue('D6', 'Status:');
                $sheet->setCellValue('E6', ucfirst(str_replace('_', ' ', $this->conselho->status)));
                $sheet->getStyle('D6')->getFont()->setBold(true);
                
                // Estatísticas
                $totalParticipantes = $this->participantes->count();
                $presentes = $this->participantes->where('presente', true)->count();
                $ausentes = $totalParticipantes - $presentes;
                $percentualPresenca = $totalParticipantes > 0 ? round(($presentes / $totalParticipantes) * 100, 1) : 0;
                
                $ultimaLinha = 8 + $totalParticipantes + 2;
                
                $sheet->mergeCells("A{$ultimaLinha}:E{$ultimaLinha}");
                $sheet->setCellValue("A{$ultimaLinha}", 'ESTATÍSTICAS DE PRESENÇA');
                $sheet->getStyle("A{$ultimaLinha}")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E5E7EB']
                    ]
                ]);
                
                $ultimaLinha++;
                $sheet->setCellValue("A{$ultimaLinha}", 'Total de Participantes:');
                $sheet->setCellValue("B{$ultimaLinha}", $totalParticipantes);
                $sheet->setCellValue("C{$ultimaLinha}", 'Presentes:');
                $sheet->setCellValue("D{$ultimaLinha}", $presentes);
                $sheet->setCellValue("E{$ultimaLinha}", "{$percentualPresenca}%");
                
                $ultimaLinha++;
                $sheet->setCellValue("A{$ultimaLinha}", 'Ausentes:');
                $sheet->setCellValue("B{$ultimaLinha}", $ausentes);
                $sheet->setCellValue("C{$ultimaLinha}", 'Quórum Mín.:');
                $sheet->setCellValue("D{$ultimaLinha}", ($this->conselho->quorum_minimo ?? 50) . '%');
                
                // Rodapé
                $ultimaLinha += 3;
                $sheet->mergeCells("A{$ultimaLinha}:E{$ultimaLinha}");
                $sheet->setCellValue("A{$ultimaLinha}", 'Relatório gerado em ' . now()->format('d/m/Y H:i'));
                $sheet->getStyle("A{$ultimaLinha}")->applyFromArray([
                    'font' => ['size' => 9, 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
                
                // Bordas para a tabela de dados
                $ultimaLinhaTabela = 8 + $totalParticipantes;
                $sheet->getStyle("A8:E{$ultimaLinhaTabela}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);
                
                // Colorir linhas de presentes e ausentes
                for ($i = 9; $i <= $ultimaLinhaTabela; $i++) {
                    $presente = $sheet->getCell("D{$i}")->getValue();
                    if ($presente === 'SIM') {
                        $sheet->getStyle("A{$i}:E{$i}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'DCFCE7'] // Verde claro
                            ]
                        ]);
                    } elseif ($presente === 'NÃO') {
                        $sheet->getStyle("A{$i}:E{$i}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FEE2E2'] // Vermelho claro
                            ]
                        ]);
                    }
                }
            }
        ];
    }
} 