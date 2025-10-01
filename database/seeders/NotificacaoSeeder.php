<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔔 Criando notificações demonstrativas...');

        // Obter usuários
        $usuarios = User::all();

        if ($usuarios->isEmpty()) {
            $this->command->warn('⚠️ Nenhum usuário encontrado. Criando notificações sem associação...');
            return;
        }

        $notificacoes = [
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'info',
                'titulo' => 'Bem-vindo ao CBAV Sistema!',
                'mensagem' => 'Seja bem-vindo ao sistema de gestão ministerial. Estamos aqui para ajudá-lo a gerenciar sua igreja de forma eficiente.',
                'icone' => 'fas fa-home',
                'lida' => true,
                'lida_em' => now()->subDays(10)
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'success',
                'titulo' => 'Nova Campanha Criada',
                'mensagem' => 'Uma nova campanha foi criada: "Reforma do Templo". Confira os detalhes e participe!',
                'icone' => 'fas fa-hand-holding-usd',
                'lida' => false
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'warning',
                'titulo' => 'Reunião do Conselho',
                'mensagem' => 'Lembrete: Reunião do conselho amanhã às 19h. Não se esqueça de preparar os relatórios.',
                'icone' => 'fas fa-calendar-alt',
                'lida' => false
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'success',
                'titulo' => 'Doação Recebida',
                'mensagem' => 'Uma nova doação de R$ 500,00 foi recebida para a campanha "Compra de Instrumentos".',
                'icone' => 'fas fa-gift',
                'lida' => false
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'info',
                'titulo' => 'Novo Membro Cadastrado',
                'mensagem' => 'João Silva Santos foi cadastrado como novo membro da igreja. Confira os dados.',
                'icone' => 'fas fa-user-plus',
                'lida' => true,
                'lida_em' => now()->subDays(5)
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'info',
                'titulo' => 'Escola Dominical',
                'mensagem' => 'Lembrete: Escola Dominical amanhã às 8h. Prepare-se para a lição sobre "A Fé de Abraão".',
                'icone' => 'fas fa-book',
                'lida' => false
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'error',
                'titulo' => 'Manutenção do Sistema',
                'mensagem' => 'O sistema passará por manutenção hoje às 23h. Durante este período, algumas funcionalidades podem estar indisponíveis.',
                'icone' => 'fas fa-tools',
                'lida' => false
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'info',
                'titulo' => 'Aniversariantes do Mês',
                'mensagem' => 'Este mês temos 5 aniversariantes. Não se esqueça de parabenizá-los!',
                'icone' => 'fas fa-birthday-cake',
                'lida' => true,
                'lida_em' => now()->subDays(3)
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'info',
                'titulo' => 'Relatório Mensal',
                'mensagem' => 'O relatório mensal de atividades está disponível. Confira as estatísticas da igreja.',
                'icone' => 'fas fa-chart-bar',
                'lida' => false
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'warning',
                'titulo' => 'Culto de Oração',
                'mensagem' => 'Lembrete: Culto de oração hoje às 19h30. Venha interceder pela igreja e pelas necessidades.',
                'icone' => 'fas fa-pray',
                'lida' => false
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'info',
                'titulo' => 'Backup Realizado',
                'mensagem' => 'O backup automático do sistema foi realizado com sucesso. Seus dados estão seguros.',
                'icone' => 'fas fa-shield-alt',
                'lida' => true,
                'lida_em' => now()->subDays(7)
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'info',
                'titulo' => 'Nova Solicitação de Ministério',
                'mensagem' => 'Ana Paula Ferreira solicitou participação no ministério de louvor. Confira os detalhes.',
                'icone' => 'fas fa-hands-helping',
                'lida' => false
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'warning',
                'titulo' => 'Transação Pendente',
                'mensagem' => 'Uma transação de R$ 150,00 está pendente de aprovação. Verifique os detalhes.',
                'icone' => 'fas fa-clock',
                'lida' => false
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'info',
                'titulo' => 'Atualização do Sistema',
                'mensagem' => 'Uma nova atualização do sistema está disponível. Recomendamos atualizar para ter acesso às novas funcionalidades.',
                'icone' => 'fas fa-download',
                'lida' => false
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'info',
                'titulo' => 'Ensaio de Louvor',
                'mensagem' => 'Ensaio de louvor amanhã às 14h. Não se esqueça de trazer seu instrumento.',
                'icone' => 'fas fa-music',
                'lida' => true,
                'lida_em' => now()->subDays(2)
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'success',
                'titulo' => 'Meta de Campanha Atingida',
                'mensagem' => 'Parabéns! A campanha "Ação Social - Natal" atingiu 50% da meta. Continue apoiando!',
                'icone' => 'fas fa-trophy',
                'lida' => false
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'warning',
                'titulo' => 'Visita Pastoral',
                'mensagem' => 'Lembrete: Visita pastoral programada para hoje às 15h. Confirme sua disponibilidade.',
                'icone' => 'fas fa-user-tie',
                'lida' => false
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'info',
                'titulo' => 'Novo Devocional',
                'mensagem' => 'Um novo devocional foi publicado: "A Fé que Move Montanhas". Leia e medite na palavra.',
                'icone' => 'fas fa-bible',
                'lida' => true,
                'lida_em' => now()->subDays(5)
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'error',
                'titulo' => 'Erro no Sistema',
                'mensagem' => 'Foi detectado um erro no sistema. Nossa equipe técnica está trabalhando para resolver.',
                'icone' => 'fas fa-exclamation-triangle',
                'lida' => false
            ],
            [
                'user_id' => $usuarios->first()->id,
                'tipo' => 'info',
                'titulo' => 'Reunião de Ministérios',
                'mensagem' => 'Reunião de líderes de ministérios no próximo sábado às 9h. Prepare o relatório do seu ministério.',
                'icone' => 'fas fa-users',
                'lida' => false
            ]
        ];

        foreach ($notificacoes as $notificacao) {
            Notificacao::updateOrCreate(
                ['titulo' => $notificacao['titulo'], 'user_id' => $notificacao['user_id']],
                $notificacao
            );
        }

        $this->command->info('✅ Notificações demonstrativas criadas com sucesso');
        $this->command->info('📊 Total de notificações: ' . count($notificacoes));

        // Estatísticas
        $naoLidas = collect($notificacoes)->where('lida', false)->count();
        $lidas = collect($notificacoes)->where('lida', true)->count();
        $info = collect($notificacoes)->where('tipo', 'info')->count();
        $success = collect($notificacoes)->where('tipo', 'success')->count();
        $warning = collect($notificacoes)->where('tipo', 'warning')->count();
        $error = collect($notificacoes)->where('tipo', 'error')->count();

        $this->command->info("📬 Não lidas: {$naoLidas}");
        $this->command->info("📖 Lidas: {$lidas}");
        $this->command->info("ℹ️ Info: {$info}");
        $this->command->info("✅ Success: {$success}");
        $this->command->info("⚠️ Warning: {$warning}");
        $this->command->info("❌ Error: {$error}");
    }
}
