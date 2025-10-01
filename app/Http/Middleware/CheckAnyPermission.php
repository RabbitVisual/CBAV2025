<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\PermissionHelper;

class CheckAnyPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $permissionArray = explode('|', $permissions);

        // Verificar se o usuário tem pelo menos uma das permissões
        $hasAnyPermission = false;
        foreach ($permissionArray as $permission) {
            if ($user->hasPermissionTo(trim($permission))) {
                $hasAnyPermission = true;
                break;
            }
        }

        if (!$hasAnyPermission) {
            // Se não tem nenhuma das permissões, verificar se tem acesso admin geral
            if (!PermissionHelper::hasAdminAccess()) {
                // Redirecionar para área de membros se não tem acesso admin
                return redirect('/membro/dashboard')->with('error', 'Você não tem permissão para acessar esta área.');
            }
            
            // Se tem acesso admin mas não tem as permissões específicas
            return redirect('/admin')->with('error', 'Você não tem permissão para acessar esta funcionalidade.');
        }

        return $next($request);
    }
} 