<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conselho;
use App\Models\User;

class ConselhoReuniaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏛️ Criando reuniões do conselho demonstrativas...');

        // Obter usuários para associar às reuniões
        $admin = User::where('email', 'admin@cbav.com')->first();
        $pastor = User::where('email', 'pastor@cbav.com')->first();
        $tesoureiro = User::where('email', 'tesoureiro@cbav.com')->first();

        if (!$admin) {
            $this->command->warn('⚠️ Usuário admin não encontrado. Criando reuniões sem associação...');
            return;
        }

        $reunioes = [
            [
                'titulo' => 'Reunião Ordinária - Janeiro 2024',
                'descricao' => 'Reunião ordinária do conselho para janeiro de 2024',
                'data_reuniao' => '2024-01-15',
                'hora_inicio' => '19:00',
                'hora_fim' => '21:00',
                'local' => 'Sala do Conselho',
                'tipo' => 'reuniao_ordinaria',
                'status' => 'finalizada',
                'quorum_minimo' => 8,
                'criado_por' => $admin->id,
                'presidente_id' => $pastor ? $pastor->id : $admin->id,
                'secretario_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'observacoes' => 'Reunião muito produtiva, todas as pautas foram aprovadas. Aprovação do orçamento anual, eleição de novos líderes de ministérios, discussão sobre reforma do templo. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',
                'ata_finalizada' => true,
                'data_ata_finalizada' => '2024-01-15 21:00:00'
            ],
            [
                'titulo' => 'Reunião Extraordinária - Fevereiro 2024',
                'descricao' => 'Reunião extraordinária do conselho para fevereiro de 2024',
                'data_reuniao' => '2024-02-10',
                'hora_inicio' => '14:00',
                'hora_fim' => '16:00',
                'local' => 'Sala do Conselho',
                'tipo' => 'reuniao_extraordinaria',
                'status' => 'finalizada',
                'quorum_minimo' => 8,
                'criado_por' => $admin->id,
                'presidente_id' => $pastor ? $pastor->id : $admin->id,
                'secretario_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'observacoes' => 'Decisão tomada sobre iniciar a campanha de reforma. Discussão sobre campanha de reforma do templo, aprovação de novos membros. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues, Roberto Silva Costa',
                'ata_finalizada' => true,
                'data_ata_finalizada' => '2024-02-10 16:00:00'
            ],
            [
                'titulo' => 'Reunião Ordinária - Março 2024',
                'descricao' => 'Reunião ordinária do conselho para março de 2024',
                'data_reuniao' => '2024-03-15',
                'hora_inicio' => '19:00',
                'hora_fim' => '21:00',
                'local' => 'Sala do Conselho',
                'tipo' => 'reuniao_ordinaria',
                'status' => 'finalizada',
                'quorum_minimo' => 8,
                'criado_por' => $admin->id,
                'presidente_id' => $pastor ? $pastor->id : $admin->id,
                'secretario_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'observacoes' => 'Relatórios aprovados, eventos confirmados. Relatório financeiro mensal, aprovação de eventos, discussão sobre Escola Dominical. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',
                'ata_finalizada' => true,
                'data_ata_finalizada' => '2024-03-15 21:00:00'
            ],
            [
                'titulo' => 'Reunião Ordinária - Abril 2024',
                'descricao' => 'Reunião ordinária do conselho para abril de 2024',
                'data_reuniao' => '2024-04-15',
                'hora_inicio' => '19:00',
                'hora_fim' => '21:00',
                'local' => 'Sala do Conselho',
                'tipo' => 'reuniao_ordinaria',
                'status' => 'agendada',
                'quorum_minimo' => 8,
                'criado_por' => $admin->id,
                'presidente_id' => $pastor ? $pastor->id : $admin->id,
                'secretario_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'observacoes' => 'Reunião agendada. Avaliação dos ministérios, planejamento para o segundo trimestre, discussão sobre evangelismo. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',
                'ata_finalizada' => false
            ],
            [
                'titulo' => 'Reunião Extraordinária - Dezembro 2023',
                'descricao' => 'Reunião extraordinária do conselho para dezembro de 2023',
                'data_reuniao' => '2023-12-20',
                'hora_inicio' => '19:00',
                'hora_fim' => '21:00',
                'local' => 'Sala do Conselho',
                'tipo' => 'reuniao_extraordinaria',
                'status' => 'finalizada',
                'quorum_minimo' => 8,
                'criado_por' => $admin->id,
                'presidente_id' => $pastor ? $pastor->id : $admin->id,
                'secretario_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'observacoes' => 'Planejamento aprovado para 2024. Planejamento para 2024, aprovação do orçamento, eleição de novos conselheiros. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',
                'ata_finalizada' => true,
                'data_ata_finalizada' => '2023-12-20 21:00:00'
            ],
            [
                'titulo' => 'Reunião Ordinária - Novembro 2023',
                'descricao' => 'Reunião ordinária do conselho para novembro de 2023',
                'data_reuniao' => '2023-11-15',
                'hora_inicio' => '19:00',
                'hora_fim' => '21:00',
                'local' => 'Sala do Conselho',
                'tipo' => 'reuniao_ordinaria',
                'status' => 'finalizada',
                'quorum_minimo' => 8,
                'criado_por' => $admin->id,
                'presidente_id' => $pastor ? $pastor->id : $admin->id,
                'secretario_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'observacoes' => 'Eventos de Natal aprovados. Relatório financeiro, preparação para Natal, discussão sobre ação social. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',
                'ata_finalizada' => true,
                'data_ata_finalizada' => '2023-11-15 21:00:00'
            ],
            [
                'titulo' => 'Reunião Ordinária - Outubro 2023',
                'descricao' => 'Reunião ordinária do conselho para outubro de 2023',
                'data_reuniao' => '2023-10-15',
                'hora_inicio' => '19:00',
                'hora_fim' => '21:00',
                'local' => 'Sala do Conselho',
                'tipo' => 'reuniao_ordinaria',
                'status' => 'finalizada',
                'quorum_minimo' => 8,
                'criado_por' => $admin->id,
                'presidente_id' => $pastor ? $pastor->id : $admin->id,
                'secretario_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'observacoes' => 'Metas do terceiro trimestre atingidas. Avaliação do terceiro trimestre, planejamento para o último trimestre. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',
                'ata_finalizada' => true,
                'data_ata_finalizada' => '2023-10-15 21:00:00'
            ],
            [
                'titulo' => 'Reunião Extraordinária - Setembro 2023',
                'descricao' => 'Reunião extraordinária do conselho para setembro de 2023',
                'data_reuniao' => '2023-09-25',
                'hora_inicio' => '14:00',
                'hora_fim' => '16:00',
                'local' => 'Sala do Conselho',
                'tipo' => 'reuniao_extraordinaria',
                'status' => 'finalizada',
                'quorum_minimo' => 8,
                'criado_por' => $admin->id,
                'presidente_id' => $pastor ? $pastor->id : $admin->id,
                'secretario_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'observacoes' => 'Compra de equipamentos aprovada. Discussão sobre compra de equipamentos de som, aprovação de novos membros. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',
                'ata_finalizada' => true,
                'data_ata_finalizada' => '2023-09-25 16:00:00'
            ],
            [
                'titulo' => 'Reunião Ordinária - Agosto 2023',
                'descricao' => 'Reunião ordinária do conselho para agosto de 2023',
                'data_reuniao' => '2023-08-15',
                'hora_inicio' => '19:00',
                'hora_fim' => '21:00',
                'local' => 'Sala do Conselho',
                'tipo' => 'reuniao_ordinaria',
                'status' => 'finalizada',
                'quorum_minimo' => 8,
                'criado_por' => $admin->id,
                'presidente_id' => $pastor ? $pastor->id : $admin->id,
                'secretario_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'observacoes' => 'Ministérios funcionando bem. Relatório financeiro, discussão sobre ministérios, planejamento para o terceiro trimestre. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',
                'ata_finalizada' => true,
                'data_ata_finalizada' => '2023-08-15 21:00:00'
            ],
            [
                'titulo' => 'Reunião Ordinária - Julho 2023',
                'descricao' => 'Reunião ordinária do conselho para julho de 2023',
                'data_reuniao' => '2023-07-15',
                'hora_inicio' => '19:00',
                'hora_fim' => '21:00',
                'local' => 'Sala do Conselho',
                'tipo' => 'reuniao_ordinaria',
                'status' => 'finalizada',
                'quorum_minimo' => 8,
                'criado_por' => $admin->id,
                'presidente_id' => $pastor ? $pastor->id : $admin->id,
                'secretario_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'observacoes' => 'Primeiro semestre muito produtivo. Avaliação do primeiro semestre, discussão sobre evangelismo, aprovação de eventos. Participantes: Pr. João Silva, Maria Santos, Carlos Eduardo Almeida, Lucia Helena Rodrigues',
                'ata_finalizada' => true,
                'data_ata_finalizada' => '2023-07-15 21:00:00'
            ]
        ];

        foreach ($reunioes as $reuniao) {
            Conselho::create($reuniao);
        }

        $this->command->info('✅ Reuniões do conselho demonstrativas criadas com sucesso');
        $this->command->info('📊 Total de reuniões: ' . count($reunioes));
        
        // Estatísticas
        $finalizadas = collect($reunioes)->where('status', 'finalizada')->count();
        $agendadas = collect($reunioes)->where('status', 'agendada')->count();
        $ordinarias = collect($reunioes)->where('tipo', 'reuniao_ordinaria')->count();
        $extraordinarias = collect($reunioes)->where('tipo', 'reuniao_extraordinaria')->count();
        
        $this->command->info("✅ Finalizadas: {$finalizadas}");
        $this->command->info("📅 Agendadas: {$agendadas}");
        $this->command->info("📋 Ordinárias: {$ordinarias}");
        $this->command->info("🚨 Extraordinárias: {$extraordinarias}");
    }
} 