<?php

namespace App\Exports;

use App\Models\Membro;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class AniversariantesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $mes;
    protected $ano;

    public function __construct($mes, $ano)
    {
        $this->mes = $mes;
        $this->ano = $ano;
    }

    public function collection()
    {
        return Membro::with(['cargos.departamento.ministerio'])
            ->where('ativo', true)
            ->whereMonth('data_nascimento', $this->mes)
            ->whereYear('data_nascimento', '<=', $this->ano)
            ->orderByRaw('DAY(data_nascimento)')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nome',
            'Data de Nascimento',
            'Idade',
            'Telefone',
            'Email',
            'Endereço',
            'Cidade',
            'Estado',
            'Cargos',
            'Ministérios',
            'Data de Ingresso',
            'Observações'
        ];
    }

    public function map($membro): array
    {
        $idade = $membro->data_nascimento ? Carbon::parse($membro->data_nascimento)->age : 'N/A';
        $cargos = $membro->cargos->pluck('nome')->implode(', ');
        $ministerios = $membro->cargos->map(function($cargo) {
            return $cargo->departamento->ministerio->nome;
        })->unique()->implode(', ');

        return [
            $membro->nome,
            $membro->data_nascimento ? $membro->data_nascimento->format('d/m/Y') : 'Não informado',
            $idade,
            $membro->telefone ?? 'Não informado',
            $membro->email,
            $membro->endereco ?? 'Não informado',
            $membro->cidade ?? 'Não informado',
            $membro->estado ?? 'Não informado',
            $cargos ?: 'Nenhum cargo',
            $ministerios ?: 'Nenhum ministério',
            $membro->data_ingresso ? $membro->data_ingresso->format('d/m/Y') : 'Não informado',
            $membro->observacoes ?? 'Nenhuma observação'
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