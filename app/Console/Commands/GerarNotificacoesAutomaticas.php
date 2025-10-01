<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificacaoService;
use App\Models\Membro;
use App\Models\Transacao;
use App\Models\Campanha;
use App\Models\EbdAvaliacao;
use App\Models\PedidoOracao;
use Carbon\Carbon;

class GerarNotificacoesAutomaticas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificacoes:gerar-automaticas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera notificações automáticas do sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Gerando notificações automáticas...');

        try {
            $geradas = 0;

            // 1. Notificar aniversariantes do dia
            $aniversariantes = Membro::where('ativo', true)
                ->whereRaw('DATE_FORMAT(data_nascimento, "%m-%d") = ?', [now()->format('m-d')])
                ->get();

            foreach ($aniversariantes as $membro) {
                NotificacaoService::notificarAniversariante($membro);
                $geradas++;
            }

            if ($aniversariantes->count() > 0) {
                $this->info("🎂 {$aniversariantes->count()} aniversariante(s) notificado(s)");
            }

            // 2. Notificar novas transações (últimas 24h)
            $transacoesRecentes = Transacao::where('created_at', '>=', now()->subDay())
                ->where('notificado', false)
                ->get();

            foreach ($transacoesRecentes as $transacao) {
                NotificacaoService::notificarNovaTransacao($transacao);
                $transacao->update(['notificado' => true]);
                $geradas++;
            }

            if ($transacoesRecentes->count() > 0) {
                $this->info("💰 {$transacoesRecentes->count()} transação(ões) notificada(s)");
            }

            // 3. Notificar novas campanhas (últimas 24h)
            $campanhasRecentes = Campanha::where('created_at', '>=', now()->subDay())
                ->where('notificado', false)
                ->get();

            foreach ($campanhasRecentes as $campanha) {
                NotificacaoService::notificarNovaCampanha($campanha);
                $campanha->update(['notificado' => true]);
                $geradas++;
            }

            if ($campanhasRecentes->count() > 0) {
                $this->info("🎯 {$campanhasRecentes->count()} campanha(s) notificada(s)");
            }

            // 4. Notificar novas avaliações EBD (últimas 24h)
            $avaliacoesRecentes = EbdAvaliacao::where('created_at', '>=', now()->subDay())
                ->where('notificado', false)
                ->get();

            foreach ($avaliacoesRecentes as $avaliacao) {
                NotificacaoService::notificarNovaAvaliacaoEbd($avaliacao);
                $avaliacao->update(['notificado' => true]);
                $geradas++;
            }

            if ($avaliacoesRecentes->count() > 0) {
                $this->info("📚 {$avaliacoesRecentes->count()} avaliação(ões) EBD notificada(s)");
            }

            // 5. Notificar novos pedidos de oração (últimas 24h)
            $pedidosRecentes = PedidoOracao::where('created_at', '>=', now()->subDay())
                ->where('notificado', false)
                ->get();

            foreach ($pedidosRecentes as $pedido) {
                NotificacaoService::notificarNovoPedidoOracao($pedido);
                $pedido->update(['notificado' => true]);
                $geradas++;
            }

            if ($pedidosRecentes->count() > 0) {
                $this->info("🙏 {$pedidosRecentes->count()} pedido(s) de oração notificado(s)");
            }

            // 6. Notificar membros inativos (último acesso há mais de 30 dias)
            $membrosInativos = Membro::where('ativo', true)
                ->where('ultimo_acesso', '<', now()->subDays(30))
                ->where('notificado_inativo', false)
                ->get();

            foreach ($membrosInativos as $membro) {
                NotificacaoService::notificarMembro(
                    $membro->id,
                    'warning',
                    'Você está sendo lembrado',
                    'Faz tempo que você não acessa o sistema. Que tal dar uma olhada nas novidades?',
                    [
                        'categoria' => 'sistema',
                        'prioridade' => 'normal'
                    ]
                );
                $membro->update(['notificado_inativo' => true]);
                $geradas++;
            }

            if ($membrosInativos->count() > 0) {
                $this->info("⏰ {$membrosInativos->count()} membro(s) inativo(s) notificado(s)");
            }

            $this->info("✅ Total de {$geradas} notificação(ões) gerada(s) com sucesso!");

        } catch (\Exception $e) {
            $this->error('❌ Erro ao gerar notificações automáticas: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 