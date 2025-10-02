<?php

namespace App\Services;

use App\Models\{PrayerRequest, Intercession, User};
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
                'total_pendentes' => PrayerRequest::pending()->count(),
                'total_em_oracao' => PrayerRequest::inPrayer()->count(),
                'total_atendidos' => PrayerRequest::answered()->count(),
                'minhas_intercessoes' => Intercession::where('user_id', Auth::id())->count(),
            ],
            'pedidosPendentes' => PrayerRequest::pending()->with(['user.profile', 'intercessions.user'])->latest()->take(10)->get(),
            'pedidosEmOracao' => PrayerRequest::inPrayer()->with(['user.profile', 'intercessions.user'])->latest()->take(10)->get(),
        ];
    }

    public function getPrayerRequests(Request $request): LengthAwarePaginator
    {
        $query = PrayerRequest::with(['user.profile', 'intercessions']);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) =>
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"))
            );
        }
        return $query->latest()->paginate(15);
    }

    public function registerIntercession(PrayerRequest $prayerRequest, array $data, User $user): Intercession
    {
        $intercession = $prayerRequest->intercessions()->create(['user_id' => $user->id] + $data);
        if ($prayerRequest->status === 'pendente') {
            $this->updateStatus($prayerRequest, 'em_oracao');
        }
        return $intercession;
    }

    public function updateStatus(PrayerRequest $prayerRequest, string $newStatus): bool
    {
        return $prayerRequest->update(['status' => $newStatus]);
    }

    // --- MÉTODOS DE MEMBRO ---

    public function getMemberPrayerRequests(User $user, Request $request): LengthAwarePaginator
    {
        $query = $user->prayerRequests();
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('categoria')) $query->where('categoria', $request->categoria);
        return $query->latest()->paginate(10);
    }

    public function getSharedPrayerRequests(User $user)
    {
        return PrayerRequest::where('user_id', '!=', $user->id)
            ->where('pode_compartilhar', true)
            ->where('anonimo', false)
            ->with('user.profile')
            ->latest()
            ->limit(5)
            ->get();
    }

    public function createPrayerRequest(array $data, User $user): PrayerRequest
    {
        return $user->prayerRequests()->create($data);
    }

    public function updatePrayerRequest(PrayerRequest $prayerRequest, array $data, User $user): bool
    {
        if ($prayerRequest->user_id !== $user->id) throw new \Exception('Acesso negado.');
        if ($prayerRequest->status !== 'pendente') throw new \Exception('Apenas pedidos pendentes podem ser editados.');
        return $prayerRequest->update($data);
    }

    public function deletePrayerRequest(PrayerRequest $prayerRequest, User $user): bool
    {
        if ($prayerRequest->user_id !== $user->id) throw new \Exception('Acesso negado.');
        if ($prayerRequest->status !== 'pendente') throw new \Exception('Apenas pedidos pendentes podem ser excluídos.');
        return $prayerRequest->delete();
    }

    public function markAsAnswered(PrayerRequest $prayerRequest, User $user): bool
    {
        if ($prayerRequest->user_id !== $user->id) throw new \Exception('Acesso negado.');
        return $this->updateStatus($prayerRequest, 'atendido');
    }

    public function participateInIntercession(PrayerRequest $prayerRequest, array $data, User $user): Intercession
    {
        $canParticipate = $prayerRequest->user_id === $user->id || ($prayerRequest->pode_compartilhar && !$prayerRequest->anonimo);
        if (!$canParticipate) throw new \Exception('Você não pode participar da intercessão deste pedido.');

        if ($prayerRequest->intercessions()->where('user_id', $user->id)->exists()) {
            throw new \Exception('Você já participou da intercessão deste pedido.');
        }

        return $this->registerIntercession($prayerRequest, $data, $user);
    }
}