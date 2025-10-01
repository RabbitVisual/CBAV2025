<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\CbavInstallCommand::class,
        \App\Console\Commands\AnalyzeDatabaseStructure::class,
        \App\Console\Commands\CleanupUnusedTables::class,
        \App\Console\Commands\ListDatabaseTables::class,
        \App\Console\Commands\OptimizeDatabaseStructure::class,
        \App\Console\Commands\AddMemberAccessPermission::class,
        \App\Console\Commands\ApplyTimezone::class,
        \App\Console\Commands\AssignEbdPermissions::class,
        \App\Console\Commands\CheckBibleStatus::class,
        \App\Console\Commands\CheckConfigConflicts::class,
        \App\Console\Commands\CheckEbdAlunos::class,
        \App\Console\Commands\CheckMemberAccess::class,
        \App\Console\Commands\CheckNotifications::class,
        \App\Console\Commands\CheckPermissions::class,
        \App\Console\Commands\CheckSystemConfig::class,
        \App\Console\Commands\ClearBibleCache::class,
        \App\Console\Commands\ClearOldLogs::class,
        \App\Console\Commands\CouncilSettingsCommand::class,
        \App\Console\Commands\CreateTestNotification::class,
        \App\Console\Commands\DebugNotifications::class,
        \App\Console\Commands\FixConfigurations::class,
        \App\Console\Commands\ListUsers::class,
        \App\Console\Commands\MigrateToNewStructure::class,
        \App\Console\Commands\SetupBibleOffline::class,
        \App\Console\Commands\SetupGateways::class,
        \App\Console\Commands\TestBibleCommand::class,
        \App\Console\Commands\TestConfigurations::class,
        \App\Console\Commands\TestControllerLogic::class,
        \App\Console\Commands\TestEbdAccess::class,
        \App\Console\Commands\TestGateways::class,
        \App\Console\Commands\TestHeaderVariables::class,
        \App\Console\Commands\TestHomePage::class,
        \App\Console\Commands\TestLoggingSystem::class,
        \App\Console\Commands\TestMemberLayout::class,
        \App\Console\Commands\TestMiddleware::class,
        \App\Console\Commands\TestMiddlewareExecution::class,
        \App\Console\Commands\TestNotificacoes::class,
        \App\Console\Commands\TestNotification::class,
        \App\Console\Commands\TestNotificationAPI::class,
        \App\Console\Commands\TestNotificationRoutes::class,
        \App\Console\Commands\TestQuizBiblico::class,
        \App\Console\Commands\TestQuizEmail::class,
        \App\Console\Commands\TestQuizEstatisticas::class,
        \App\Console\Commands\TestSpecificNotification::class,
        \App\Console\Commands\TestSystemConfig::class,
        \App\Console\Commands\TestUrl::class,
        \App\Console\Commands\TestWebPage::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Limpar logs antigos
        $schedule->command('logs:clear')->daily();
        
        // Backup automático do sistema
        $schedule->command('backup:sistema')->daily();
        
        // Verificar status do sistema
        $schedule->command('system:check')->weekly();
        
        // Limpar cache da Bíblia
        $schedule->command('bible:clear-cache')->weekly();
        
        // Verificar configurações
        $schedule->command('config:check')->monthly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 