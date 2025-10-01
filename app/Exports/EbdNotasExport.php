<?php

namespace App\Exports;

use App\Models\EbdNota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EbdNotasExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithProperties
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

    public function collection()
    {
        $query = EbdNota::with(['aluno', 'avaliacao.aula.turma', 'avaliacao.aula.licao']);
        
        if ($this->turmaId) {
            $query->whereHas('avaliacao.aula', function($q) {
                $q->where('turma_id', $this->turmaId);
            });
        }
        
        if ($this->dataInicio) {
            $query->whereHas('avaliacao.aula', function($q) {
                $q->where('data_aula', '>=', $this->dataInicio);
            });
        }
        
        if ($this->dataFim) {
            $query->whereHas('avaliacao.aula', function($q) {
                $q->where('data_aula', '<=', $this->dataFim);
            });
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Aluno',
            'Turma',
            'Avaliação',
            'Tipo',
            'Nota',
            'Pontuação Máxima',
            'Percentual',
            'Observações',
            'Data da Avaliação',
            'Data de Registro'
        ];
    }

    public function map($nota): array
    {
        return [
            $nota->aluno->nome ?? 'Aluno não encontrado',
            $nota->avaliacao->aula->turma->nome ?? 'Turma não encontrada',
            $nota->avaliacao->titulo ?? 'Avaliação não encontrada',
            $nota->avaliacao->tipo_formatado ?? 'Tipo não informado',
            $nota->nota,
            $nota->pontuacao_maxima,
            number_format($nota->percentual, 2) . '%',
            $nota->observacoes ?? 'Nenhuma observação',
            $nota->avaliacao->aula->data_aula ? $nota->avaliacao->aula->data_aula->format('d/m/Y') : 'Data não informada',
            $nota->created_at->format('d/m/Y H:i:s')
        ];
    }

    public function title(): string
    {
        return 'Relatório de Notas EBD';
    }

    public function properties(): array
    {
        return [
            'creator' => 'CBAV Sistema',
            'title' => 'Relatório de Notas EBD',
            'description' => 'Relatório detalhado de notas dos alunos na EBD',
            'subject' => 'EBD - Notas',
            'keywords' => 'EBD, notas, avaliações, relatório, educação bíblica',
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