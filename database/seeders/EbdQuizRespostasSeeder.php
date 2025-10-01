<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EbdQuizResposta;
use App\Models\EbdQuizSessao;
use App\Models\EbdQuizPergunta;

class EbdQuizRespostasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📝 Criando respostas de quiz da EBD demonstrativas...');

        // Obter sessões e perguntas para associar as respostas
        $sessoes = EbdQuizSessao::all();
        $perguntas = EbdQuizPergunta::all();
        
        if ($sessoes->isEmpty()) {
            $this->command->warn('⚠️ Nenhuma sessão encontrada. Criando respostas sem associação...');
            return;
        }

        if ($perguntas->isEmpty()) {
            $this->command->warn('⚠️ Nenhuma pergunta encontrada. Criando respostas sem associação...');
            return;
        }

        $respostas = [];

        foreach ($sessoes as $sessao) {
            // Obter perguntas do nível e categoria da sessão
            $perguntas_sessao = $perguntas->where('nivel', $sessao->nivel)
                                         ->where('categoria', $sessao->categoria)
                                         ->take($sessao->total_perguntas);
            
            if ($perguntas_sessao->isEmpty()) {
                // Se não há perguntas específicas, usar perguntas gerais
                $perguntas_sessao = $perguntas->where('nivel', $sessao->nivel)
                                             ->take($sessao->total_perguntas);
            }

            $acertos_contador = 0;
            $pergunta_index = 0;

            foreach ($perguntas_sessao as $pergunta) {
                $pergunta_index++;
                
                // Simular resposta do usuário (maioria correta, algumas incorretas)
                $resposta_correta = $pergunta->resposta_correta;
                $resposta_dada = $resposta_correta;
                
                // Simular algumas respostas incorretas para tornar realista
                if ($acertos_contador < $sessao->acertos && $pergunta_index <= $sessao->total_perguntas) {
                    // Resposta correta
                    $correta = true;
                    $pontuacao_obtida = $pergunta->pontuacao;
                    $acertos_contador++;
                } else {
                    // Resposta incorreta (simular erro)
                    $opcoes = ['a', 'b', 'c', 'd'];
                    $resposta_dada = $opcoes[array_rand($opcoes)];
                    while ($resposta_dada === $resposta_correta) {
                        $resposta_dada = $opcoes[array_rand($opcoes)];
                    }
                    $correta = false;
                    $pontuacao_obtida = 0;
                }

                // Simular tempo de resposta (entre 5 e 60 segundos)
                $tempo_resposta = rand(5, 60);

                $respostas[] = [
                    'sessao_id' => $sessao->id,
                    'pergunta_id' => $pergunta->id,
                    'resposta_dada' => $resposta_dada,
                    'correta' => $correta,
                    'pontuacao_obtida' => $pontuacao_obtida,
                    'tempo_resposta' => $tempo_resposta
                ];
            }
        }

        foreach ($respostas as $resposta) {
            EbdQuizResposta::create($resposta);
        }

        $this->command->info('✅ Respostas de quiz da EBD demonstrativas criadas com sucesso');
        $this->command->info('📊 Total de respostas: ' . count($respostas));
        
        // Estatísticas
        $corretas = collect($respostas)->where('correta', true)->count();
        $incorretas = collect($respostas)->where('correta', false)->count();
        $total_pontuacao = collect($respostas)->sum('pontuacao_obtida');
        $media_tempo = collect($respostas)->avg('tempo_resposta');
        
        $respostas_a = collect($respostas)->where('resposta_dada', 'a')->count();
        $respostas_b = collect($respostas)->where('resposta_dada', 'b')->count();
        $respostas_c = collect($respostas)->where('resposta_dada', 'c')->count();
        $respostas_d = collect($respostas)->where('resposta_dada', 'd')->count();
        
        $this->command->info("✅ Corretas: {$corretas}");
        $this->command->info("❌ Incorretas: {$incorretas}");
        $this->command->info("🏆 Total de pontos: {$total_pontuacao}");
        $this->command->info("⏱️ Tempo médio: " . number_format($media_tempo, 1) . "s");
        $this->command->info("📊 Respostas A: {$respostas_a}");
        $this->command->info("📊 Respostas B: {$respostas_b}");
        $this->command->info("📊 Respostas C: {$respostas_c}");
        $this->command->info("📊 Respostas D: {$respostas_d}");
    }
} 