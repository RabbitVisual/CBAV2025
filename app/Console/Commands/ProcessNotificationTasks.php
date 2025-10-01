<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;
use App\Services\QuizAlertService;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ProcessNotificationTasks extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'notifications:process 
                           {--type=all : Tipo de processamento (all, scheduled, reminders, cleanup)}
                           {--dry-run : Executar sem fazer alterações}';

    /**
     * The console command description.
     */
    protected $description = 'Processar tarefas de notificação (agendadas, lembretes, limpeza)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->option('type');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('🔍 Modo DRY-RUN ativado - nenhuma alteração será feita');
        }

        $this->info('🚀 Iniciando processamento de notificações...');
        $this->newLine();

        $totalProcessed = 0;

        try {
            switch ($type) {
                case 'scheduled':
                    $totalProcessed += $this->processScheduledNotifications($dryRun);
                    break;
                case 'reminders':
                    $totalProcessed += $this->processQuizReminders($dryRun);
                    break;
                case 'cleanup':
                    $totalProcessed += $this->cleanupOldNotifications($dryRun);
                    break;
                case 'all':
                default:
                    $totalProcessed += $this->processScheduledNotifications($dryRun);
                    $totalProcessed += $this->processQuizReminders($dryRun);
                    $totalProcessed += $this->cleanupOldNotifications($dryRun);
                    break;
            }

            $this->newLine();
            $this->info("✅ Processamento concluído! Total de itens processados: {$totalProcessed}");
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Erro durante o processamento: ' . $e->getMessage());
            Log::error('Error in ProcessNotificationTasks command: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }

    /**
     * Processar notificações agendadas
     */
    private function processScheduledNotifications(bool $dryRun): int
    {
        $this->info('📅 Processando notificações agendadas...');
        
        $pendingNotifications = Notification::pending()->get();
        $count = $pendingNotifications->count();
        
        if ($count === 0) {
            $this->line('   ℹ️  Nenhuma notificação agendada pendente');
            return 0;
        }

        $this->line("   📊 Encontradas {$count} notificação(ões) para processar");
        
        if ($dryRun) {
            $this->table(
                ['ID', 'Título', 'Tipo', 'Agendada para', 'Destinatário'],
                $pendingNotifications->map(function ($notification) {
                    return [
                        $notification->id,
                        str_limit($notification->title, 30),
                        $notification->type,
                        $notification->scheduled_at?->format('d/m/Y H:i'),
                        $notification->recipient_type
                    ];
                })->toArray()
            );
            return $count;
        }

        $processed = 0;
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($pendingNotifications as $notification) {
            try {
                // Processar através do serviço
                NotificationService::processScheduledNotifications();
                $processed++;
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("   ❌ Erro ao processar notificação {$notification->id}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("   ✅ {$processed} notificação(ões) processada(s)");
        
        return $processed;
    }

    /**
     * Processar lembretes de quiz
     */
    private function processQuizReminders(bool $dryRun): int
    {
        $this->info('🎯 Processando lembretes de quiz...');
        
        if ($dryRun) {
            // Simular contagem
            $inactiveToday = $this->getUsersInactiveToday();
            $inactiveWeek = $this->getUsersInactiveForDays(7);
            $inactiveLong = $this->getUsersInactiveForDays(30);
            
            $this->line("   📊 Usuários inativos hoje: " . count($inactiveToday));
            $this->line("   📊 Usuários inativos há 1 semana: " . count($inactiveWeek));
            $this->line("   📊 Usuários inativos há 1 mês: " . count($inactiveLong));
            
            return count($inactiveToday) + count($inactiveWeek) + count($inactiveLong);
        }

        $processed = QuizAlertService::processAutomaticReminders();
        $this->info("   ✅ {$processed} lembrete(s) de quiz enviado(s)");
        
        return $processed;
    }

    /**
     * Limpar notificações antigas
     */
    private function cleanupOldNotifications(bool $dryRun): int
    {
        $this->info('🧹 Limpando notificações antigas...');
        
        $daysOld = 90; // Configurável
        $cutoffDate = now()->subDays($daysOld);
        
        $oldNotifications = Notification::where('created_at', '<', $cutoffDate)
                                       ->where('is_persistent', false)
                                       ->get();
        
        $count = $oldNotifications->count();
        
        if ($count === 0) {
            $this->line('   ℹ️  Nenhuma notificação antiga para limpar');
            return 0;
        }

        $this->line("   📊 Encontradas {$count} notificação(ões) antigas (>{$daysOld} dias)");
        
        if ($dryRun) {
            $this->table(
                ['ID', 'Título', 'Tipo', 'Criada em', 'Idade (dias)'],
                $oldNotifications->take(10)->map(function ($notification) {
                    return [
                        $notification->id,
                        str_limit($notification->title, 30),
                        $notification->type,
                        $notification->created_at->format('d/m/Y'),
                        $notification->created_at->diffInDays(now())
                    ];
                })->toArray()
            );
            
            if ($count > 10) {
                $this->line("   ... e mais " . ($count - 10) . " notificação(ões)");
            }
            
            return $count;
        }

        if ($this->confirm("Deseja realmente excluir {$count} notificação(ões) antigas?")) {
            $deleted = NotificationService::cleanupOldNotifications($daysOld);
            $this->info("   ✅ {$deleted} notificação(ões) antiga(s) removida(s)");
            return $deleted;
        } else {
            $this->line('   ⏭️  Limpeza cancelada pelo usuário');
            return 0;
        }
    }

    /**
     * Obter usuários inativos hoje
     */
    private function getUsersInactiveToday(): array
    {
        $activeToday = \App\Models\EbdQuizSessao::whereDate('created_at', today())
                                               ->pluck('user_id')
                                               ->unique()
                                               ->toArray();

        return User::whereNotIn('id', $activeToday)
                  ->where('active', true)
                  ->pluck('id')
                  ->toArray();
    }

    /**
     * Obter usuários inativos por dias
     */
    private function getUsersInactiveForDays(int $days): array
    {
        $cutoffDate = now()->subDays($days);
        
        $activeRecently = \App\Models\EbdQuizSessao::where('created_at', '>=', $cutoffDate)
                                                   ->pluck('user_id')
                                                   ->unique()
                                                   ->toArray();

        return User::whereNotIn('id', $activeRecently)
                  ->where('active', true)
                  ->pluck('id')
                  ->toArray();
    }

    /**
     * Exibir estatísticas gerais
     */
    private function showStats(): void
    {
        $this->info('📊 Estatísticas do Sistema de Notificações:');
        $this->newLine();
        
        $stats = [
            'Total de notificações' => Notification::count(),
            'Notificações enviadas' => Notification::where('status', 'sent')->count(),
            'Notificações agendadas' => Notification::where('status', 'scheduled')->count(),
            'Notificações falharam' => Notification::where('status', 'failed')->count(),
            'Usuários ativos' => User::where('active', true)->count(),
        ];
        
        foreach ($stats as $label => $value) {
            $this->line("   {$label}: {$value}");
        }
        
        $this->newLine();
    }

    /**
     * Verificar saúde do sistema
     */
    private function checkSystemHealth(): void
    {
        $this->info('🏥 Verificando saúde do sistema...');
        
        $issues = [];
        
        // Verificar notificações falhadas
        $failedCount = Notification::where('status', 'failed')->count();
        if ($failedCount > 10) {
            $issues[] = "Muitas notificações falharam ({$failedCount})";
        }
        
        // Verificar notificações muito antigas agendadas
        $oldScheduled = Notification::where('status', 'scheduled')
                                   ->where('scheduled_at', '<', now()->subHours(24))
                                   ->count();
        if ($oldScheduled > 0) {
            $issues[] = "Notificações agendadas há mais de 24h ({$oldScheduled})";
        }
        
        // Verificar usuários sem preferências
        $usersWithoutPrefs = User::whereDoesntHave('notificationPreference')->count();
        if ($usersWithoutPrefs > 0) {
            $issues[] = "Usuários sem preferências de notificação ({$usersWithoutPrefs})";
        }
        
        if (empty($issues)) {
            $this->info('   ✅ Sistema funcionando normalmente');
        } else {
            $this->warn('   ⚠️  Problemas encontrados:');
            foreach ($issues as $issue) {
                $this->line("      - {$issue}");
            }
        }
    }
}