<?php

namespace App\Services;

use App\Models\{Transacao, Campanha, User};
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class FinanceService
{
    // --- MÉTODOS DE ADMIN ---

    public function getAdminDashboardData(): array
    {
        $estatisticas = [
            'total_recebido' => Transacao::confirmed()->income()->sum('valor'),
            'total_despesas' => Transacao::confirmed()->expense()->sum('valor'),
            'total_pendente' => Transacao::pending()->sum('valor'),
            'campanhas_ativas' => Campanha::where('status', 'ativa')->count(),
        ];
        $estatisticas['saldo_atual'] = $estatisticas['total_recebido'] - $estatisticas['total_despesas'];

        return [
            'estatisticas' => $estatisticas,
            'transacoesRecentes' => Transacao::with(['user', 'campanha'])->latest()->limit(10)->get(),
            'campanhasAtivas' => Campanha::where('status', 'ativa')->withCount('transacoes')->latest()->limit(5)->get(),
        ];
    }

    public function getTransactions(Request $request): array
    {
        $query = Transacao::with(['user', 'campanha']);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) =>
                $q->where('descricao', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"))
            );
        }
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('tipo')) $query->where('tipo', $request->tipo);

        return ['transacoes' => $query->latest()->paginate(20)];
    }

    public function createTransaction(array $data): Transacao
    {
        return Transacao::create($data);
    }

    public function updateTransaction(Transacao $transacao, array $data): bool
    {
        return $transacao->update($data);
    }

    public function getCampaigns(Request $request)
    {
        $query = Campanha::withCount('transacoes');
        if ($request->filled('search')) $query->where('titulo', 'like', '%' . $request->search . '%');
        if ($request->filled('status')) $query->where('status', $request->status);
        return $query->latest()->paginate(15);
    }

    public function createCampaign(array $data): Campanha
    {
        return Campanha::create($data);
    }

    public function updateCampaign(Campanha $campanha, array $data): bool
    {
        return $campanha->update($data);
    }

    // --- MÉTODOS DE DOAÇÃO (MEMBRO & PÚBLICO) ---

    public function getMemberDonationHistory(User $user, Request $request)
    {
        $query = $user->transacoes()->with('campanha');
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('descricao', 'like', "%{$search}%")->orWhereHas('campanha', fn($cq) => $cq->where('titulo', 'like', "%{$search}%")));
        }
        return $query->latest()->paginate(20);
    }

    public function getMemberDonationStats(User $user): array
    {
        return [
            'total_doado' => $user->transacoes()->income()->confirmed()->sum('valor'),
            'total_doacoes' => $user->transacoes()->income()->count(),
            'doacoes_mes' => $user->transacoes()->income()->confirmed()->where('created_at', '>=', now()->startOfMonth())->sum('valor'),
        ];
    }

    public function getActiveCampaigns(): Collection
    {
        return Campanha::where('ativo', true)->where('data_fim', '>=', now())->orderBy('data_fim', 'asc')->get();
    }

    public function processMemberDonation(User $user, array $data): Transacao
    {
        $data['user_id'] = $user->id;
        $data['tipo'] = 'entrada';
        $data['status'] = 'pendente';
        $data['data_transacao'] = now();
        $data['dados_extras'] = ['tipo_doador' => 'membro', 'gateway' => $data['gateway']];

        return $this->createTransaction($data);
    }

    public function processPublicDonation(array $data): Transacao
    {
        $data['tipo'] = 'entrada';
        $data['status'] = 'pendente';
        $data['data_transacao'] = now();
        $data['dados_extras'] = ['nome' => $data['nome'], 'email' => $data['email'], 'tipo_doador' => 'publico', 'gateway' => $data['gateway']];

        return $this->createTransaction($data);
    }
}