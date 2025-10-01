<?php

namespace App\Services;

use App\Models\{Transacao, Campanha, Membro, User};
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class FinanceService
{
    // --- MÉTODOS DE ADMIN ---

    public function getAdminDashboardData(): array
    {
        $estatisticas = [
            'total_recebido' => Transacao::where('status', 'confirmado')->where('tipo', 'entrada')->sum('valor'),
            'total_despesas' => Transacao::where('status', 'confirmado')->where('tipo', 'saida')->sum('valor'),
            'total_pendente' => Transacao::where('status', 'pendente')->sum('valor'),
            'campanhas_ativas' => Campanha::where('status', 'ativa')->count(),
        ];
        $estatisticas['saldo_atual'] = $estatisticas['total_recebido'] - $estatisticas['total_despesas'];

        return [
            'estatisticas' => $estatisticas,
            'transacoesRecentes' => Transacao::with(['membro.user', 'campanha'])->latest()->limit(10)->get(),
            'campanhasAtivas' => Campanha::where('status', 'ativa')->withCount('transacoes')->latest()->limit(5)->get(),
        ];
    }

    public function getTransactions(Request $request): array
    {
        $query = Transacao::with(['membro.user', 'campanha']);
        if ($request->filled('search')) $query->where('descricao', 'like', '%' . $request->search . '%');
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('tipo')) $query->where('tipo', $request->tipo);
        if ($request->filled('data_inicio')) $query->whereDate('data_transacao', '>=', $request->data_inicio);
        if ($request->filled('data_fim')) $query->whereDate('data_transacao', '<=', $request->data_fim);

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

    // --- MÉTODOS DE MEMBRO (DOAÇÕES) ---

    public function getMemberDonationHistory(Membro $membro, Request $request)
    {
        $query = $membro->transacoes()->with('campanha');
        if ($request->filled('periodo')) $query->where('created_at', '>=', now()->subDays((int)$request->periodo));
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('descricao', 'like', "%{$search}%")->orWhereHas('campanha', fn($cq) => $cq->where('titulo', 'like', "%{$search}%")));
        }
        return $query->latest()->paginate(20);
    }

    public function getMemberDonationStats(Membro $membro): array
    {
        return [
            'total_doado' => $membro->transacoes()->where('tipo', 'entrada')->where('status', 'confirmado')->sum('valor'),
            'total_doacoes' => $membro->transacoes()->where('tipo', 'entrada')->count(),
            'doacoes_mes' => $membro->transacoes()->where('tipo', 'entrada')->where('status', 'confirmado')->where('created_at', '>=', now()->startOfMonth())->sum('valor'),
        ];
    }

    public function getActiveCampaignsForMember(): Collection
    {
        return Campanha::where('ativo', true)
            ->where('data_fim', '>=', now())
            ->orderBy('data_fim', 'asc')
            ->get()
            ->map(function ($campanha) {
                $campanha->dias_restantes = now()->diffInDays($campanha->data_fim, false);
                return $campanha;
            });
    }

    public function processMemberDonation(User $user, array $data): Transacao
    {
        if (!$user->membro) {
            throw new \Exception('Perfil de membro não encontrado.');
        }

        $data['membro_id'] = $user->membro->id;
        $data['tipo'] = 'entrada';
        $data['status'] = 'pendente';
        $data['data_transacao'] = now();
        $data['dados_extras'] = ['tipo_doador' => 'membro', 'gateway' => $data['gateway']];

        return $this->createTransaction($data);
    }

    // --- MÉTODOS PÚBLICOS ---

    public function processPublicDonation(array $data): Transacao
    {
        $data['tipo'] = 'entrada';
        $data['status'] = 'pendente';
        $data['data_transacao'] = now();
        $data['dados_extras'] = ['nome' => $data['nome'], 'email' => $data['email'], 'tipo_doador' => 'publico', 'gateway' => $data['gateway']];

        return $this->createTransaction($data);
    }
}