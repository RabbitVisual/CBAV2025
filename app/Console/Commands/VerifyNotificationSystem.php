<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\User;
use App\Models\Membro;
use App\Services\NotificacaoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VerifyNotificationSystem extends Command
{
    protected $signature = 'notifications:verify';
    protected $description = 'Verificar e corrigir inconsistências no sistema de notificações';

    public function handle()
    {
        $this->info("=== VERIFICAÇÃO COMPLETA DO SISTEMA DE NOTIFICAÇÕES ===");

        $problemas = [];
        $correcoes = 0;

        // 1. Verificar estrutura da tabela
        $this->info("\n1. Verificando estrutura da tabela...");
        $columns = DB::select('SHOW COLUMNS FROM notificacaos');
        $columnNames = array_column($columns, 'Field');

        $requiredColumns = [
            'id',
            'user_id',
            'tipo',
            'titulo',
            'mensagem',
            'icone',
            'acao_url',
            'acao_texto',
            'lida',
            'lida_em',
            'dados_extras',
            'prioridade',
            'categoria',
            'destinatario_tipo',
            'destinatario_id',
            'enviada_por',
            'agendada_para',
            'enviada_em',
            'created_at',
            'updated_at'
        ];

        foreach ($requiredColumns as $column) {
            if (!in_array($column, $columnNames)) {
                $problemas[] = "Coluna '{$column}' não encontrada na tabela";
            }
        }

        if (empty($problemas)) {
            $this->info("✅ Estrutura da tabela está correta");
        }

        // 2. Verificar notificações com dados inválidos
        $this->info("\n2. Verificando notificações com dados inválidos...");
        $invalidNotifications = Notificacao::where(function ($q) {
            $q->whereNull('tipo')
                ->orWhereNull('titulo')
                ->orWhereNull('mensagem')
                ->orWhereNull('prioridade')
                ->orWhereNull('categoria');
        })->get();

        foreach ($invalidNotifications as $notification) {
            $updates = [];

            if (empty($notification->tipo)) {
                $updates['tipo'] = 'info';
            }
            if (empty($notification->prioridade)) {
                $updates['prioridade'] = 'normal';
            }
            if (empty($notification->categoria)) {
                $updates['categoria'] = 'sistema';
            }

            if (!empty($updates)) {
                $notification->update($updates);
                $correcoes++;
                $this->info("✓ Notificação ID {$notification->id} corrigida");
            }
        }

        // 3. Verificar relacionamentos
        $this->info("\n3. Verificando relacionamentos...");
        $orphanNotifications = Notificacao::whereNotNull('user_id')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('users')
                    ->whereRaw('users.id = notificacaos.user_id');
            })->get();

        foreach ($orphanNotifications as $notification) {
            $notification->update(['user_id' => null]);
            $correcoes++;
            $this->info("✓ Notificação ID {$notification->id} - user_id removido (usuário não existe)");
        }

        // 4. Verificar contadores
        $this->info("\n4. Verificando contadores...");
        $users = User::all();
        foreach ($users as $user) {
            $expectedCount = NotificacaoService::countNotificacoesNaoLidas($user);
            $actualCount = NotificacaoService::countNotificacoesNaoLidas($user);

            if ($expectedCount !== $actualCount) {
                $this->warn("⚠️  Usuário {$user->name} - Contador inconsistente: esperado {$expectedCount}, encontrado {$actualCount}");
            } else {
                $this->info("✅ Usuário {$user->name} - Contador correto: {$expectedCount}");
            }
        }

        // 5. Verificar middleware
        $this->info("\n5. Testando middleware...");
        try {
            $testUser = User::first();
            if ($testUser) {
                $notificacoes = NotificacaoService::getNotificacoesUsuario($testUser, 5);
                $count = NotificacaoService::countNotificacoesNaoLidas($testUser);
                $this->info("✅ Middleware funcionando - {$count} notificações não lidas para {$testUser->name}");
            }
        } catch (\Exception $e) {
            $problemas[] = "Erro no middleware: " . $e->getMessage();
        }

        // 6. Verificar JavaScript
        $this->info("\n6. Verificando arquivos JavaScript...");
        $jsFiles = [
            'resources/views/components/notification-dropdown.blade.php',
            'resources/views/admin/system/notifications/index.blade.php'
        ];

        foreach ($jsFiles as $file) {
            if (file_exists($file)) {
                $content = file_get_contents($file);
                if (strpos($content, 'toString()') !== false) {
                    $this->info("⚠️  Arquivo {$file} contém toString() - verificar se está protegido contra null");
                }
            }
        }

        // 7. Estatísticas finais
        $this->info("\n=== ESTATÍSTICAS FINAIS ===");
        $totalNotifications = Notificacao::count();
        $unreadNotifications = Notificacao::where('lida', false)->count();
        $sentNotifications = Notificacao::whereNotNull('enviada_em')->count();
        $pendingNotifications = Notificacao::whereNull('enviada_em')->count();
        $totalUsers = User::count();

        $this->info("📊 Estatísticas:");
        $this->info("   - Total de notificações: {$totalNotifications}");
        $this->info("   - Não lidas: {$unreadNotifications}");
        $this->info("   - Enviadas: {$sentNotifications}");
        $this->info("   - Pendentes: {$pendingNotifications}");
        $this->info("   - Usuários: {$totalUsers}");
        $this->info("   - Correções aplicadas: {$correcoes}");

        if (empty($problemas)) {
            $this->info("\n✅ Sistema de notificações está 100% coerente!");
        } else {
            $this->error("\n❌ Problemas encontrados:");
            foreach ($problemas as $problema) {
                $this->error("   - {$problema}");
            }
        }

        return 0;
    }
}
