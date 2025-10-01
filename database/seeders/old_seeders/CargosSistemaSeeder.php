<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cargo;
use App\Models\Departamento;

class CargosSistemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar um departamento para cargos do sistema se não existir
        $departamentoSistema = Departamento::firstOrCreate(
            ['nome' => 'Sistema'],
            [
                'nome' => 'Sistema',
                'descricao' => 'Cargos do sistema administrativo',
                'ministerio_id' => 1, // Assumindo que existe um ministério com ID 1
                'ativo' => true,
            ]
        );

        $cargosSistema = [
            [
                'nome' => 'Super Administrador',
                'descricao' => 'Acesso total ao sistema',
                'departamento_id' => $departamentoSistema->id,
                'sistema' => true,
                'ativo' => true,
            ],
            [
                'nome' => 'Pastor',
                'descricao' => 'Liderança pastoral da igreja',
                'departamento_id' => $departamentoSistema->id,
                'sistema' => true,
                'ativo' => true,
            ],
            [
                'nome' => 'Tesoureiro',
                'descricao' => 'Responsável pela tesouraria',
                'departamento_id' => $departamentoSistema->id,
                'sistema' => true,
                'ativo' => true,
            ],
            [
                'nome' => 'Líder de Ministério',
                'descricao' => 'Líder de ministério específico',
                'departamento_id' => $departamentoSistema->id,
                'sistema' => true,
                'ativo' => true,
            ],
            [
                'nome' => 'Secretário',
                'descricao' => 'Secretário administrativo',
                'departamento_id' => $departamentoSistema->id,
                'sistema' => true,
                'ativo' => true,
            ],
            [
                'nome' => 'Músico',
                'descricao' => 'Músico do ministério de música',
                'departamento_id' => $departamentoSistema->id,
                'sistema' => true,
                'ativo' => true,
            ],
            [
                'nome' => 'Professor',
                'descricao' => 'Professor da escola dominical',
                'departamento_id' => $departamentoSistema->id,
                'sistema' => true,
                'ativo' => true,
            ],
            [
                'nome' => 'Diácono',
                'descricao' => 'Diácono da igreja',
                'departamento_id' => $departamentoSistema->id,
                'sistema' => true,
                'ativo' => true,
            ],
            [
                'nome' => 'Membro',
                'descricao' => 'Membro da igreja com acesso ao painel',
                'departamento_id' => $departamentoSistema->id,
                'sistema' => true,
                'ativo' => true,
            ],
        ];

        foreach ($cargosSistema as $cargo) {
            Cargo::updateOrCreate(
                ['nome' => $cargo['nome']],
                $cargo
            );
        }
    }
}
