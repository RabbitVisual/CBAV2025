<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuizEstatisticasExport implements FromArray, WithHeadings, WithStyles, WithTitle, WithProperties
{
    protected $estatisticas;
    protected $melhoresPontuacoes;

    public function __construct($estatisticas, $melhoresPontuacoes)
    {
        $this->estatisticas = $estatisticas;
        $this->melhoresPontuacoes = $melhoresPontuacoes;
    }

    public function array(): array
    {
        $data = [];
        
        // Estatísticas gerais
        $data[] = ['ESTATÍSTICAS GERAIS'];
        $data[] = ['Total de Perguntas', $this->estatisticas['total_perguntas']];
        $data[] = ['Perguntas Ativas', $this->estatisticas['perguntas_ativas']];
        $data[] = ['Total de Sessões', $this->estatisticas['total_sessoes']];
        $data[] = ['Sessões Hoje', $this->estatisticas['sessoes_hoje']];
        $data[] = ['Sessões Esta Semana', $this->estatisticas['sessoes_semana']];
        $data[] = ['Sessões Este Mês', $this->estatisticas['sessoes_mes']];
        $data[] = [];
        
        // Top 10 melhores pontuações
        $data[] = ['TOP 10 MELHORES PONTUAÇÕES'];
        $data[] = ['Posição', 'Usuário', 'Pontuação Total', 'Percentual', 'Data'];
        
        foreach ($this->melhoresPontuacoes as $index => $sessao) {
            $data[] = [
                $index + 1,
                $sessao->user->name ?? 'N/A',
                $sessao->pontuacao_total,
                number_format($sessao->percentual, 1) . '%',
                $sessao->created_at->format('d/m/Y H:i')
            ];
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'Estatísticas Quiz Bíblico';
    }

    public function properties(): array
    {
        return [
            'creator' => 'CBAV Sistema',
            'title' => 'Estatísticas Quiz Bíblico',
            'description' => 'Relatório de estatísticas do sistema de quiz bíblico',
            'company' => 'CBAV',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:A7')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(14);
        $sheet->getStyle('A9:A9')->getFont()->setBold(true);
        $sheet->getStyle('A9')->getFont()->setSize(14);
        $sheet->getStyle('A10:E10')->getFont()->setBold(true);
        
        // Aplicar cores de fundo
        $sheet->getStyle('A1:A7')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E3F2FD'],
            ],
        ]);
        
        $sheet->getStyle('A9:A9')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F3E5F5'],
            ],
        ]);
        
        $sheet->getStyle('A10:E10')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F1F8E9'],
            ],
        ]);
        
        // Auto-size columns
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        return $sheet;
    }
} 