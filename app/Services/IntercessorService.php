<?php

namespace App\Services;

use App\Models\{PedidoOracao, Intercessao, User, Membro};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class IntercessorService
{
    // --- MÉTODOS DE ADMIN ---

    public function getAdminDashboardData(): array
    {
        return [
            'estatisticas' => [
                'total_pendentes' => PedidoOracao::where('status', 'pendente')->count(),
                'total_em_oracao' => PedidoOracao::where('status', 'em_oracao')->count(),
                'total_atendidos' => PedidoOracao::where('status', 'atendido')->count(),
                'minhas_intercessoes' => Intercessao::where('user_id', Auth::id())->count(),
            ],
            'pedidosPendentes' => PedidoOracao::where('status', 'pendente')->with(['membro.user', 'intercessores.user'])->latest()->take(10)->get(),
            'pedidosEmOracao' => PedidoOracao::where('status', 'em_oracao')->with(['membro.user', 'intercessores.user'])->latest()->take(10)->get(),
        ];
    }

    public function getPedidos(Request $request): LengthAwarePaginator
    {
        $query = PedidoOracao::with(['membro.user', 'intercessores']);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) =>
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%")
                  ->orWhereHas('membro', fn($mq) => $mq->where('nome', 'like', "%{$search}%"))
            );
        }
        return $query->latest()->paginate(15);
    }

    public function registrarIntercessao(PedidoOracao $pedido, array $data, User $user): Intercessao
    {
        $intercessao = $pedido->intercessoes()->create(['user_id' => $user->id] + $data);
        if ($pedido->status === 'pendente') $this->atualizarStatus($pedido, 'em_oracao');
        return $intercessao;
    }

    public function atualizarStatus(PedidoOracao $pedido, string $novoStatus): bool
    {
        return $pedido->update(['status' => $novoStatus]);
    }

    // --- MÉTODOS DE MEMBRO ---

    public function getMemberPedidos(Membro $membro, Request $request): LengthAwarePaginator
    {
        $query = $membro->pedidosOracao();
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('categoria')) $query->where('categoria', $request->categoria);
        return $query->latest()->paginate(10);
    }

    public function getSharedPedidos(Membro $membro)
    {
        return PedidoOracao::where('membro_id', '!=', $membro->id)
            ->where('pode_compartilhar', true)
            ->where('anonimo', false)
            ->with('membro.user')
            ->latest()
            ->limit(5)
            ->get();
    }

    public function createPedido(array $data, User $user): PedidoOracao
    {
        if (!$user->membro) throw new \Exception('Usuário não possui um perfil de membro associado.');
        $data['membro_id'] = $user->membro->id;
        return PedidoOracao::create($data);
    }

    public function updatePedido(PedidoOracao $pedido, array $data, User $user): bool
    {
        if ($pedido->membro_id !== $user->membro->id) throw new \Exception('Acesso negado.');
        if ($pedido->status !== 'pendente') throw new \Exception('Apenas pedidos pendentes podem ser editados.');
        return $pedido->update($data);
    }

    public function deletePedido(PedidoOracao $pedido, User $user): bool
    {
        if ($pedido->membro_id !== $user->membro->id) throw new \Exception('Acesso negado.');
        if ($pedido->status !== 'pendente') throw new \Exception('Apenas pedidos pendentes podem ser excluídos.');
        return $pedido->delete();
    }

    public function marcarAtendido(PedidoOracao $pedido, User $user): bool
    {
        if ($pedido->membro_id !== $user->membro->id) throw new \Exception('Acesso negado.');
        return $this->atualizarStatus($pedido, 'atendido');
    }

    public function participarIntercessao(PedidoOracao $pedido, array $data, User $user): Intercessao
    {
        $podeParticipar = $pedido->membro_id === $user->membro->id || ($pedido->pode_compartilhar && !$pedido->anonimo);
        if (!$podeParticipar) throw new \Exception('Você não pode participar da intercessão deste pedido.');

        if ($pedido->intercessores()->where('user_id', $user->id)->exists()) {
            throw new \Exception('Você já participou da intercessão deste pedido.');
        }

        return $this->registrarIntercessao($pedido, $data, $user);
    }
}