<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificacoesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Compartilhar notificações com todas as views
        if (Auth::check()) {
            $user = Auth::user();
            
            try {
                // Buscar notificações usando o serviço (limitado para header)
                $notificacoes = NotificationService::getUserNotifications($user->id, ['limit' => 5]);
                $notificacoesNaoLidas = NotificationService::getUnreadCount($user->id);
                
                // Estatísticas adicionais para o header
                $estatisticasNotificacoes = [
                    'total' => $notificacoes->count(),
                    'nao_lidas' => $notificacoesNaoLidas,
                    'lidas' => $notificacoes->where('lida', true)->count(),
                    'urgentes' => $notificacoes->where('prioridade', 'urgente')->where('lida', false)->count(),
                ];
                
                // Compartilhar com todas as views
                view()->share('notificacoes', $notificacoes);
                view()->share('notificacoesNaoLidas', $notificacoesNaoLidas);
                view()->share('estatisticasNotificacoes', $estatisticasNotificacoes);
                
            } catch (\Exception $e) {
                // Em caso de erro, compartilhar valores vazios
                Log::error('Erro no middleware de notificações: ' . $e->getMessage());
                
                view()->share('notificacoes', collect());
                view()->share('notificacoesNaoLidas', 0);
                view()->share('estatisticasNotificacoes', [
                    'total' => 0,
                    'nao_lidas' => 0,
                    'lidas' => 0,
                    'urgentes' => 0,
                ]);
            }
        } else {
            // Se não está autenticado, compartilhar valores vazios
            view()->share('notificacoes', collect());
            view()->share('notificacoesNaoLidas', 0);
            view()->share('estatisticasNotificacoes', [
                'total' => 0,
                'nao_lidas' => 0,
                'lidas' => 0,
                'urgentes' => 0,
            ]);
        }

        return $next($request);
    }
}
