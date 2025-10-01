<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se o sistema está instalado
        if (!$this->isInstalled()) {
            // Se não está instalado e não está acessando o instalador
            if (!$request->is('install*')) {
                return redirect('/install.php');
            }
        } else {
            // Se está instalado e está tentando acessar o instalador
            if ($request->is('install*')) {
                return redirect('/');
            }
        }

        return $next($request);
    }

    /**
     * Verificar se o sistema está instalado
     */
    private function isInstalled(): bool
    {
        // Verificar arquivo de instalação
        if (file_exists(storage_path('installed'))) {
            return true;
        }

        // Verificar se existe pelo menos um usuário no banco
        try {
            $userCount = \DB::table('users')->count();
            return $userCount > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
} 