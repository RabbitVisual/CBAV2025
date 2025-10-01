<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificacaoService;
use App\Models\Notification;
use Carbon\Carbon;

class ProcessarNotificacoesAgendadas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificacoes:processar-agendadas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processa notificações agendadas para envio';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processando notificações agendadas...');

        try {
            $processadas = NotificacaoService::processarNotificacoesAgendadas();

            if ($processadas > 0) {
                $this->info("✅ {$processadas} notificação(ões) processada(s) com sucesso!");
            } else {
                $this->info('ℹ️ Nenhuma notificação agendada para processar.');
            }

            // Limpar notificações antigas
            $limpas = NotificacaoService::limparNotificacoesAntigas(30);
            if ($limpas > 0) {
                $this->info("🗑️ {$limpas} notificação(ões) antiga(s) removida(s).");
            }
        } catch (\Exception $e) {
            $this->error('❌ Erro ao processar notificações: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
