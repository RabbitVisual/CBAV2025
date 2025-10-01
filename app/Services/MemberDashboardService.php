<?php

namespace App\Services;

use App\Models\{User, Evento, Campanha, Ministerio, Notificacao, Transacao};
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MemberDashboardService
{
    protected $devocionalService;
    protected $bibleService;

    public function __construct(DevocionalService $devocionalService, BibleService $bibleService)
    {
        $this->devocionalService = $devocionalService;
        $this->bibleService = $bibleService;
    }

    /**
     * Coleta e retorna todos os dados necessários para o dashboard do membro.
     *
     * @param User $user
     * @return array
     */
    public function getDataForDashboard(User $user): array
    {
        $membro = $user->membro;

        // Se o membro não existir, cria um perfil básico.
        if (!$membro) {
            // Esta lógica pode ser movida para um evento de usuário registrado no futuro.
            $membro = \App\Models\Membro::firstOrCreate(
                ['email' => $user->email],
                ['nome' => $user->name, 'ativo' => true, 'data_ingresso' => now()]
            );
            $user->load('membro');
        }

        return [
            'estatisticas' => $this->getEstatisticas($user, $membro),
            'campanhasAtivas' => $this->getCampanhasAtivas(),
            'doacoesRecentes' => $this->getDoacoesRecentes($membro),
            'ministeriosDisponiveis' => $this->getMinisteriosDisponiveis(),
            'devocionalDiario' => $this->devocionalService->getDevocionalDoDia(),
            'versiculoAleatorio' => $this->bibleService->getRandomVerse() ?? ['texto' => 'O Senhor é o meu pastor; nada me faltará.', 'referencia' => 'Salmos 23:1'],
        ];
    }

    /**
     * Retorna as principais estatísticas para o dashboard.
     */
    private function getEstatisticas(User $user, $membro): array
    {
        return [
            'notificacoes_nao_lidas' => $user->unreadNotifications()->count(),
            'proximos_eventos' => Evento::where('data_inicio', '>=', now())->count(),
            'total_doacoes' => $membro ? $membro->transacoes()->where('status', 'confirmado')->count() : 0,
            'valor_total' => $membro ? $membro->transacoes()->where('status', 'confirmado')->sum('valor') : 0,
            'ministerios_participando' => $membro ? $membro->cargos()->where('membro_cargo.ativo', true)->count() : 0,
            'solicitacoes_pendentes' => $membro ? $membro->solicitacoesMinisterio()->where('status', 'pendente')->count() : 0,
        ];
    }

    /**
     * Retorna as campanhas de doação ativas com cálculo de progresso.
     */
    private function getCampanhasAtivas(): \Illuminate\Database\Eloquent\Collection
    {
        return Campanha::where('ativo', true)
            ->where('data_fim', '>=', now())
            ->orderBy('data_fim', 'asc')
            ->limit(3)
            ->get()
            ->map(function ($campanha) {
                $campanha->dias_restantes = now()->diffInDays($campanha->data_fim, false);
                $progresso = ($campanha->meta > 0) ? ($campanha->arrecadado / $campanha->meta) * 100 : 0;
                $campanha->progresso_percentual = min($progresso, 100); // Garante que não passe de 100%
                return $campanha;
            });
    }

    /**
     * Retorna as doações recentes do membro.
     */
    private function getDoacoesRecentes($membro)
    {
        if (!$membro) return collect();

        return $membro->transacoes()
            ->with('campanha')
            ->where('status', 'confirmado')
            ->latest()
            ->limit(3)
            ->get();
    }

    /**
     * Retorna os ministérios disponíveis.
     */
    private function getMinisteriosDisponiveis()
    {
        return Ministerio::withCount('membros')
            ->where('ativo', true)
            ->limit(3)
            ->get();
    }
}