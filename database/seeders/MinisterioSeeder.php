<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ministerio;

class MinisterioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('⛪ Criando ministérios...');

        $ministerios = [
            [
                'nome' => 'Louvor e Adoração',
                'descricao' => 'Ministério responsável pela música e adoração nos cultos',
                'cor' => '#3b82f6',
                'icone' => 'music',
                'ativo' => true
            ],
            [
                'nome' => 'Infantil',
                'descricao' => 'Ministério dedicado ao cuidado e ensino das crianças',
                'cor' => '#10b981',
                'icone' => 'child',
                'ativo' => true
            ],
            [
                'nome' => 'Jovens',
                'descricao' => 'Ministério voltado para jovens e adolescentes',
                'cor' => '#f59e0b',
                'icone' => 'users',
                'ativo' => true
            ],
            [
                'nome' => 'Intercessão',
                'descricao' => 'Ministério de oração e intercessão pela igreja',
                'cor' => '#8b5cf6',
                'icone' => 'pray',
                'ativo' => true
            ],
            [
                'nome' => 'Evangelismo',
                'descricao' => 'Ministério de evangelização e missões',
                'cor' => '#ef4444',
                'icone' => 'globe',
                'ativo' => true
            ],
            [
                'nome' => 'Ação Social',
                'descricao' => 'Ministério de assistência social e caridade',
                'cor' => '#06b6d4',
                'icone' => 'heart',
                'ativo' => true
            ],
            [
                'nome' => 'Ensino',
                'descricao' => 'Ministério responsável pela Escola Dominical e estudos bíblicos',
                'cor' => '#84cc16',
                'icone' => 'book',
                'ativo' => true
            ],
            [
                'nome' => 'Hospitalidade',
                'descricao' => 'Ministério de recepção e acolhimento',
                'cor' => '#f97316',
                'icone' => 'smile',
                'ativo' => true
            ],
            [
                'nome' => 'Tecnologia',
                'descricao' => 'Ministério de tecnologia e mídia',
                'cor' => '#6366f1',
                'icone' => 'monitor',
                'ativo' => true
            ],
            [
                'nome' => 'Finanças',
                'descricao' => 'Ministério de gestão financeira da igreja',
                'cor' => '#22c55e',
                'icone' => 'dollar-sign',
                'ativo' => true
            ]
        ];

        foreach ($ministerios as $ministerio) {
            Ministerio::updateOrCreate(
                ['nome' => $ministerio['nome']],
                $ministerio
            );
        }

        $this->command->info('✅ Ministérios criados com sucesso');
        $this->command->info('📊 Total de ministérios: ' . count($ministerios));
        
        // Estatísticas
        $ativos = collect($ministerios)->where('ativo', true)->count();
        
        $this->command->info("✅ Ativos: {$ativos}");
    }
} 