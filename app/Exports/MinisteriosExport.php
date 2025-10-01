<?php

namespace App\Exports;

use App\Models\Ministerio;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MinisteriosExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Ministerio::with(['departamentos.cargos.membros'])->where('ativo', true)->get();
    }

    public function headings(): array
    {
        return [
            'Ministério',
            'Descrição',
            'Cor',
            'Departamentos',
            'Cargos',
            'Total de Membros',
            'Status',
            'Data de Criação'
        ];
    }

    public function map($ministerio): array
    {
        $departamentos = $ministerio->departamentos->pluck('nome')->implode(', ');
        $cargos = $ministerio->departamentos->flatMap(function($departamento) {
            return $departamento->cargos->pluck('nome');
        })->unique()->implode(', ');
        
        $totalMembros = $ministerio->departamentos->flatMap(function($departamento) {
            return $departamento->cargos->flatMap(function($cargo) {
                return $cargo->membros->where('ativo', true);
            });
        })->unique('id')->count();

        return [
            $ministerio->nome,
            $ministerio->descricao ?? 'Sem descrição',
            $ministerio->cor,
            $departamentos ?: 'Nenhum departamento',
            $cargos ?: 'Nenhum cargo',
            $totalMembros,
            $ministerio->ativo ? 'Ativo' : 'Inativo',
            $ministerio->created_at->format('d/m/Y H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '8B5CF6']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ]
        ];
    }
} 