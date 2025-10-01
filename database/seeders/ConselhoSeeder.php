<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conselho;
use App\Models\User;

class ConselhoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏛️ Criando conselhos demonstrativos...');

        // Obter usuários para associar aos conselhos
        $admin = User::where('email', 'admin@cbav.com')->first();
        $pastor = User::where('email', 'pastor@cbav.com')->first();
        $tesoureiro = User::where('email', 'tesoureiro@cbav.com')->first();
        $membro = User::where('email', 'membro@cbav.com')->first();

        if (!$admin) {
            $this->command->warn('⚠️ Usuário admin não encontrado. Criando conselhos sem associação...');
            return;
        }

        $conselhos = [
            [
                'titulo' => 'Conselho Administrativo 2024',
                'descricao' => 'Conselho administrativo da igreja para o ano de 2024',
                'data_reuniao' => '2024-01-15',
                'hora_inicio' => '19:00',
                'hora_fim' => '21:00',
                'local' => 'Sala do Conselho',
                'status' => 'finalizada',
                'tipo' => 'reuniao_ordinaria',
                'quorum_minimo' => 8,
                'criado_por' => $admin->id,
                'presidente_id' => $pastor ? $pastor->id : $admin->id,
                'secretario_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'observacoes' => 'Primeira reunião do ano com aprovação do orçamento',
                'ata_finalizada' => true,
                'data_ata_finalizada' => '2024-01-15 21:00:00'
            ],
            [
                'titulo' => 'Conselho Extraordinário - Reforma',
                'descricao' => 'Reunião extraordinária para discutir reforma do templo',
                'data_reuniao' => '2024-02-10',
                'hora_inicio' => '14:00',
                'hora_fim' => '16:00',
                'local' => 'Sala do Conselho',
                'status' => 'finalizada',
                'tipo' => 'reuniao_extraordinaria',
                'quorum_minimo' => 8,
                'criado_por' => $admin->id,
                'presidente_id' => $pastor ? $pastor->id : $admin->id,
                'secretario_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'observacoes' => 'Decisão tomada sobre iniciar campanha de reforma',
                'ata_finalizada' => true,
                'data_ata_finalizada' => '2024-02-10 16:00:00'
            ],
            [
                'titulo' => 'Conselho Mensal - Março 2024',
                'descricao' => 'Reunião mensal do conselho para março de 2024',
                'data_reuniao' => '2024-03-15',
                'hora_inicio' => '19:00',
                'hora_fim' => '21:00',
                'local' => 'Sala do Conselho',
                'status' => 'finalizada',
                'tipo' => 'reuniao_ordinaria',
                'quorum_minimo' => 8,
                'criado_por' => $admin->id,
                'presidente_id' => $pastor ? $pastor->id : $admin->id,
                'secretario_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'observacoes' => 'Relatórios aprovados e eventos confirmados',
                'ata_finalizada' => true,
                'data_ata_finalizada' => '2024-03-15 21:00:00'
            ],
            [
                'titulo' => 'Conselho Mensal - Abril 2024',
                'descricao' => 'Reunião mensal do conselho para abril de 2024',
                'data_reuniao' => '2024-04-15',
                'hora_inicio' => '19:00',
                'hora_fim' => '21:00',
                'local' => 'Sala do Conselho',
                'status' => 'agendada',
                'tipo' => 'reuniao_ordinaria',
                'quorum_minimo' => 8,
                'criado_por' => $admin->id,
                'presidente_id' => $pastor ? $pastor->id : $admin->id,
                'secretario_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'observacoes' => 'Reunião agendada para avaliação dos ministérios',
                'ata_finalizada' => false
            ],
            [
                'titulo' => 'Conselho de Planejamento 2024',
                'descricao' => 'Reunião de planejamento estratégico para 2024',
                'data_reuniao' => '2023-12-20',
                'hora_inicio' => '19:00',
                'hora_fim' => '21:00',
                'local' => 'Sala do Conselho',
                'status' => 'finalizada',
                'tipo' => 'reuniao_extraordinaria',
                'quorum_minimo' => 8,
                'criado_por' => $admin->id,
                'presidente_id' => $pastor ? $pastor->id : $admin->id,
                'secretario_id' => $tesoureiro ? $tesoureiro->id : $admin->id,
                'observacoes' => 'Planejamento aprovado para 2024',
                'ata_finalizada' => true,
                'data_ata_finalizada' => '2023-12-20 21:00:00'
            ]
        ];

        foreach ($conselhos as $conselho) {
            Conselho::create($conselho);
        }

        $this->command->info('✅ Conselhos demonstrativos criados com sucesso');
        $this->command->info('📊 Total de conselhos: ' . count($conselhos));
        
        // Estatísticas
        $finalizadas = collect($conselhos)->where('status', 'finalizada')->count();
        $agendadas = collect($conselhos)->where('status', 'agendada')->count();
        $ordinarias = collect($conselhos)->where('tipo', 'reuniao_ordinaria')->count();
        $extraordinarias = collect($conselhos)->where('tipo', 'reuniao_extraordinaria')->count();
        
        $this->command->info("✅ Finalizadas: {$finalizadas}");
        $this->command->info("📅 Agendadas: {$agendadas}");
        $this->command->info("📊 Ordinárias: {$ordinarias}");
        $this->command->info("📊 Extraordinárias: {$extraordinarias}");
    }
} 