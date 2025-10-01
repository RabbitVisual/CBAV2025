<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NotificacoesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $notificacoes;

    public function __construct($notificacoes)
    {
        $this->notificacoes = $notificacoes;
    }

    public function collection()
    {
        return $this->notificacoes;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Título',
            'Mensagem',
            'Tipo',
            'Prioridade',
            'Destinatário Tipo',
            'Destinatário ID',
            'Agendada Para',
            'Enviada Em',
            'Lida',
            'Lida Em',
            'Criada Em',
            'Atualizada Em'
        ];
    }

    public function map($notificacao): array
    {
        return [
            $notificacao->id,
            $notificacao->titulo,
            $notificacao->mensagem,
            $notificacao->tipo,
            $notificacao->prioridade,
            $notificacao->destinatario_tipo ?? 'N/A',
            $notificacao->destinatario_id ?? 'N/A',
            $notificacao->agendada_para ? $notificacao->agendada_para->format('d/m/Y H:i:s') : 'N/A',
            $notificacao->enviada_em ? $notificacao->enviada_em->format('d/m/Y H:i:s') : 'N/A',
            $notificacao->lida ? 'Sim' : 'Não',
            $notificacao->lida_em ? $notificacao->lida_em->format('d/m/Y H:i:s') : 'N/A',
            $notificacao->created_at->format('d/m/Y H:i:s'),
            $notificacao->updated_at->format('d/m/Y H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E3F2FD']
                ]
            ]
        ];
    }
} 