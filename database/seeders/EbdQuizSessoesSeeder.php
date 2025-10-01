<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EbdQuizSessao;
use App\Models\User;

class EbdQuizSessoesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎯 Criando sessões de quiz da EBD demonstrativas...');

        // Obter usuários para associar as sessões
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('⚠️ Nenhum usuário encontrado. Criando sessões sem associação...');
            return;
        }

        $sessoes = [
            // Sessões do Admin
            [
                'user_id' => $users->first()->id,
                'nivel' => 'facil',
                'categoria' => 'geral',
                'total_perguntas' => 10,
                'acertos' => 9,
                'pontuacao_total' => 90,
                'percentual' => 90.00,
                'iniciado_em' => '2024-01-15 10:00:00',
                'finalizado_em' => '2024-01-15 10:15:00'
            ],
            [
                'user_id' => $users->first()->id,
                'nivel' => 'medio',
                'categoria' => 'antigo_testamento',
                'total_perguntas' => 15,
                'acertos' => 12,
                'pontuacao_total' => 240,
                'percentual' => 80.00,
                'iniciado_em' => '2024-01-16 14:30:00',
                'finalizado_em' => '2024-01-16 15:00:00'
            ],
            [
                'user_id' => $users->first()->id,
                'nivel' => 'dificil',
                'categoria' => 'personagens',
                'total_perguntas' => 20,
                'acertos' => 14,
                'pontuacao_total' => 420,
                'percentual' => 70.00,
                'iniciado_em' => '2024-01-17 16:00:00',
                'finalizado_em' => '2024-01-17 16:45:00'
            ],

            // Sessões de outros usuários (se existirem)
            [
                'user_id' => $users->skip(1)->first()?->id ?? $users->first()->id,
                'nivel' => 'facil',
                'categoria' => 'novo_testamento',
                'total_perguntas' => 10,
                'acertos' => 8,
                'pontuacao_total' => 80,
                'percentual' => 80.00,
                'iniciado_em' => '2024-01-18 09:00:00',
                'finalizado_em' => '2024-01-18 09:12:00'
            ],
            [
                'user_id' => $users->skip(1)->first()?->id ?? $users->first()->id,
                'nivel' => 'medio',
                'categoria' => 'milagres',
                'total_perguntas' => 12,
                'acertos' => 10,
                'pontuacao_total' => 200,
                'percentual' => 83.33,
                'iniciado_em' => '2024-01-19 11:00:00',
                'finalizado_em' => '2024-01-19 11:25:00'
            ],
            [
                'user_id' => $users->skip(1)->first()?->id ?? $users->first()->id,
                'nivel' => 'dificil',
                'categoria' => 'apostolos',
                'total_perguntas' => 18,
                'acertos' => 11,
                'pontuacao_total' => 330,
                'percentual' => 61.11,
                'iniciado_em' => '2024-01-20 15:30:00',
                'finalizado_em' => '2024-01-20 16:15:00'
            ],

            // Sessões históricas (2023)
            [
                'user_id' => $users->first()->id,
                'nivel' => 'facil',
                'categoria' => 'parabolas',
                'total_perguntas' => 8,
                'acertos' => 7,
                'pontuacao_total' => 70,
                'percentual' => 87.50,
                'iniciado_em' => '2023-12-10 10:00:00',
                'finalizado_em' => '2023-12-10 10:08:00'
            ],
            [
                'user_id' => $users->first()->id,
                'nivel' => 'medio',
                'categoria' => 'profetas',
                'total_perguntas' => 15,
                'acertos' => 13,
                'pontuacao_total' => 260,
                'percentual' => 86.67,
                'iniciado_em' => '2023-12-15 14:00:00',
                'finalizado_em' => '2023-12-15 14:30:00'
            ],
            [
                'user_id' => $users->skip(1)->first()?->id ?? $users->first()->id,
                'nivel' => 'dificil',
                'categoria' => 'geral',
                'total_perguntas' => 25,
                'acertos' => 16,
                'pontuacao_total' => 480,
                'percentual' => 64.00,
                'iniciado_em' => '2023-12-20 16:00:00',
                'finalizado_em' => '2023-12-20 17:00:00'
            ],

            // Sessões recentes (2024)
            [
                'user_id' => $users->first()->id,
                'nivel' => 'facil',
                'categoria' => 'antigo_testamento',
                'total_perguntas' => 10,
                'acertos' => 10,
                'pontuacao_total' => 100,
                'percentual' => 100.00,
                'iniciado_em' => '2024-02-01 09:00:00',
                'finalizado_em' => '2024-02-01 09:10:00'
            ],
            [
                'user_id' => $users->first()->id,
                'nivel' => 'medio',
                'categoria' => 'novo_testamento',
                'total_perguntas' => 15,
                'acertos' => 13,
                'pontuacao_total' => 260,
                'percentual' => 86.67,
                'iniciado_em' => '2024-02-05 14:00:00',
                'finalizado_em' => '2024-02-05 14:25:00'
            ],
            [
                'user_id' => $users->skip(1)->first()?->id ?? $users->first()->id,
                'nivel' => 'dificil',
                'categoria' => 'personagens',
                'total_perguntas' => 20,
                'acertos' => 15,
                'pontuacao_total' => 450,
                'percentual' => 75.00,
                'iniciado_em' => '2024-02-10 16:00:00',
                'finalizado_em' => '2024-02-10 16:50:00'
            ],
            [
                'user_id' => $users->skip(1)->first()?->id ?? $users->first()->id,
                'nivel' => 'facil',
                'categoria' => 'milagres',
                'total_perguntas' => 10,
                'acertos' => 9,
                'pontuacao_total' => 90,
                'percentual' => 90.00,
                'iniciado_em' => '2024-02-15 11:00:00',
                'finalizado_em' => '2024-02-15 11:12:00'
            ],
            [
                'user_id' => $users->first()->id,
                'nivel' => 'medio',
                'categoria' => 'parabolas',
                'total_perguntas' => 12,
                'acertos' => 11,
                'pontuacao_total' => 220,
                'percentual' => 91.67,
                'iniciado_em' => '2024-02-20 15:00:00',
                'finalizado_em' => '2024-02-20 15:20:00'
            ],
            [
                'user_id' => $users->skip(1)->first()?->id ?? $users->first()->id,
                'nivel' => 'dificil',
                'categoria' => 'apostolos',
                'total_perguntas' => 18,
                'acertos' => 12,
                'pontuacao_total' => 360,
                'percentual' => 66.67,
                'iniciado_em' => '2024-02-25 17:00:00',
                'finalizado_em' => '2024-02-25 17:45:00'
            ]
        ];

        foreach ($sessoes as $sessao) {
            EbdQuizSessao::create($sessao);
        }

        $this->command->info('✅ Sessões de quiz da EBD demonstrativas criadas com sucesso');
        $this->command->info('📊 Total de sessões: ' . count($sessoes));
        
        // Estatísticas
        $facil = collect($sessoes)->where('nivel', 'facil')->count();
        $medio = collect($sessoes)->where('nivel', 'medio')->count();
        $dificil = collect($sessoes)->where('nivel', 'dificil')->count();
        
        $geral = collect($sessoes)->where('categoria', 'geral')->count();
        $antigo_testamento = collect($sessoes)->where('categoria', 'antigo_testamento')->count();
        $novo_testamento = collect($sessoes)->where('categoria', 'novo_testamento')->count();
        $personagens = collect($sessoes)->where('categoria', 'personagens')->count();
        $milagres = collect($sessoes)->where('categoria', 'milagres')->count();
        $parabolas = collect($sessoes)->where('categoria', 'parabolas')->count();
        $profetas = collect($sessoes)->where('categoria', 'profetas')->count();
        $apostolos = collect($sessoes)->where('categoria', 'apostolos')->count();
        
        $media_percentual = collect($sessoes)->avg('percentual');
        $total_pontuacao = collect($sessoes)->sum('pontuacao_total');
        
        $this->command->info("🟢 Fácil: {$facil}");
        $this->command->info("🟡 Médio: {$medio}");
        $this->command->info("🔴 Difícil: {$dificil}");
        $this->command->info("📖 Geral: {$geral}");
        $this->command->info("📖 Antigo Testamento: {$antigo_testamento}");
        $this->command->info("📖 Novo Testamento: {$novo_testamento}");
        $this->command->info("👥 Personagens: {$personagens}");
        $this->command->info("✨ Milagres: {$milagres}");
        $this->command->info("📚 Parábolas: {$parabolas}");
        $this->command->info("🔮 Profetas: {$profetas}");
        $this->command->info("✝️ Apóstolos: {$apostolos}");
        $this->command->info("📊 Média de acerto: " . number_format($media_percentual, 1) . "%");
        $this->command->info("🏆 Total de pontos: {$total_pontuacao}");
    }
} 