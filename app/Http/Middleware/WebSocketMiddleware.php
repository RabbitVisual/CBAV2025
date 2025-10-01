<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WebSocketMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se é uma conexão WebSocket
        if ($request->header('Upgrade') === 'websocket') {
            // Aqui você pode adicionar lógica específica para WebSockets
            // Por exemplo, verificar autenticação, rate limiting, etc.
            
            // Verificar se o usuário está autenticado
            if (!Auth::check()) {
                return response('Unauthorized', 401);
            }
            
            // Adicionar headers específicos para WebSocket
            $response = $next($request);
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
            
            return $response;
        }
        
        return $next($request);
    }
} 