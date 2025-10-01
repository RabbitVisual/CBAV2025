<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class FixNotifications extends Command
{
    protected $signature = 'notifications:fix';
    protected $description = 'Corrigir notificações com problemas de estrutura';

    public function handle()
    {
        $this->info("=== CORRIGINDO NOTIFICAÇÕES ===");

        // Verificar notificações existentes
        $total = Notificacao::count();
        $this->info("Total de notificações: {$total}");

        if ($total === 0) {
            $this->info("Nenhuma notificação encontrada.");
            return;
        }

        // Verificar notificações com problemas
        $problemas = 0;
        $corrigidas = 0;

        $notificacoes = Notificacao::all();

        foreach ($notificacoes as $notificacao) {
            $problema = false;
            $updates = [];

            // Verificar se tem campos obrigatórios
            if (empty($notificacao->tipo)) {
                $updates['tipo'] = 'info';
                $problema = true;
            }

            if (empty($notificacao->prioridade)) {
                $updates['prioridade'] = 'normal';
                $problema = true;
            }

            if (empty($notificacao->categoria)) {
                $updates['categoria'] = 'sistema';
                $problema = true;
            }

            // Verificar se tem destinatário_tipo mas não tem destinatario_id
            if ($notificacao->destinatario_tipo && !$notificacao->destinatario_id) {
                $updates['destinatario_tipo'] = 'todos';
                $updates['destinatario_id'] = null;
                $problema = true;
            }

            // Verificar se tem agendada_para mas não tem enviada_em
            if ($notificacao->agendada_para && !$notificacao->enviada_em) {
                if ($notificacao->agendada_para <= now()) {
                    $updates['enviada_em'] = now();
                    $problema = true;
                }
            }

            // Aplicar correções se necessário
            if ($problema) {
                $notificacao->update($updates);
                $corrigidas++;
                $this->info("✓ Notificação ID {$notificacao->id} corrigida");
            }
        }

        $this->info("\n=== RESUMO ===");
        $this->info("Total de notificações verificadas: {$total}");
        $this->info("Notificações corrigidas: {$corrigidas}");

        if ($corrigidas > 0) {
            $this->info("✅ Correções aplicadas com sucesso!");
        } else {
            $this->info("✅ Nenhuma correção necessária!");
        }

        return 0;
    }
}
