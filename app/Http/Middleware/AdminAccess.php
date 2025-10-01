<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\PermissionHelper;

class AdminAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado.'
                ], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Verificar se tem acesso admin
        if (!PermissionHelper::hasAdminAccess()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para acessar esta área.'
                ], 403);
            }
            return redirect()->route('member.dashboard')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
} 