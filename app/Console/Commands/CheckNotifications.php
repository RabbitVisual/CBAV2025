<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\User;
use App\Models\Membro;

class CheckNotifications extends Command
{
    protected $signature = 'notifications:check {user_id?}';
    protected $description = 'Verificar notificações do sistema';

    public function handle()
    {
        $userId = $this->argument('user_id');

        $this->info('=== VERIFICAÇÃO DE NOTIFICAÇÕES ===');

        // Verificar todas as notificações
        $notificacoes = Notificacao::all();
        $this->info("Total de notificações: {$notificacoes->count()}");

        if ($notificacoes->count() > 0) {
            $this->table(
                ['ID', 'Título', 'Tipo', 'Destinatário', 'Destinatário ID', 'Lida', 'Criada em'],
                $notificacoes->map(function ($n) {
                    return [
                        $n->id,
                        $n->titulo,
                        $n->tipo,
                        $n->destinatario_tipo,
                        $n->destinatario_id,
                        $n->lida ? 'Sim' : 'Não',
                        $n->created_at->format('d/m/Y H:i:s')
                    ];
                })->toArray()
            );
        }

        // Se um user_id foi fornecido, verificar notificações específicas
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $this->info("\n=== NOTIFICAÇÕES PARA USUÁRIO {$user->name} ===");

                // Verificar se é membro
                $membro = Membro::where('email', $user->email)->first();
                if ($membro) {
                    $this->info("Usuário é membro com ID: {$membro->id}");

                    // Buscar notificações para membro
                    $notificacoesMembro = Notificacao::where(function ($query) use ($membro) {
                        $query->where('dados_extras->membro_id', $membro->id)
                            ->orWhere('destinatario_tipo', 'membros')
                            ->orWhere('destinatario_tipo', 'todos');
                    })->get();

                    $this->info("Notificações para membro: {$notificacoesMembro->count()}");

                    if ($notificacoesMembro->count() > 0) {
                        $this->table(
                            ['ID', 'Título', 'Tipo', 'Lida', 'Criada em'],
                            $notificacoesMembro->map(function ($n) {
                                return [
                                    $n->id,
                                    $n->titulo,
                                    $n->tipo,
                                    $n->lida ? 'Sim' : 'Não',
                                    $n->created_at->format('d/m/Y H:i:s')
                                ];
                            })->toArray()
                        );
                    }
                }

                // Buscar notificações diretas para usuário
                $notificacoesUsuario = Notificacao::where('user_id', $user->id)->get();
                $this->info("Notificações diretas para usuário: {$notificacoesUsuario->count()}");

                if ($notificacoesUsuario->count() > 0) {
                    $this->table(
                        ['ID', 'Título', 'Tipo', 'Lida', 'Criada em'],
                        $notificacoesUsuario->map(function ($n) {
                            return [
                                $n->id,
                                $n->titulo,
                                $n->tipo,
                                $n->lida ? 'Sim' : 'Não',
                                $n->created_at->format('d/m/Y H:i:s')
                            ];
                        })->toArray()
                    );
                }
            } else {
                $this->error("Usuário com ID {$userId} não encontrado!");
            }
        }

        $this->info('=== FIM DA VERIFICAÇÃO ===');
    }
}
