<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\PermissionHelper;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Verificar se o usuário tem a permissão específica
        if (!$user->hasPermissionTo($permission)) {
            // Se não tem a permissão, verificar se tem acesso admin geral
            if (!PermissionHelper::hasAdminAccess()) {
                // Redirecionar para área de membros se não tem acesso admin
                return redirect('/membro/dashboard')->with('error', 'Você não tem permissão para acessar esta área.');
            }
            
            // Se tem acesso admin mas não tem a permissão específica
            return redirect('/admin')->with('error', 'Você não tem permissão para acessar esta funcionalidade.');
        }

        return $next($request);
    }
}
