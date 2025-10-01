<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Membro;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\NotificacaoService;

class PublicMemberController extends Controller
{
    /**
     * Lista de membros públicos
     */
    public function index(Request $request)
    {
        $query = Membro::with(['user', 'cargos.departamento.ministerio'])
            ->whereHas('user', function($q) {
                $q->where('public_profile', true);
            });

        // Filtros
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('ministerio')) {
            $query->whereHas('cargos.departamento.ministerio', function($q) use ($request) {
                $q->where('ministerios.id', $request->ministerio);
            });
        }

        $membros = $query->where('ativo', true)->paginate(12);

        return view('member.public.members.index', compact('membros'));
    }

    /**
     * Visualizar perfil público de um membro
     */
    public function show(Membro $membro)
    {
        // Verificar se o perfil é público
        $user = $membro->user;
        if (!$user || !$user->public_profile) {
            abort(404, 'Perfil não encontrado ou não está disponível para visualização pública.');
        }

        // Carregar relacionamentos
        $membro->load(['user', 'cargos.departamento.ministerio']);

        return view('member.public.members.show', compact('membro'));
    }

    /**
     * Buscar membros por nome
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $membros = Membro::with(['user', 'cargos.departamento.ministerio'])
            ->whereHas('user', function($q) {
                $q->where('public_profile', true);
            })
            ->where('ativo', true)
            ->where('nome', 'like', '%' . $query . '%')
            ->limit(10)
            ->get()
            ->map(function ($membro) {
                return [
                    'id' => $membro->id,
                    'nome' => $membro->nome,
                    'email' => $membro->email,
                    'ministerios' => $membro->cargos->map(function ($cargo) {
                        return $cargo->departamento->ministerio->nome;
                    })->unique()->implode(', '),
                    'url' => route('member.public.members.show', $membro)
                ];
            });

        return response()->json($membros);
    }
} 