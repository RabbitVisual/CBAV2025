<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Iniciando população do banco de dados...');

        // 1. Configurações básicas do sistema
        $this->call([
            SystemConfigSeeder::class,
            TimezoneSeeder::class,
            HomeConfigSeeder::class,
            GatewaySettingsSeeder::class,
        ]);

        // 2. Sistema de permissões e roles
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        // 3. Dados iniciais essenciais
        $this->call([
            InitialDataSeeder::class,
        ]);

        // 4. Estrutura organizacional
        $this->call([
            MinisterioSeeder::class,
            DepartamentoSeeder::class,
            CargoSeeder::class,
        ]);

        // 5. EBD - Escola Bíblica Dominical
        $this->call([
            EbdTurmaSeeder::class,
            EbdProfessorSeeder::class,
            EbdAlunoSeeder::class,
            EbdLicaoSeeder::class,
            EbdAulaSeeder::class,
            EbdAvaliacaoSeeder::class,
            EbdQuizPerguntasSeeder::class,
            EbdQuizSessoesSeeder::class,
            EbdQuizRespostasSeeder::class,
        ]);

        // 6. Ministérios e membros
        $this->call([
            MembroSeeder::class,
            MembroCargoSeeder::class,
            SolicitacaoMinisterioSeeder::class,
        ]);

        // 7. Sistema financeiro
        $this->call([
            CampanhaSeeder::class,
            TransacaoSeeder::class,
            DocumentoDeclaracaoAnualSeeder::class,
            DocumentoBaixaSeeder::class,
        ]);

        // 8. Conselho e reuniões
        $this->call([
            ConselhoSeeder::class,
            ConselhoReuniaoSeeder::class,
            ConselhoPautaSeeder::class,
            ConselhoVotacaoSeeder::class,
        ]);

        // 9. Devocionais e conteúdo espiritual
        $this->call([
            DevocionalSeeder::class,
        ]);

        // 10. Sistema de intercessão
        $this->call([
            PedidoOracaoSeeder::class,
            IntercessaoSeeder::class,
        ]);

        // 11. Notificações e comunicação
        $this->call([
            NotificacaoSeeder::class,
        ]);

        // 12. Usuários demonstrativos
        $this->call([
            UsuarioSeeder::class,
        ]);

        $this->command->info('✅ Banco de dados populado com sucesso!');
        $this->command->info('📋 Credenciais de acesso:');
        $this->command->info('   👤 Admin: admin@cbav.com / password');
        $this->command->info('   👤 Pastor: pastor@cbav.com / password');
        $this->command->info('   👤 Tesoureiro: tesoureiro@cbav.com / password');
        $this->command->info('   👤 Membro: membro@cbav.com / password');
    }
} 