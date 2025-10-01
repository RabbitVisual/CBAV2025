<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\CouncilSettingsHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CouncilSecurityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar se deve registrar logs
        if (CouncilSettingsHelper::shouldLogActivity()) {
            $this->logActivity($request);
        }

        // Verificar tempo de sessão
        $sessionTime = CouncilSettingsHelper::getSessionTime();
        if ($sessionTime > 0 && Auth::check()) {
            $lastActivity = session('last_activity');
            $now = time();
            
            if ($lastActivity && ($now - $lastActivity) > ($sessionTime * 60)) {
                Auth::logout();
                session()->flush();
                
                return redirect()->route('login')
                    ->with('error', 'Sessão expirada por inatividade.');
            }
            
            session(['last_activity' => $now]);
        }

        return $next($request);
    }

    /**
     * Registrar atividade se configurado
     */
    private function logActivity(Request $request)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();
        $data = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'timestamp' => now()->toISOString(),
        ];

        Log::channel('council_activity')->info('Council Activity', $data);
    }
} 