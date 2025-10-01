<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;

class ListNotifications extends Command
{
    protected $signature = 'notifications:list';
    protected $description = 'Listar todas as notificações';

    public function handle()
    {
        $this->info("=== LISTA DE NOTIFICAÇÕES ===");

        $notificacoes = Notificacao::select('id', 'titulo', 'user_id', 'destinatario_tipo', 'destinatario_id', 'lida')
            ->orderBy('id')
            ->get();

        if ($notificacoes->isEmpty()) {
            $this->warn("Nenhuma notificação encontrada");
            return 0;
        }

        $this->table(
            ['ID', 'Título', 'User ID', 'Destinatário Tipo', 'Destinatário ID', 'Lida'],
            $notificacoes->map(function ($n) {
                return [
                    $n->id,
                    $n->titulo,
                    $n->user_id ?? 'null',
                    $n->destinatario_tipo ?? 'null',
                    $n->destinatario_id ?? 'null',
                    $n->lida ? 'Sim' : 'Não'
                ];
            })->toArray()
        );

        return 0;
    }
}
