<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PermissoesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $cargos;
    protected $usuarios;

    public function __construct($cargos, $usuarios)
    {
        $this->cargos = $cargos;
        $this->usuarios = $usuarios;
    }

    public function collection()
    {
        $data = [];
        
        // Dados dos cargos
        foreach ($this->cargos as $cargo) {
            $data[] = [
                'tipo' => 'Cargo',
                'nome' => $cargo->nome,
                'descricao' => $cargo->descricao ?? 'N/A',
                'usuarios' => $cargo->membros->count(),
                'permissoes' => $cargo->permissions->pluck('name')->implode(', ') ?: 'Nenhuma',
                'status' => $cargo->ativo ? 'Ativo' : 'Inativo'
            ];
        }
        
        // Dados dos usuários
        foreach ($this->usuarios as $usuario) {
            $data[] = [
                'tipo' => 'Usuário',
                'nome' => $usuario->name,
                'email' => $usuario->email,
                'cargos' => $usuario->cargos->pluck('nome')->implode(', ') ?: 'Nenhum',
                'roles' => $usuario->roles->pluck('name')->implode(', ') ?: 'Nenhum',
                'permissoes_diretas' => $usuario->permissions->pluck('name')->implode(', ') ?: 'Nenhuma'
            ];
        }
        
        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Tipo',
            'Nome',
            'Descrição/Email',
            'Cargos/Usuários',
            'Permissões/Roles',
            'Status/Permissões Diretas'
        ];
    }

    public function map($row): array
    {
        return [
            $row['tipo'],
            $row['nome'],
            $row['descricao'] ?? $row['email'] ?? 'N/A',
            $row['usuarios'] ?? $row['cargos'] ?? 'N/A',
            $row['permissoes'] ?? $row['roles'] ?? 'N/A',
            $row['status'] ?? $row['permissoes_diretas'] ?? 'N/A'
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

    public function title(): string
    {
        return 'Relatório de Permissões';
    }
} 