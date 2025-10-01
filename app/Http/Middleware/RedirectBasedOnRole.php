<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\PermissionHelper;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se não estiver autenticado, continuar normalmente
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $currentPath = $request->path();

        // Se já está na área correta, continuar
        if ($this->isUserInCorrectArea($user, $currentPath)) {
            return $next($request);
        }

        // Se está tentando acessar uma área sem permissão, redirecionar
        if ($this->isUserTryingToAccessUnauthorizedArea($user, $currentPath)) {
            $redirectPath = $this->getRedirectPathForUser($user);
            
            if ($redirectPath) {
                return redirect($redirectPath)->with('warning', 'Você foi redirecionado para uma área onde tem permissão de acesso.');
            }
        }

        return $next($request);
    }

    /**
     * Verificar se o usuário está na área correta
     */
    private function isUserInCorrectArea($user, $path): bool
    {
        // Super Admin pode acessar tudo
        if ($user->hasPermissionTo('admin master')) {
            return true;
        }

        // Área de membros - todos os usuários podem acessar
        if (str_starts_with($path, 'member/') || $path === 'member') {
            return true;
        }

        // Verificar se o usuário tem permissão para a área admin atual
        if (str_starts_with($path, 'admin/')) {
            $hasPermission = $this->hasPermissionForAdminArea($user, $path);
            return $hasPermission;
        }

        return false;
    }

    /**
     * Verificar se o usuário tem permissão para área admin específica
     */
    private function hasPermissionForAdminArea($user, $path): bool
    {
        $permissionMap = [
            'admin/people' => ['people.access'],
            'admin/finance' => ['finance.access'],
            'admin/system' => ['system.access'],
            'admin/devotionals' => ['devotionals.access'],
            'admin/council' => ['council.access'],
            'admin/intercessor' => ['intercessor.access'],
        ];

        foreach ($permissionMap as $pathPrefix => $permissions) {
            if (str_starts_with($path, $pathPrefix)) {
                return $user->hasAnyPermission($permissions);
            }
        }

        // Dashboard admin principal
        if ($path === 'admin' || $path === 'admin/') {
            return PermissionHelper::hasAdminAccess();
        }

        return false;
    }

    /**
     * Verificar se o usuário está tentando acessar área sem permissão
     */
    private function isUserTryingToAccessUnauthorizedArea($user, $path): bool
    {
        // Se está tentando acessar admin sem permissão
        if (str_starts_with($path, 'admin/') && !PermissionHelper::hasAdminAccess()) {
            return true;
        }

        return false;
    }

    /**
     * Obter caminho de redirecionamento baseado no perfil do usuário
     */
    private function getRedirectPathForUser($user): ?string
    {
        // Se tem acesso admin, redirecionar para área admin apropriada
        if (PermissionHelper::hasAdminAccess()) {
            return $this->getAdminRedirectPath($user);
        }

        // Verificar se tem permissões específicas de intercessor
        if ($user->hasAnyPermission(['intercessor.access'])) {
            return '/admin/intercessor';
        }

        // Por padrão, todos os usuários são membros
        return '/member';
    }

    /**
     * Obter caminho de redirecionamento para área admin
     */
    private function getAdminRedirectPath($user): string
    {
        // Super Admin - dashboard principal
        if ($user->hasPermissionTo('admin master')) {
            return '/admin';
        }

        // Verificar permissões específicas e redirecionar para área apropriada
        if ($user->hasAnyPermission(['people.access'])) {
            return '/admin/people';
        }

        if ($user->hasAnyPermission(['finance.access'])) {
            return '/admin/finance';
        }

        if ($user->hasAnyPermission(['system.access'])) {
            return '/admin/system';
        }

        if ($user->hasAnyPermission(['devotionals.access'])) {
            return '/admin/devotionals';
        }

        if ($user->hasAnyPermission(['council.access'])) {
            return '/admin/council';
        }

        if ($user->hasAnyPermission(['intercessor.access'])) {
            return '/admin/intercessor';
        }

        // Se tem acesso admin mas não tem área específica, ir para dashboard admin
        return '/admin';
    }
} 