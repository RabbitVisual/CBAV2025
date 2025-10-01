<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Membro, Campanha, Ministerio, Transacao, Notificacao};
use App\Services\{DevocionalService, BibleService};
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $devocionalService;
    protected $bibleService;

    public function __construct()
    {
        $this->devocionalService = new DevocionalService();
        $this->bibleService = new BibleService();
    }

    /**
     * Dashboard principal do membro
     */
    public function index()
    {
        $user = Auth::user();
        
        // Verificar se o usuário tem um membro associado
        $membro = $user->membro;

        // Se não tem membro associado, tentar criar um perfil básico
        if (!$membro) {
            $membro = $this->createBasicMemberProfile($user);
            
            if (!$membro) {
                return redirect()->route('member.profile.index')
                    ->with('error', 'Erro ao criar perfil de membro. Tente novamente.');
            }
        }

        // Obter versículo aleatório da Bíblia offline
        $versiculoAleatorio = $this->getVersiculoAleatorio();

        $data = [
            'membro' => $membro,
            'estatisticas' => $this->getEstatisticasMembro($membro),
            'doacoesRecentes' => $this->getDoacoesRecentes($membro),
            'campanhasAtivas' => $this->getCampanhasAtivas(),
            'ministeriosDisponiveis' => $this->getMinisteriosDisponiveis(),
            'solicitacoesPendentes' => $this->getSolicitacoesPendentes($membro),
            'devocionalDiario' => $this->getDevocionalDiario(),
            'versiculoDoDia' => $this->getVersiculoDoDia(),
            'oracaoDoDia' => $this->getOracaoDoDia(),
            'notificacoesRecentes' => $this->getNotificacoesRecentes($user),
            'versiculoAleatorio' => $versiculoAleatorio,
        ];

        return view('member.dashboard.index', $data);
    }

    /**
     * Obter estatísticas do membro
     */
    private function getEstatisticasMembro($membro)
    {
        $totalDoacoes = $membro->transacoes()->where('status', 'confirmado')->count();
        $valorTotalDoacoes = $membro->transacoes()->where('status', 'confirmado')->sum('valor');
        
        // Usar o relacionamento correto através dos cargos
        $ministeriosAtivos = $membro->cargos()
            ->whereHas('departamento.ministerio', function($query) {
                $query->where('ativo', true);
            })
            ->where('membro_cargo.ativo', true)
            ->count();
            
        $campanhasParticipadas = $membro->transacoes()->where('status', 'confirmado')
            ->whereNotNull('campanha_id')->distinct('campanha_id')->count();

        // Solicitações pendentes
        $solicitacoesPendentes = $membro->solicitacoesMinisterio()
            ->where('status', 'pendente')
            ->count();

        // Notificações não lidas
        $notificacoesNaoLidas = $membro->user ? $membro->user->unreadNotifications()->count() : 0;

        return [
            'total_doacoes' => $totalDoacoes,
            'valor_total' => $valorTotalDoacoes,
            'ministerios_participando' => $ministeriosAtivos,
            'campanhas_participadas' => $campanhasParticipadas,
            'solicitacoes_pendentes' => $solicitacoesPendentes,
            'notificacoes_nao_lidas' => $notificacoesNaoLidas,
        ];
    }

    /**
     * Obter doações recentes do membro
     */
    private function getDoacoesRecentes($membro)
    {
        return $membro->transacoes()
            ->with('campanha')
            ->where('status', 'confirmado')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Obter campanhas ativas
     */
    private function getCampanhasAtivas()
    {
        return Campanha::where('ativo', true)
            ->where('data_fim', '>=', now())
            ->orderBy('data_fim', 'asc')
            ->limit(3)
            ->get()
            ->map(function ($campanha) {
                $campanha->dias_restantes = $this->calcularDiasRestantes($campanha);
                return $campanha;
            });
    }

    /**
     * Obter ministérios disponíveis
     */
    private function getMinisteriosDisponiveis()
    {
        return Ministerio::with(['departamentos.cargos'])
            ->where('ativo', true)
            ->get()
            ->map(function ($ministerio) {
                $ministerio->total_membros = $ministerio->membros()->where('membros.ativo', true)->count();
                return $ministerio;
            });
    }

    /**
     * Obter solicitações pendentes
     */
    private function getSolicitacoesPendentes($membro)
    {
        return $membro->solicitacoesMinisterio()
            ->with('ministerio')
            ->where('status', 'pendente')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obter devocional do dia
     */
    private function getDevocionalDiario()
    {
        return $this->devocionalService->getDevocionalDoDia();
    }

    /**
     * Obter versículo do dia
     */
    private function getVersiculoDoDia()
    {
        return $this->devocionalService->getVersiculoDoDia();
    }

    /**
     * Obter oração do dia
     */
    private function getOracaoDoDia()
    {
        return $this->devocionalService->getOracaoDoDia();
    }

    /**
     * Obter notificações recentes
     */
    private function getNotificacoesRecentes($user)
    {
        return $user->unreadNotifications()
            ->limit(5)
            ->get();
    }

    /**
     * Calcular dias restantes de uma campanha
     */
    private function calcularDiasRestantes($campanha)
    {
        if (!$campanha->data_fim) {
            return null;
        }

        $dias = now()->diffInDays($campanha->data_fim, false);
        return $dias > 0 ? $dias : 0;
    }

    /**
     * Obter versículo aleatório da Bíblia offline
     */
    private function getVersiculoAleatorio()
    {
        try {
            $versiculo = $this->bibleService->getRandomVerse();
            
            if ($versiculo) {
                return [
                    'texto' => $versiculo['texto'] ?? $versiculo['versiculo'] ?? 'Porque Deus amou o mundo de tal maneira que deu o seu Filho unigênito, para que todo aquele que nele crê não pereça, mas tenha a vida eterna.',
                    'referencia' => $versiculo['referencia'] ?? 'João 3:16',
                    'versao' => $versiculo['versao'] ?? 'Almeida Revista e Atualizada'
                ];
            }
        } catch (\Exception $e) {
            // Log do erro se necessário
        }

        // Versículo padrão caso não consiga obter da Bíblia offline
        return [
            'texto' => 'Porque Deus amou o mundo de tal maneira que deu o seu Filho unigênito, para que todo aquele que nele crê não pereça, mas tenha a vida eterna.',
            'referencia' => 'João 3:16',
            'versao' => 'Almeida Revista e Atualizada'
        ];
    }

    /**
     * Criar perfil básico de membro para usuário
     */
    private function createBasicMemberProfile($user)
    {
        try {
            // Verificar se já existe um membro com este email
            $membro = Membro::where('email', $user->email)->first();
            
            if (!$membro) {
                // Criar membro básico
                $membro = Membro::create([
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
                    'observacoes' => 'Perfil criado automaticamente pelo sistema.',
                ]);
            }
            
            return $membro;
        } catch (\Exception $e) {
            \Log::error('Erro ao criar perfil de membro: ' . $e->getMessage());
            return null;
        }
    }
}