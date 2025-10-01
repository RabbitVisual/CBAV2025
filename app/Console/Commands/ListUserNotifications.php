<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\User;

class ListUserNotifications extends Command
{
    protected $signature = 'notifications:user {user_id}';
    protected $description = 'Listar notificações de um usuário específico';

    public function handle()
    {
        $userId = $this->argument('user_id');

        $this->info("=== NOTIFICAÇÕES DO USUÁRIO {$userId} ===");

        $user = User::find($userId);
        if (!$user) {
            $this->error("❌ Usuário com ID {$userId} não encontrado");
            return 1;
        }

        $this->info("✅ Usuário: {$user->name} ({$user->email})");

        // Notificações diretas
        $notificacoesDiretas = Notificacao::where('user_id', $userId)->get();
        $this->info("\n📬 Notificações Diretas ({$notificacoesDiretas->count()}):");

        if ($notificacoesDiretas->isEmpty()) {
            $this->warn("   Nenhuma notificação direta");
        } else {
            foreach ($notificacoesDiretas as $n) {
                $status = $n->lida ? '✅ Lida' : '❌ Não lida';
                $this->info("   ID {$n->id}: {$n->titulo} - {$status}");
            }
        }

        // Notificações globais
        $notificacoesGlobais = Notificacao::whereNull('user_id')
            ->where(function ($q) {
                $q->where('destinatario_tipo', 'todos')
                    ->orWhereNull('destinatario_tipo');
            })
            ->get();

        $this->info("\n🌍 Notificações Globais ({$notificacoesGlobais->count()}):");
        if ($notificacoesGlobais->isEmpty()) {
            $this->warn("   Nenhuma notificação global");
        } else {
            foreach ($notificacoesGlobais as $n) {
                $status = $n->lida ? '✅ Lida' : '❌ Não lida';
                $this->info("   ID {$n->id}: {$n->titulo} - {$status}");
            }
        }

        // Notificações de membro (se aplicável)
        if ($user->isMembro()) {
            $membro = \App\Models\Membro::where('email', $user->email)->first();
            if ($membro) {
                $notificacoesMembro = Notificacao::where('destinatario_tipo', 'membro')
                    ->where('destinatario_id', $membro->id)
                    ->get();

                $this->info("\n👥 Notificações de Membro ({$notificacoesMembro->count()}):");
                if ($notificacoesMembro->isEmpty()) {
                    $this->warn("   Nenhuma notificação de membro");
                } else {
                    foreach ($notificacoesMembro as $n) {
                        $status = $n->lida ? '✅ Lida' : '❌ Não lida';
                        $this->info("   ID {$n->id}: {$n->titulo} - {$status}");
                    }
                }
            }
        }

        // Total via NotificacaoService
        $todasNotificacoes = \App\Services\NotificacaoService::getTodasNotificacoesUsuario($user);
        $this->info("\n📊 Resumo via NotificacaoService:");
        $this->info("   - Total: {$todasNotificacoes->count()}");
        $this->info("   - Não lidas: " . $todasNotificacoes->where('lida', false)->count());
        $this->info("   - Lidas: " . $todasNotificacoes->where('lida', true)->count());

        return 0;
    }
}
