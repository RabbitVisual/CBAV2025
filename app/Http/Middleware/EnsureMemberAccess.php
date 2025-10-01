<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Membro;
use Symfony\Component\HttpFoundation\Response;

class EnsureMemberAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se não estiver autenticado, continuar normalmente
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        
        // Verificar se o usuário tem um membro associado
        $membro = $user->membro;

        // Se não tem membro associado, criar um perfil básico
        if (!$membro) {
            try {
                // Verificar se já existe um membro com este email
                $existingMembro = Membro::where('email', $user->email)->first();
                
                if (!$existingMembro) {
                    // Criar membro básico
                    Membro::create([
                        'nome' => $user->name,
                        'email' => $user->email,
                        'data_nascimento' => now()->subYears(25), // Data padrão
                        'telefone' => '',
                        'endereco' => '',
                        'cidade' => '',
                        'estado' => '',
                        'cep' => '',
                        'data_batismo' => null,
                        'ativo' => true,
                        'data_ingresso' => now(),
                        'observacoes' => 'Perfil criado automaticamente pelo middleware.',
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Erro ao criar perfil de membro no middleware: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
} 