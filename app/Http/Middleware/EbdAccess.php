<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\PermissionHelper;

class EbdAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Verificar se tem acesso EBD
        if (!$user->hasPermissionTo('ebd.access')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Você não tem permissão para acessar a área EBD.');
        }

        return $next($request);
    }
} 