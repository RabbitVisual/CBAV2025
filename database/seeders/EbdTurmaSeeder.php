<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EbdTurma;

class EbdTurmaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👥 Criando turmas da EBD demonstrativas...');

        $turmas = [
            [
                'nome' => 'Berçário',
                'descricao' => 'Turma para crianças de 0 a 2 anos',
                'faixa_etaria' => '0-2 anos',
                'cor' => '#FF6B6B',
                'capacidade_maxima' => 15,
                'ativo' => true
            ],
            [
                'nome' => 'Maternal',
                'descricao' => 'Turma para crianças de 3 a 4 anos',
                'faixa_etaria' => '3-4 anos',
                'cor' => '#4ECDC4',
                'capacidade_maxima' => 20,
                'ativo' => true
            ],
            [
                'nome' => 'Jardim',
                'descricao' => 'Turma para crianças de 5 a 6 anos',
                'faixa_etaria' => '5-6 anos',
                'cor' => '#45B7D1',
                'capacidade_maxima' => 25,
                'ativo' => true
            ],
            [
                'nome' => 'Primários',
                'descricao' => 'Turma para crianças de 7 a 9 anos',
                'faixa_etaria' => '7-9 anos',
                'cor' => '#96CEB4',
                'capacidade_maxima' => 30,
                'ativo' => true
            ],
            [
                'nome' => 'Juniores',
                'descricao' => 'Turma para crianças de 10 a 12 anos',
                'faixa_etaria' => '10-12 anos',
                'cor' => '#FFEAA7',
                'capacidade_maxima' => 25,
                'ativo' => true
            ],
            [
                'nome' => 'Adolescentes',
                'descricao' => 'Turma para adolescentes de 13 a 17 anos',
                'faixa_etaria' => '13-17 anos',
                'cor' => '#DDA0DD',
                'capacidade_maxima' => 30,
                'ativo' => true
            ],
            [
                'nome' => 'Jovens',
                'descricao' => 'Turma para jovens de 18 a 25 anos',
                'faixa_etaria' => '18-25 anos',
                'cor' => '#98D8C8',
                'capacidade_maxima' => 35,
                'ativo' => true
            ],
            [
                'nome' => 'Adultos',
                'descricao' => 'Turma para adultos de 26 a 59 anos',
                'faixa_etaria' => '26-59 anos',
                'cor' => '#3b82f6',
                'capacidade_maxima' => 40,
                'ativo' => true
            ],
            [
                'nome' => 'Senhores',
                'descricao' => 'Turma para senhores de 60 anos ou mais',
                'faixa_etaria' => '60+ anos',
                'cor' => '#F7DC6F',
                'capacidade_maxima' => 30,
                'ativo' => true
            ],
            [
                'nome' => 'Novos Convertidos',
                'descricao' => 'Turma especial para novos convertidos',
                'faixa_etaria' => 'Todas as idades',
                'cor' => '#BB8FCE',
                'capacidade_maxima' => 20,
                'ativo' => true
            ]
        ];

        foreach ($turmas as $turma) {
            EbdTurma::updateOrCreate(
                ['nome' => $turma['nome']],
                $turma
            );
        }

        $this->command->info('✅ Turmas da EBD demonstrativas criadas com sucesso');
        $this->command->info('📊 Total de turmas: ' . count($turmas));
        
        // Estatísticas
        $ativas = collect($turmas)->where('ativo', true)->count();
        $infantil = collect($turmas)->whereIn('nome', ['Berçário', 'Maternal', 'Jardim', 'Primários', 'Juniores'])->count();
        $adultos = collect($turmas)->whereIn('nome', ['Adultos', 'Senhores'])->count();
        $especiais = collect($turmas)->whereIn('nome', ['Adolescentes', 'Jovens', 'Novos Convertidos'])->count();
        
        $this->command->info("✅ Ativas: {$ativas}");
        $this->command->info("👶 Infantil: {$infantil}");
        $this->command->info("👨‍🦳 Adultos: {$adultos}");
        $this->command->info("🎯 Especiais: {$especiais}");
    }
} 