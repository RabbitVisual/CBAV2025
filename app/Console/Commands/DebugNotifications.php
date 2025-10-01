<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\User;
use App\Models\Membro;
use App\Services\NotificacaoService;

class DebugNotifications extends Command
{
    protected $signature = 'notifications:debug {user_id}';
    protected $description = 'Debugar notificações para um usuário específico';

    public function handle()
    {
        $userId = $this->argument('user_id');

        $this->info('=== DEBUG DE NOTIFICAÇÕES ===');

        // Buscar usuário
        $user = User::find($userId);
        if (!$user) {
            $this->error("Usuário com ID {$userId} não encontrado!");
            return;
        }

        $this->info("Usuário: {$user->name} (ID: {$user->id})");
        $this->info("Email: {$user->email}");

        // Verificar se é membro
        $membro = Membro::where('email', $user->email)->first();
        if ($membro) {
            $this->info("Membro encontrado: {$membro->nome} (ID: {$membro->id})");
        } else {
            $this->warn("Nenhum membro encontrado com email: {$user->email}");
        }

        // Buscar notificações diretas para o usuário
        $notificacoesDiretas = Notificacao::where('user_id', $user->id)->get();
        $this->info("\n=== NOTIFICAÇÕES DIRETAS PARA USUÁRIO ===");
        $this->info("Total: {$notificacoesDiretas->count()}");

        foreach ($notificacoesDiretas as $n) {
            $this->info("ID: {$n->id} - Título: {$n->titulo} - Lida: " . ($n->lida ? 'Sim' : 'Não') . " - Criada: {$n->created_at->format('d/m/Y H:i:s')}");
        }

        // Buscar notificações para membro
        if ($membro) {
            $notificacoesMembro = Notificacao::where(function ($query) use ($membro) {
                $query->where('dados_extras->membro_id', $membro->id)
                    ->orWhere('destinatario_tipo', 'membros')
                    ->orWhere('destinatario_tipo', 'todos');
            })->get();

            $this->info("\n=== NOTIFICAÇÕES PARA MEMBRO ===");
            $this->info("Total: {$notificacoesMembro->count()}");

            foreach ($notificacoesMembro as $n) {
                $this->info("ID: {$n->id} - Título: {$n->titulo} - Lida: " . ($n->lida ? 'Sim' : 'Não') . " - Criada: {$n->created_at->format('d/m/Y H:i:s')}");
            }
        }

        // Testar o serviço
        $this->info("\n=== TESTE DO SERVIÇO ===");
        $notificacoesServico = NotificacaoService::getNotificacoesUsuario($user);
        $this->info("Notificações retornadas pelo serviço: {$notificacoesServico->count()}");

        foreach ($notificacoesServico as $n) {
            $this->info("ID: {$n->id} - Título: {$n->titulo} - Lida: " . ($n->lida ? 'Sim' : 'Não') . " - Criada: {$n->created_at->format('d/m/Y H:i:s')}");
        }

        $count = NotificacaoService::countNotificacoesNaoLidas($user);
        $this->info("Contagem pelo serviço: {$count}");

        $this->info('=== FIM DO DEBUG ===');
    }
}
