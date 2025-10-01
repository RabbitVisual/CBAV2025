<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PautaConselho;
use App\Models\Conselho;
use App\Models\User;

class ConselhoPautaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📋 Criando pautas do conselho demonstrativas...');

        // Obter conselhos
        $conselhos = Conselho::all();
        $admin = User::where('email', 'admin@cbav.com')->first();
        $pastor = User::where('email', 'pastor@cbav.com')->first();
        $tesoureiro = User::where('email', 'tesoureiro@cbav.com')->first();

        if ($conselhos->isEmpty()) {
            $this->command->warn('⚠️ Nenhum conselho encontrado. Criando pautas sem associação...');
            return;
        }

        $pautas = [
            [
                'conselho_id' => $conselhos->where('titulo', 'Reunião Ordinária - Janeiro 2024')->first()->id,
                'titulo' => 'Aprovação do Orçamento Anual 2024',
                'descricao' => 'Discussão e aprovação do orçamento anual da igreja para 2024',
                'tipo' => 'deliberativo',
                'prioridade' => 'alta',
                'ordem' => 1,
                'tempo_estimado' => 30,
                'responsavel_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'status' => 'aprovado',
                'observacoes' => 'Orçamento aprovado por unanimidade',
                'decisao_final' => 'Aprovado por unanimidade',
                'data_decisao' => '2024-01-15 20:30:00'
            ],
            [
                'conselho_id' => $conselhos->where('titulo', 'Reunião Ordinária - Janeiro 2024')->first()->id,
                'titulo' => 'Eleição de Líderes de Ministérios',
                'descricao' => 'Eleição de novos líderes para os ministérios da igreja',
                'tipo' => 'deliberativo',
                'prioridade' => 'alta',
                'ordem' => 2,
                'tempo_estimado' => 45,
                'responsavel_id' => $pastor ? $pastor->id : $admin->id,
                'status' => 'aprovado',
                'observacoes' => 'Todos os líderes eleitos e aprovados',
                'decisao_final' => 'Todos os líderes eleitos e aprovados',
                'data_decisao' => '2024-01-15 21:00:00'
            ],
            [
                'conselho_id' => $conselhos->where('titulo', 'Reunião Ordinária - Janeiro 2024')->first()->id,
                'titulo' => 'Discussão sobre Reforma do Templo',
                'descricao' => 'Discussão sobre a necessidade de reforma do templo e possíveis soluções',
                'tipo' => 'informativo',
                'prioridade' => 'media',
                'ordem' => 3,
                'tempo_estimado' => 20,
                'responsavel_id' => $admin->id,
                'status' => 'pendente',
                'observacoes' => 'Necessário mais estudos técnicos'
            ],
            [
                'conselho_id' => $conselhos->where('titulo', 'Reunião Extraordinária - Fevereiro 2024')->first()->id,
                'titulo' => 'Campanha de Reforma do Templo',
                'descricao' => 'Aprovação e planejamento da campanha para reforma do templo',
                'tipo' => 'deliberativo',
                'prioridade' => 'alta',
                'ordem' => 1,
                'tempo_estimado' => 40,
                'responsavel_id' => $pastor ? $pastor->id : $admin->id,
                'status' => 'aprovado',
                'observacoes' => 'Campanha aprovada e iniciada',
                'decisao_final' => 'Campanha aprovada e iniciada',
                'data_decisao' => '2024-02-10 15:30:00'
            ],
            [
                'conselho_id' => $conselhos->where('titulo', 'Reunião Extraordinária - Fevereiro 2024')->first()->id,
                'titulo' => 'Aprovação de Novos Membros',
                'descricao' => 'Aprovação de novos membros que solicitaram adesão à igreja',
                'tipo' => 'deliberativo',
                'prioridade' => 'media',
                'ordem' => 2,
                'tempo_estimado' => 25,
                'responsavel_id' => $pastor ? $pastor->id : $admin->id,
                'status' => 'aprovado',
                'observacoes' => 'Todos os membros aprovados',
                'decisao_final' => 'Todos os membros aprovados',
                'data_decisao' => '2024-02-10 16:00:00'
            ],
            [
                'conselho_id' => $conselhos->where('titulo', 'Reunião Ordinária - Março 2024')->first()->id,
                'titulo' => 'Relatório Financeiro Mensal',
                'descricao' => 'Apresentação e análise do relatório financeiro do mês de fevereiro',
                'tipo' => 'informativo',
                'prioridade' => 'alta',
                'ordem' => 1,
                'tempo_estimado' => 20,
                'responsavel_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'status' => 'aprovado',
                'observacoes' => 'Relatório aprovado',
                'decisao_final' => 'Relatório aprovado',
                'data_decisao' => '2024-03-15 20:15:00'
            ],
            [
                'conselho_id' => $conselhos->where('titulo', 'Reunião Ordinária - Março 2024')->first()->id,
                'titulo' => 'Aprovação de Eventos',
                'descricao' => 'Aprovação de eventos programados para o próximo trimestre',
                'tipo' => 'deliberativo',
                'prioridade' => 'media',
                'ordem' => 2,
                'tempo_estimado' => 30,
                'responsavel_id' => $admin->id,
                'status' => 'aprovado',
                'observacoes' => 'Todos os eventos aprovados',
                'decisao_final' => 'Todos os eventos aprovados',
                'data_decisao' => '2024-03-15 20:45:00'
            ],
            [
                'conselho_id' => $conselhos->where('titulo', 'Reunião Ordinária - Março 2024')->first()->id,
                'titulo' => 'Discussão sobre Escola Dominical',
                'descricao' => 'Discussão sobre melhorias na Escola Dominical e novos professores',
                'tipo' => 'deliberativo',
                'prioridade' => 'media',
                'ordem' => 3,
                'tempo_estimado' => 25,
                'responsavel_id' => $pastor ? $pastor->id : $admin->id,
                'status' => 'aprovado',
                'observacoes' => 'Melhorias aprovadas',
                'decisao_final' => 'Melhorias aprovadas',
                'data_decisao' => '2024-03-15 21:00:00'
            ],
            [
                'conselho_id' => $conselhos->where('titulo', 'Reunião Ordinária - Abril 2024')->first()->id,
                'titulo' => 'Avaliação dos Ministérios',
                'descricao' => 'Avaliação do desempenho dos ministérios no primeiro trimestre',
                'tipo' => 'informativo',
                'prioridade' => 'alta',
                'ordem' => 1,
                'tempo_estimado' => 30,
                'responsavel_id' => $pastor ? $pastor->id : $admin->id,
                'status' => 'pendente',
                'observacoes' => 'Aguardando reunião'
            ],
            [
                'conselho_id' => $conselhos->where('titulo', 'Reunião Ordinária - Abril 2024')->first()->id,
                'titulo' => 'Planejamento para o Segundo Trimestre',
                'descricao' => 'Planejamento de atividades e eventos para o segundo trimestre',
                'tipo' => 'deliberativo',
                'prioridade' => 'alta',
                'ordem' => 2,
                'tempo_estimado' => 40,
                'responsavel_id' => $admin->id,
                'status' => 'pendente',
                'observacoes' => 'Aguardando reunião'
            ],
            [
                'conselho_id' => $conselhos->where('titulo', 'Reunião Ordinária - Abril 2024')->first()->id,
                'titulo' => 'Discussão sobre Evangelismo',
                'descricao' => 'Discussão sobre estratégias de evangelismo e missões',
                'tipo' => 'deliberativo',
                'prioridade' => 'media',
                'ordem' => 3,
                'tempo_estimado' => 25,
                'responsavel_id' => $pastor ? $pastor->id : $admin->id,
                'status' => 'pendente',
                'observacoes' => 'Aguardando reunião'
            ]
        ];

        foreach ($pautas as $pauta) {
            PautaConselho::create($pauta);
        }

        $this->command->info('✅ Pautas do conselho demonstrativas criadas com sucesso');
        $this->command->info('📊 Total de pautas: ' . count($pautas));
        
        // Estatísticas
        $aprovadas = collect($pautas)->where('status', 'aprovado')->count();
        $pendentes = collect($pautas)->where('status', 'pendente')->count();
        $deliberativas = collect($pautas)->where('tipo', 'deliberativo')->count();
        $informativas = collect($pautas)->where('tipo', 'informativo')->count();
        
        $this->command->info("✅ Aprovadas: {$aprovadas}");
        $this->command->info("📅 Pendentes: {$pendentes}");
        $this->command->info("📊 Deliberativas: {$deliberativas}");
        $this->command->info("📊 Informativas: {$informativas}");
    }
} 