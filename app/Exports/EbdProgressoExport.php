<?php

namespace App\Exports;

use App\Models\EbdAluno;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EbdProgressoExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithProperties
{
    protected $turmaId;

    public function __construct($turmaId = null)
    {
        $this->turmaId = $turmaId;
    }

    public function collection()
    {
        $query = EbdAluno::with(['turma', 'presencas.aula', 'notas.avaliacao']);
        
        if ($this->turmaId) {
            $query->where('turma_id', $this->turmaId);
        }
        
        return $query->where('status', 'ativo')->orderBy('nome')->get();
    }

    public function headings(): array
    {
        return [
            'Aluno',
            'Turma',
            'Data de Matrícula',
            'Total de Aulas',
            'Presenças',
            'Faltas',
            'Percentual de Presença',
            'Total de Avaliações',
            'Média Geral',
            'Status',
            'Observações'
        ];
    }

    public function map($aluno): array
    {
        $totalAulas = $aluno->presencas->count();
        $presencas = $aluno->presencas->where('status', 'presente')->count();
        $faltas = $aluno->presencas->where('status', 'ausente')->count();
        $percentualPresenca = $totalAulas > 0 ? ($presencas / $totalAulas) * 100 : 0;
        
        $totalAvaliacoes = $aluno->notas->count();
        $mediaGeral = $totalAvaliacoes > 0 ? $aluno->notas->avg('percentual') : 0;

        return [
            $aluno->nome,
            $aluno->turma->nome ?? 'Turma não encontrada',
            $aluno->data_matricula ? $aluno->data_matricula->format('d/m/Y') : 'Data não informada',
            $totalAulas,
            $presencas,
            $faltas,
            number_format($percentualPresenca, 2) . '%',
            $totalAvaliacoes,
            number_format($mediaGeral, 2) . '%',
            ucfirst($aluno->status),
            $aluno->observacoes ?? 'Nenhuma observação'
        ];
    }

    public function title(): string
    {
        return 'Relatório de Progresso EBD';
    }

    public function properties(): array
    {
        return [
            'creator' => 'CBAV Sistema',
            'title' => 'Relatório de Progresso EBD',
            'description' => 'Relatório detalhado de progresso dos alunos na EBD',
            'subject' => 'EBD - Progresso',
            'keywords' => 'EBD, progresso, alunos, relatório, educação bíblica',
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
            ]
        ];
    }
} 