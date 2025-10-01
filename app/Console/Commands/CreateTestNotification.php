<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\User;
use App\Models\Membro;

class CreateTestNotification extends Command
{
    protected $signature = 'notification:create-test {membro_id}';
    protected $description = 'Criar notificação de teste via sistema admin';

    public function handle()
    {
        $membroId = $this->argument('membro_id');

        $this->info('=== CRIANDO NOTIFICAÇÃO VIA SISTEMA ADMIN ===');

        // Buscar membro
        $membro = Membro::find($membroId);
        if (!$membro) {
            $this->error("Membro com ID {$membroId} não encontrado!");
            return;
        }

        $this->info("Membro: {$membro->nome} (ID: {$membro->id})");
        $this->info("Email: {$membro->email}");

        // Simular dados do request
        $requestData = [
            'titulo' => 'Notificação de Teste via Admin',
            'mensagem' => 'Esta notificação foi criada através do sistema admin em ' . now()->format('d/m/Y H:i:s'),
            'tipo' => 'info',
            'prioridade' => 'normal',
            'destinatario_tipo' => 'membro',
            'destinatario_id' => $membroId,
            'agendada_para' => null,
        ];

        // Simular o método storeNotification do SystemController
        $dadosNotificacao = [
            'titulo' => $requestData['titulo'],
            'mensagem' => $requestData['mensagem'],
            'tipo' => $requestData['tipo'],
            'prioridade' => $requestData['prioridade'],
            'destinatario_tipo' => $requestData['destinatario_tipo'],
            'destinatario_id' => $requestData['destinatario_id'],
            'enviada_por' => 1, // Admin
            'agendada_para' => $requestData['agendada_para'],
        ];

        // Se for para um membro específico, buscar o usuário associado
        if ($requestData['destinatario_tipo'] === 'membro' && $requestData['destinatario_id']) {
            $membro = Membro::find($requestData['destinatario_id']);
            if ($membro && $membro->email) {
                $user = User::where('email', $membro->email)->first();
                if ($user) {
                    $dadosNotificacao['user_id'] = $user->id;
                    $this->info("Usuário associado: {$user->name} (ID: {$user->id})");
                } else {
                    $this->warn("Nenhum usuário encontrado com email: {$membro->email}");
                }
            }
            // Adicionar dados extras para membro
            $dadosNotificacao['dados_extras'] = ['membro_id' => $requestData['destinatario_id']];
        }

        $notificacao = Notificacao::create($dadosNotificacao);

        $this->info("Notificação criada com ID: {$notificacao->id}");
        $this->info("User ID associado: " . ($notificacao->user_id ?? 'Nenhum'));
        $this->info("Destinatário tipo: {$notificacao->destinatario_tipo}");
        $this->info("Destinatário ID: {$notificacao->destinatario_id}");

        // Verificar se a notificação aparece para o usuário
        if (isset($dadosNotificacao['user_id'])) {
            $user = User::find($dadosNotificacao['user_id']);
            $notificacoesUsuario = $user->notificacoes()->where('lida', false)->count();
            $this->info("Notificações não lidas do usuário: {$notificacoesUsuario}");

            // Testar o serviço de notificações
            $notificacoes = \App\Services\NotificacaoService::getNotificacoesUsuario($user);
            $this->info("Notificações retornadas pelo serviço: {$notificacoes->count()}");

            $count = \App\Services\NotificacaoService::countNotificacoesNaoLidas($user);
            $this->info("Contagem pelo serviço: {$count}");
        }

        $this->info('=== FIM DO TESTE ===');
    }
}
