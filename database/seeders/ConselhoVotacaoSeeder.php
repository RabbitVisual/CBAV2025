<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VotacaoConselho;
use App\Models\PautaConselho;
use App\Models\Conselho;

class ConselhoVotacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🗳️ Criando votações do conselho demonstrativas...');

        // Obter conselhos e pautas para associar as votações
        $conselhos = Conselho::all();
        $pautas = PautaConselho::all();
        
        if ($conselhos->isEmpty()) {
            $this->command->warn('⚠️ Nenhum conselho encontrado. Criando votações sem associação...');
            return;
        }

        $conselho = $conselhos->first();
        $pauta = $pautas->first();
        
        $votacoes = [
            // Votação sobre Orçamento Anual 2024
            [
                'conselho_id' => $conselho->id,
                'pauta_id' => $pauta ? $pauta->id : null,
                'titulo' => 'Aprovação do Orçamento Anual 2024',
                'descricao' => 'Votação para aprovação do orçamento anual da igreja para o ano de 2024',
                'tipo_votacao' => 'aprovacao_rejeicao',
                'opcoes_votacao' => json_encode(['Aprovar', 'Rejeitar']),
                'votos_favoraveis' => 12,
                'votos_contrarios' => 0,
                'votos_abstencao' => 0,
                'total_votos' => 12,
                'quorum_necessario' => 8,
                'status' => 'finalizada',
                'resultado' => 'aprovado',
                'observacoes' => 'Orçamento aprovado por unanimidade',
                'data_inicio' => '2024-01-15 20:00:00',
                'data_fim' => '2024-01-15 20:30:00',
                'tempo_limite' => 30,
                'voto_secreto' => false
            ],
            [
                'conselho_id' => $conselho->id,
                'pauta_id' => $pauta ? $pauta->id : null,
                'titulo' => 'Eleição de Líderes de Ministérios',
                'descricao' => 'Votação para eleição de novos líderes para os ministérios da igreja',
                'tipo_votacao' => 'multipla_escolha',
                'opcoes_votacao' => json_encode(['João Silva', 'Maria Santos', 'Pedro Oliveira', 'Ana Costa']),
                'votos_favoraveis' => 10,
                'votos_contrarios' => 1,
                'votos_abstencao' => 1,
                'total_votos' => 12,
                'quorum_necessario' => 8,
                'status' => 'finalizada',
                'resultado' => 'aprovado',
                'observacoes' => 'Todos os líderes eleitos e aprovados',
                'data_inicio' => '2024-01-15 20:30:00',
                'data_fim' => '2024-01-15 21:00:00',
                'tempo_limite' => 30,
                'voto_secreto' => true
            ],
            [
                'conselho_id' => $conselho->id,
                'pauta_id' => $pauta ? $pauta->id : null,
                'titulo' => 'Discussão sobre Reforma do Templo',
                'descricao' => 'Votação sobre a necessidade de reforma do templo e possíveis soluções',
                'tipo_votacao' => 'aprovacao_rejeicao',
                'opcoes_votacao' => json_encode(['Aprovar', 'Rejeitar']),
                'votos_favoraveis' => 8,
                'votos_contrarios' => 2,
                'votos_abstencao' => 2,
                'total_votos' => 12,
                'quorum_necessario' => 8,
                'status' => 'finalizada',
                'resultado' => 'aprovado',
                'observacoes' => 'Aprovado com ressalvas, necessário mais estudos técnicos',
                'data_inicio' => '2024-01-15 21:00:00',
                'data_fim' => '2024-01-15 21:15:00',
                'tempo_limite' => 15,
                'voto_secreto' => false
            ],
            [
                'conselho_id' => $conselho->id,
                'pauta_id' => $pauta ? $pauta->id : null,
                'titulo' => 'Campanha de Reforma do Templo',
                'descricao' => 'Votação para aprovação e planejamento da campanha para reforma do templo',
                'tipo_votacao' => 'aprovacao_rejeicao',
                'opcoes_votacao' => json_encode(['Aprovar', 'Rejeitar']),
                'votos_favoraveis' => 14,
                'votos_contrarios' => 0,
                'votos_abstencao' => 1,
                'total_votos' => 15,
                'quorum_necessario' => 10,
                'status' => 'finalizada',
                'resultado' => 'aprovado',
                'observacoes' => 'Campanha aprovada e iniciada com sucesso',
                'data_inicio' => '2024-02-10 15:00:00',
                'data_fim' => '2024-02-10 15:30:00',
                'tempo_limite' => 30,
                'voto_secreto' => false
            ],
            [
                'conselho_id' => $conselho->id,
                'pauta_id' => $pauta ? $pauta->id : null,
                'titulo' => 'Aprovação de Novos Membros',
                'descricao' => 'Votação para aprovação de novos membros que solicitaram adesão à igreja',
                'tipo_votacao' => 'multipla_escolha',
                'opcoes_votacao' => json_encode(['Aprovar Todos', 'Aprovar Parcialmente', 'Rejeitar']),
                'votos_favoraveis' => 15,
                'votos_contrarios' => 0,
                'votos_abstencao' => 0,
                'total_votos' => 15,
                'quorum_necessario' => 10,
                'status' => 'finalizada',
                'resultado' => 'aprovado',
                'observacoes' => 'Todos os membros aprovados por unanimidade',
                'data_inicio' => '2024-02-10 15:30:00',
                'data_fim' => '2024-02-10 16:00:00',
                'tempo_limite' => 30,
                'voto_secreto' => true
            ],
            [
                'conselho_id' => $conselho->id,
                'pauta_id' => $pauta ? $pauta->id : null,
                'titulo' => 'Relatório Financeiro Mensal',
                'descricao' => 'Votação para aprovação do relatório financeiro do mês de fevereiro',
                'tipo_votacao' => 'aprovacao_rejeicao',
                'opcoes_votacao' => json_encode(['Aprovar', 'Rejeitar']),
                'votos_favoraveis' => 12,
                'votos_contrarios' => 0,
                'votos_abstencao' => 0,
                'total_votos' => 12,
                'quorum_necessario' => 8,
                'status' => 'finalizada',
                'resultado' => 'aprovado',
                'observacoes' => 'Relatório aprovado por unanimidade',
                'data_inicio' => '2024-03-15 20:00:00',
                'data_fim' => '2024-03-15 20:15:00',
                'tempo_limite' => 15,
                'voto_secreto' => false
            ],
            [
                'conselho_id' => $conselho->id,
                'pauta_id' => $pauta ? $pauta->id : null,
                'titulo' => 'Aprovação de Eventos',
                'descricao' => 'Votação para aprovação de eventos programados para o próximo trimestre',
                'tipo_votacao' => 'multipla_escolha',
                'opcoes_votacao' => json_encode(['Aprovar Todos', 'Aprovar Parcialmente', 'Rejeitar']),
                'votos_favoraveis' => 11,
                'votos_contrarios' => 0,
                'votos_abstencao' => 1,
                'total_votos' => 12,
                'quorum_necessario' => 8,
                'status' => 'finalizada',
                'resultado' => 'aprovado',
                'observacoes' => 'Todos os eventos aprovados',
                'data_inicio' => '2024-03-15 20:15:00',
                'data_fim' => '2024-03-15 20:45:00',
                'tempo_limite' => 30,
                'voto_secreto' => false
            ],
            [
                'conselho_id' => $conselho->id,
                'pauta_id' => $pauta ? $pauta->id : null,
                'titulo' => 'Discussão sobre Escola Dominical',
                'descricao' => 'Votação sobre melhorias na Escola Dominical e novos professores',
                'tipo_votacao' => 'aprovacao_rejeicao',
                'opcoes_votacao' => json_encode(['Aprovar', 'Rejeitar']),
                'votos_favoraveis' => 12,
                'votos_contrarios' => 0,
                'votos_abstencao' => 0,
                'total_votos' => 12,
                'quorum_necessario' => 8,
                'status' => 'finalizada',
                'resultado' => 'aprovado',
                'observacoes' => 'Melhorias aprovadas por unanimidade',
                'data_inicio' => '2024-03-15 20:45:00',
                'data_fim' => '2024-03-15 21:00:00',
                'tempo_limite' => 15,
                'voto_secreto' => false
            ],

            // Votações históricas (2023)
            [
                'conselho_id' => $conselho->id,
                'pauta_id' => null,
                'titulo' => 'Planejamento para 2024',
                'descricao' => 'Votação para aprovação do planejamento estratégico para o ano de 2024',
                'tipo_votacao' => 'aprovacao_rejeicao',
                'opcoes_votacao' => json_encode(['Aprovar', 'Rejeitar']),
                'votos_favoraveis' => 12,
                'votos_contrarios' => 0,
                'votos_abstencao' => 0,
                'total_votos' => 12,
                'quorum_necessario' => 8,
                'status' => 'finalizada',
                'resultado' => 'aprovado',
                'observacoes' => 'Planejamento aprovado por unanimidade',
                'data_inicio' => '2023-12-20 20:00:00',
                'data_fim' => '2023-12-20 20:30:00',
                'tempo_limite' => 30,
                'voto_secreto' => false
            ],
            [
                'conselho_id' => $conselho->id,
                'pauta_id' => null,
                'titulo' => 'Aprovação do Orçamento 2024',
                'descricao' => 'Votação para aprovação do orçamento para o ano de 2024',
                'tipo_votacao' => 'aprovacao_rejeicao',
                'opcoes_votacao' => json_encode(['Aprovar', 'Rejeitar']),
                'votos_favoraveis' => 11,
                'votos_contrarios' => 0,
                'votos_abstencao' => 1,
                'total_votos' => 12,
                'quorum_necessario' => 8,
                'status' => 'finalizada',
                'resultado' => 'aprovado',
                'observacoes' => 'Orçamento aprovado',
                'data_inicio' => '2023-12-20 20:30:00',
                'data_fim' => '2023-12-20 21:00:00',
                'tempo_limite' => 30,
                'voto_secreto' => false
            ],
            [
                'conselho_id' => $conselho->id,
                'pauta_id' => null,
                'titulo' => 'Eleição de Novos Conselheiros',
                'descricao' => 'Votação para eleição de novos conselheiros para o próximo ano',
                'tipo_votacao' => 'multipla_escolha',
                'opcoes_votacao' => json_encode(['João Silva', 'Maria Santos', 'Pedro Oliveira', 'Ana Costa']),
                'votos_favoraveis' => 12,
                'votos_contrarios' => 0,
                'votos_abstencao' => 0,
                'total_votos' => 12,
                'quorum_necessario' => 8,
                'status' => 'finalizada',
                'resultado' => 'aprovado',
                'observacoes' => 'Conselheiros eleitos por unanimidade',
                'data_inicio' => '2023-12-20 21:00:00',
                'data_fim' => '2023-12-20 21:30:00',
                'tempo_limite' => 30,
                'voto_secreto' => true
            ],

            // Votações pendentes (2024)
            [
                'conselho_id' => $conselho->id,
                'pauta_id' => null,
                'titulo' => 'Avaliação dos Ministérios',
                'descricao' => 'Votação para avaliação do desempenho dos ministérios no primeiro trimestre',
                'tipo_votacao' => 'escala',
                'opcoes_votacao' => json_encode(['1 - Insatisfatório', '2 - Regular', '3 - Bom', '4 - Muito Bom', '5 - Excelente']),
                'votos_favoraveis' => 0,
                'votos_contrarios' => 0,
                'votos_abstencao' => 0,
                'total_votos' => 0,
                'quorum_necessario' => 8,
                'status' => 'pendente',
                'resultado' => null,
                'observacoes' => 'Aguardando reunião',
                'data_inicio' => null,
                'data_fim' => null,
                'tempo_limite' => 30,
                'voto_secreto' => false
            ],
            [
                'conselho_id' => $conselho->id,
                'pauta_id' => null,
                'titulo' => 'Planejamento para o Segundo Trimestre',
                'descricao' => 'Votação para planejamento de atividades e eventos para o segundo trimestre',
                'tipo_votacao' => 'aprovacao_rejeicao',
                'opcoes_votacao' => json_encode(['Aprovar', 'Rejeitar']),
                'votos_favoraveis' => 0,
                'votos_contrarios' => 0,
                'votos_abstencao' => 0,
                'total_votos' => 0,
                'quorum_necessario' => 8,
                'status' => 'pendente',
                'resultado' => null,
                'observacoes' => 'Aguardando reunião',
                'data_inicio' => null,
                'data_fim' => null,
                'tempo_limite' => 30,
                'voto_secreto' => false
            ]
        ];

        foreach ($votacoes as $votacao) {
            VotacaoConselho::create($votacao);
        }

        $this->command->info('✅ Votações do conselho demonstrativas criadas com sucesso');
        $this->command->info('📊 Total de votações: ' . count($votacoes));
        
        // Estatísticas
        $finalizadas = collect($votacoes)->where('status', 'finalizada')->count();
        $pendentes = collect($votacoes)->where('status', 'pendente')->count();
        $aprovadas = collect($votacoes)->where('resultado', 'aprovado')->count();
        
        $simples = collect($votacoes)->where('tipo_votacao', 'aprovacao_rejeicao')->count();
        $multiplas = collect($votacoes)->where('tipo_votacao', 'multipla_escolha')->count();
        $escala = collect($votacoes)->where('tipo_votacao', 'escala')->count();
        
        $this->command->info("✅ Finalizadas: {$finalizadas}");
        $this->command->info("📅 Pendentes: {$pendentes}");
        $this->command->info("✅ Aprovadas: {$aprovadas}");
        $this->command->info("📊 Simples: {$simples}");
        $this->command->info("📊 Múltiplas: {$multiplas}");
        $this->command->info("📊 Escala: {$escala}");
    }
} 