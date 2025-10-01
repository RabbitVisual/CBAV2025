<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsuariosExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return User::with(['roles', 'permissions'])->get();
    }

    public function headings(): array
    {
        return [
            'Nome',
            'Email',
            'Roles',
            'Permissões Diretas',
            'Email Verificado',
            'Último Login',
            'Data de Criação',
            'Status'
        ];
    }

    public function map($usuario): array
    {
        $roles = $usuario->roles->pluck('name')->implode(', ');
        $permissoes = $usuario->permissions->pluck('name')->implode(', ');
        $emailVerificado = $usuario->email_verified_at ? 'Sim' : 'Não';
        $ultimoLogin = $usuario->last_login_at ? $usuario->last_login_at->format('d/m/Y H:i:s') : 'Nunca';
        $status = $usuario->email_verified_at ? 'Ativo' : 'Pendente';

        return [
            $usuario->name,
            $usuario->email,
            $roles ?: 'Nenhum role',
            $permissoes ?: 'Nenhuma permissão direta',
            $emailVerificado,
            $ultimoLogin,
            $usuario->created_at->format('d/m/Y H:i:s'),
            $status
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