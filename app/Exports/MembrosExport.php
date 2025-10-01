<?php

namespace App\Exports;

use App\Models\Membro;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MembrosExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Membro::with(['cargos.departamento.ministerio'])
            ->where('ativo', true)
            ->orderBy('nome')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nome',
            'Email',
            'Telefone',
            'Data de Nascimento',
            'Estado Civil',
            'Endereço',
            'Cidade',
            'Estado',
            'CEP',
            'Data do Batismo',
            'Data de Ingresso',
            'Cargos',
            'Ministérios',
            'Status',
            'Data de Criação'
        ];
    }

    public function map($membro): array
    {
        $cargos = $membro->cargos->pluck('nome')->implode(', ');
        $ministerios = $membro->cargos->map(function($cargo) {
            return $cargo->departamento->ministerio->nome;
        })->unique()->implode(', ');

        return [
            $membro->nome,
            $membro->email,
            $membro->telefone ?? 'Não informado',
            $membro->data_nascimento ? $membro->data_nascimento->format('d/m/Y') : 'Não informado',
            ucfirst($membro->estado_civil ?? 'Não informado'),
            $membro->endereco ?? 'Não informado',
            $membro->cidade ?? 'Não informado',
            $membro->estado ?? 'Não informado',
            $membro->cep ?? 'Não informado',
            $membro->data_batismo ? $membro->data_batismo->format('d/m/Y') : 'Não batizado',
            $membro->data_ingresso ? $membro->data_ingresso->format('d/m/Y') : 'Não informado',
            $cargos ?: 'Nenhum cargo',
            $ministerios ?: 'Nenhum ministério',
            $membro->ativo ? 'Ativo' : 'Inativo',
            $membro->created_at->format('d/m/Y H:i:s')
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