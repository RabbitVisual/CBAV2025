<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Notification;

class CreateTestNotifications extends Command
{
    protected $signature = 'notifications:create-test {user_id?}';
    protected $description = 'Criar notificações de teste para o usuário';

    public function handle()
    {
        $userId = $this->argument('user_id');

        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("Usuário com ID {$userId} não encontrado!");
                return;
            }
        } else {
            $user = User::first();
            if (!$user) {
                $this->error("Nenhum usuário encontrado no sistema!");
                return;
            }
        }

        $this->info("=== CRIANDO NOTIFICAÇÕES DE TESTE ===");
        $this->info("Usuário: {$user->name} (ID: {$user->id})");

        // Limpar notificações existentes do usuário
        Notificacao::where('user_id', $user->id)->delete();

        // Criar notificações de teste
        $notificacoes = [
            [
                'tipo' => 'info',
                'titulo' => 'Bem-vindo ao Sistema',
                'mensagem' => 'Seja bem-vindo ao sistema de gerenciamento da igreja! Esta é uma notificação de teste para verificar se o sistema está funcionando corretamente.',
                'categoria' => 'sistema',
                'prioridade' => 'normal',
                'lida' => false
            ],
            [
                'tipo' => 'success',
                'titulo' => 'Cadastro Realizado',
                'mensagem' => 'Seu cadastro foi realizado com sucesso no sistema. Agora você pode acessar todas as funcionalidades disponíveis.',
                'categoria' => 'sistema',
                'prioridade' => 'normal',
                'lida' => false
            ],
            [
                'tipo' => 'warning',
                'titulo' => 'Lembrete de Evento',
                'mensagem' => 'Você tem um evento agendado para amanhã às 19h. Não se esqueça de participar!',
                'categoria' => 'eventos',
                'prioridade' => 'alta',
                'lida' => false
            ],
            [
                'tipo' => 'error',
                'titulo' => 'Erro no Sistema',
                'mensagem' => 'Ocorreu um erro no sistema. Entre em contato com o administrador para resolver o problema.',
                'categoria' => 'sistema',
                'prioridade' => 'urgente',
                'lida' => false
            ],
            [
                'tipo' => 'info',
                'titulo' => 'Nova Campanha',
                'mensagem' => 'Uma nova campanha de doação foi criada. Participe e ajude nossa igreja!',
                'categoria' => 'financeiro',
                'prioridade' => 'normal',
                'lida' => false
            ],
            [
                'tipo' => 'success',
                'titulo' => 'Pagamento Confirmado',
                'mensagem' => 'Seu pagamento foi confirmado com sucesso. Obrigado pela sua contribuição!',
                'categoria' => 'financeiro',
                'prioridade' => 'alta',
                'lida' => false
            ],
            [
                'tipo' => 'warning',
                'titulo' => 'Reunião de Ministério',
                'mensagem' => 'Lembrete: Reunião do ministério amanhã às 20h. Sua presença é importante.',
                'categoria' => 'ministerio',
                'prioridade' => 'alta',
                'lida' => false
            ],
            [
                'tipo' => 'info',
                'titulo' => 'Devocional Diário',
                'mensagem' => 'Não se esqueça de ler o devocional de hoje. É uma ótima forma de começar o dia!',
                'categoria' => 'devocional',
                'prioridade' => 'normal',
                'lida' => false
            ]
        ];

        foreach ($notificacoes as $notificacao) {
            Notificacao::create(array_merge($notificacao, [
                'user_id' => $user->id
            ]));
        }

        $this->info("✓ " . count($notificacoes) . " notificações criadas com sucesso!");
        $this->info("✓ Acesse a página de notificações para ver as notificações de teste.");

        return 0;
    }
}
