<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cargo;
use App\Models\Departamento;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👔 Criando cargos...');

        // Obter departamentos para associar aos cargos
        $departamentos = Departamento::all();
        $administrativo = $departamentos->where('nome', 'Administrativo')->first();
        $financeiro = $departamentos->where('nome', 'Financeiro')->first();
        $espiritual = $departamentos->where('nome', 'Espiritual')->first();
        $educacional = $departamentos->where('nome', 'Educacional')->first();
        $social = $departamentos->where('nome', 'Social')->first();
        $tecnologia = $departamentos->where('nome', 'Tecnologia')->first();
        $eventos = $departamentos->where('nome', 'Eventos')->first();
        $comunicacao = $departamentos->where('nome', 'Comunicação')->first();

        $cargos = [
            [
                'nome' => 'Pastor',
                'descricao' => 'Líder espiritual da igreja',
                'departamento_id' => $espiritual->id,
                'ativo' => true
            ],
            [
                'nome' => 'Pastor Auxiliar',
                'descricao' => 'Pastor auxiliar na liderança espiritual',
                'departamento_id' => $espiritual->id,
                'ativo' => true
            ],
            [
                'nome' => 'Tesoureiro',
                'descricao' => 'Responsável pela gestão financeira da igreja',
                'departamento_id' => $financeiro->id,
                'ativo' => true
            ],
            [
                'nome' => 'Secretário',
                'descricao' => 'Responsável pela documentação e registros da igreja',
                'departamento_id' => $administrativo->id,
                'ativo' => true
            ],
            [
                'nome' => 'Líder de Ministério',
                'descricao' => 'Líder de um ministério específico',
                'departamento_id' => $espiritual->id,
                'ativo' => true
            ],
            [
                'nome' => 'Coordenador',
                'descricao' => 'Coordenador de área ou departamento',
                'departamento_id' => $administrativo->id,
                'ativo' => true
            ],
            [
                'nome' => 'Professor EBD',
                'descricao' => 'Professor da Escola Bíblica Dominical',
                'departamento_id' => $educacional->id,
                'ativo' => true
            ],
            [
                'nome' => 'Músico',
                'descricao' => 'Músico do ministério de louvor',
                'departamento_id' => $eventos->id,
                'ativo' => true
            ],
            [
                'nome' => 'Cantor',
                'descricao' => 'Cantor do ministério de louvor',
                'departamento_id' => $eventos->id,
                'ativo' => true
            ],
            [
                'nome' => 'Intercessor',
                'descricao' => 'Membro do ministério de intercessão',
                'departamento_id' => $espiritual->id,
                'ativo' => true
            ],
            [
                'nome' => 'Evangelista',
                'descricao' => 'Membro do ministério de evangelismo',
                'departamento_id' => $social->id,
                'ativo' => true
            ],
            [
                'nome' => 'Auxiliar',
                'descricao' => 'Auxiliar em diversos ministérios',
                'departamento_id' => $administrativo->id,
                'ativo' => true
            ],
            [
                'nome' => 'Membro',
                'descricao' => 'Membro da igreja',
                'departamento_id' => $espiritual->id,
                'ativo' => true
            ],
            [
                'nome' => 'Visitante',
                'descricao' => 'Visitante da igreja',
                'departamento_id' => $espiritual->id,
                'ativo' => true
            ],
            [
                'nome' => 'Diácono',
                'descricao' => 'Diácono da igreja',
                'departamento_id' => $espiritual->id,
                'ativo' => true
            ],
            [
                'nome' => 'Presbítero',
                'descricao' => 'Presbítero da igreja',
                'departamento_id' => $espiritual->id,
                'ativo' => true
            ],
            [
                'nome' => 'Conselheiro',
                'descricao' => 'Membro do conselho da igreja',
                'departamento_id' => $administrativo->id,
                'ativo' => true
            ],
            [
                'nome' => 'Líder de Jovens',
                'descricao' => 'Líder do ministério de jovens',
                'departamento_id' => $educacional->id,
                'ativo' => true
            ],
            [
                'nome' => 'Líder de Crianças',
                'descricao' => 'Líder do ministério infantil',
                'departamento_id' => $educacional->id,
                'ativo' => true
            ],
            [
                'nome' => 'Técnico de Som',
                'descricao' => 'Responsável pelo sistema de som',
                'departamento_id' => $tecnologia->id,
                'ativo' => true
            ],
            [
                'nome' => 'Operador de Mídia',
                'descricao' => 'Responsável pela mídia e transmissões',
                'departamento_id' => $tecnologia->id,
                'ativo' => true
            ],
            [
                'nome' => 'Porteiro',
                'descricao' => 'Responsável pela segurança e recepção',
                'departamento_id' => $administrativo->id,
                'ativo' => true
            ],
            [
                'nome' => 'Zelador',
                'descricao' => 'Responsável pela manutenção e limpeza',
                'departamento_id' => $administrativo->id,
                'ativo' => true
            ]
        ];

        foreach ($cargos as $cargo) {
            Cargo::updateOrCreate(
                ['nome' => $cargo['nome']],
                $cargo
            );
        }

        $this->command->info('✅ Cargos criados com sucesso');
        $this->command->info('📊 Total de cargos: ' . count($cargos));
        
        // Estatísticas
        $ativos = collect($cargos)->where('ativo', true)->count();
        
        $this->command->info("✅ Ativos: {$ativos}");
    }
} 