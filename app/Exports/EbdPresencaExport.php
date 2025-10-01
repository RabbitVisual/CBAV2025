<?php

namespace App\Exports;

use App\Models\EbdPresenca;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EbdPresencaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithProperties
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
        $query = EbdPresenca::with(['aluno', 'aula.turma', 'aula.licao']);
        
        if ($this->turmaId) {
            $query->whereHas('aula', function($q) {
                $q->where('turma_id', $this->turmaId);
            });
        }
        
        if ($this->dataInicio) {
            $query->whereHas('aula', function($q) {
                $q->where('data_aula', '>=', $this->dataInicio);
            });
        }
        
        if ($this->dataFim) {
            $query->whereHas('aula', function($q) {
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
            'Data da Aula',
            'Lição',
            'Status',
            'Observações',
            'Data de Registro'
        ];
    }

    public function map($presenca): array
    {
        return [
            $presenca->aluno->nome ?? 'Aluno não encontrado',
            $presenca->aula->turma->nome ?? 'Turma não encontrada',
            $presenca->aula->data_aula ? $presenca->aula->data_aula->format('d/m/Y') : 'Data não informada',
            $presenca->aula->licao->titulo ?? 'Lição não encontrada',
            $this->getStatusFormatado($presenca->status),
            $presenca->observacoes ?? 'Nenhuma observação',
            $presenca->created_at->format('d/m/Y H:i:s')
        ];
    }

    public function title(): string
    {
        return 'Relatório de Presença EBD';
    }

    public function properties(): array
    {
        return [
            'creator' => 'CBAV Sistema',
            'title' => 'Relatório de Presença EBD',
            'description' => 'Relatório detalhado de presença dos alunos na EBD',
            'subject' => 'EBD - Presença',
            'keywords' => 'EBD, presença, relatório, educação bíblica',
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

    private function getStatusFormatado($status)
    {
        return match($status) {
            'presente' => 'Presente',
            'ausente' => 'Ausente',
            'justificado' => 'Justificado',
            'atrasado' => 'Atrasado',
            default => ucfirst($status)
        };
    }
} 