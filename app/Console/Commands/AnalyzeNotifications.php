<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\User;

class AnalyzeNotifications extends Command
{
    protected $signature = 'notifications:analyze';
    protected $description = 'Analisar notificações existentes';

    public function handle()
    {
        $this->info("=== ANÁLISE DAS NOTIFICAÇÕES ===");

        $notificacoes = Notificacao::all();
        $this->info("Total de notificações: " . $notificacoes->count());

        $this->info("\n=== DETALHES DAS NOTIFICAÇÕES ===");
        foreach ($notificacoes as $notificacao) {
            $this->info("ID: {$notificacao->id} - Tipo: {$notificacao->tipo} - Lida: " . ($notificacao->lida ? 'Sim' : 'Não') . " - User ID: {$notificacao->user_id} - Destinatario: {$notificacao->destinatario_tipo}");
        }

        $this->info("\n=== ESTATÍSTICAS ===");
        $this->info("Não lidas: " . $notificacoes->where('lida', false)->count());
        $this->info("Lidas: " . $notificacoes->where('lida', true)->count());
        $this->info("Com user_id: " . $notificacoes->whereNotNull('user_id')->count());
        $this->info("Sem user_id: " . $notificacoes->whereNull('user_id')->count());

        $this->info("\n=== TESTE DE CONTADOR ===");
        $user = User::first();
        if ($user) {
            $count = \App\Services\NotificacaoService::countNotificacoesNaoLidas($user);
            $this->info("Usuário: {$user->name} - Notificações não lidas: {$count}");
        }

        return 0;
    }
}
