<?php

namespace App\Exports;

use App\Models\EbdTurma;
use App\Models\EbdAluno;
use App\Models\EbdAula;
use App\Models\EbdAvaliacao;
use App\Models\EbdPresenca;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EbdGeralExport implements FromArray, WithHeadings, WithStyles, WithTitle, WithProperties
{
    protected $turmaId;
    protected $dataInicio;
    protected $dataFim;

    public function __construct($turmaId = null, $dataInicio = null, $dataFim = null)
    {
        $this->turmaId = $turmaId;
        $this->dataInicio = $dataInicio;
        $this->dataFim = $dataFim;
    }

    public function array(): array
    {
        $dados = [];
        
        // Estatísticas gerais
        $totalTurmas = EbdTurma::ativas()->count();
        $totalAlunos = EbdAluno::ativos()->count();
        $totalAulas = EbdAula::realizadas()->count();
        $totalAvaliacoes = EbdAvaliacao::count();
        $totalPresencas = EbdPresenca::where('status', 'presente')->count();
        $totalFaltas = EbdPresenca::where('status', 'ausente')->count();
        $mediaPresenca = $totalPresencas + $totalFaltas > 0 ? ($totalPresencas / ($totalPresencas + $totalFaltas)) * 100 : 0;
        
        $dados[] = ['Estatísticas Gerais EBD', '', '', '', '', ''];
        $dados[] = ['Total de Turmas', $totalTurmas, '', '', '', ''];
        $dados[] = ['Total de Alunos', $totalAlunos, '', '', '', ''];
        $dados[] = ['Total de Aulas Realizadas', $totalAulas, '', '', '', ''];
        $dados[] = ['Total de Avaliações', $totalAvaliacoes, '', '', '', ''];
        $dados[] = ['Total de Presenças', $totalPresencas, '', '', '', ''];
        $dados[] = ['Total de Faltas', $totalFaltas, '', '', '', ''];
        $dados[] = ['Média de Presença', number_format($mediaPresenca, 2) . '%', '', '', '', ''];
        $dados[] = ['', '', '', '', '', ''];
        
        // Estatísticas por turma
        $dados[] = ['Estatísticas por Turma', '', '', '', '', ''];
        $dados[] = ['Turma', 'Alunos', 'Aulas', 'Presenças', 'Média Presença', 'Avaliações'];
        
        $turmas = EbdTurma::ativas()->withCount(['alunos', 'aulas'])->get();
        
        foreach ($turmas as $turma) {
            $alunosTurma = $turma->alunos()->where('status', 'ativo')->count();
            $aulasTurma = $turma->aulas()->where('status', 'realizada')->count();
            $presencasTurma = EbdPresenca::whereHas('aula', function($q) use ($turma) {
                $q->where('turma_id', $turma->id);
            })->where('status', 'presente')->count();
            $faltasTurma = EbdPresenca::whereHas('aula', function($q) use ($turma) {
                $q->where('turma_id', $turma->id);
            })->where('status', 'ausente')->count();
            $mediaPresencaTurma = $presencasTurma + $faltasTurma > 0 ? ($presencasTurma / ($presencasTurma + $faltasTurma)) * 100 : 0;
            $avaliacoesTurma = EbdAvaliacao::whereHas('aula', function($q) use ($turma) {
                $q->where('turma_id', $turma->id);
            })->count();
            
            $dados[] = [
                $turma->nome,
                $alunosTurma,
                $aulasTurma,
                $presencasTurma,
                number_format($mediaPresencaTurma, 2) . '%',
                $avaliacoesTurma
            ];
        }
        
        return $dados;
    }

    public function headings(): array
    {
        return [
            'Item',
            'Valor',
            'Coluna 3',
            'Coluna 4',
            'Coluna 5',
            'Coluna 6'
        ];
    }

    public function title(): string
    {
        return 'Relatório Geral EBD';
    }

    public function properties(): array
    {
        return [
            'creator' => 'CBAV Sistema',
            'title' => 'Relatório Geral EBD',
            'description' => 'Relatório geral com estatísticas da EBD',
            'subject' => 'EBD - Relatório Geral',
            'keywords' => 'EBD, relatório, estatísticas, educação bíblica',
            'category' => 'Relatórios',
            'manager' => 'CBAV',
            'company' => 'CBAV Sistema'
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
            ],
            2 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10B981']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
            10 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10B981']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
            11 => [
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