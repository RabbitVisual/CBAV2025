<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Membro, Transacao, Campanha, Ministerio, Notificacao, Devocional};
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
     * Dashboard principal administrativo
     */
    public function index()
    {
        $user = Auth::user();

        $data = [
            'estatisticas' => $this->getEstatisticasGerais(),
            'doacoesRecentes' => $this->getDoacoesRecentes(),
            'campanhasAtivas' => $this->getCampanhasAtivas(),
            'ministeriosComEstatisticas' => $this->getMinisteriosComEstatisticas(),
            'aniversariantesProximos' => $this->getAniversariantesProximos(),
            'notificacoesRecentes' => $this->getNotificacoesRecentes(),
            'devocionalHoje' => $this->getDevocionalHoje(),
            'versiculoDoDia' => $this->getVersiculoDoDia(),
            'sistemaStatus' => $this->getSistemaStatus(),
            'quizEstatisticas' => $this->getQuizEstatisticas(),
            'ebdEstatisticas' => $this->getEbdEstatisticas(),
        ];

        return view('admin.dashboard.index', $data);
    }

    /**
     * Obter estatísticas gerais
     */
    private function getEstatisticasGerais()
    {
        $hoje = now();
        $inicioMes = $hoje->startOfMonth();
        $fimMes = $hoje->endOfMonth();

        return [
            'total_membros' => Membro::where('ativo', true)->count(),
            'membros_ativos' => Membro::where('ativo', true)->count(),
            'membros_inativos' => Membro::where('ativo', false)->count(),
            'total_doacoes' => Transacao::where('status', 'confirmado')->count(),
            'valor_total_doacoes' => Transacao::where('status', 'confirmado')->sum('valor'),
            'doacoes_mes' => Transacao::where('status', 'confirmado')
                ->whereBetween('created_at', [$inicioMes, $fimMes])->count(),
            'valor_mes' => Transacao::where('status', 'confirmado')
                ->whereBetween('created_at', [$inicioMes, $fimMes])->sum('valor'),
            'campanhas_ativas' => Campanha::where('ativo', true)->count(),
            'ministerios_ativos' => Ministerio::where('ativo', true)->count(),
            'notificacoes_nao_lidas' => \App\Models\Notification::where('status', 'sent')->count(),
            'devocionais_ativos' => Devocional::where('ativo', true)->count(),
            'quiz_perguntas' => \App\Models\EbdQuizPergunta::ativas()->count(),
            'quiz_sessoes' => \App\Models\EbdQuizSessao::count(),
            'quiz_sessoes_hoje' => \App\Models\EbdQuizSessao::whereDate('created_at', today())->count(),
            'ebd_turmas' => \App\Models\EbdTurma::ativas()->count(),
            'ebd_alunos' => \App\Models\EbdAluno::ativos()->count(),
            'ebd_professores' => \App\Models\EbdProfessor::ativos()->count(),
        ];
    }

    /**
     * Obter doações recentes
     */
    private function getDoacoesRecentes()
    {
        return Transacao::with(['membro', 'campanha'])
            ->where('status', 'confirmado')
            ->orderBy('created_at', 'desc')
            ->limit(10)
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
            ->limit(5)
            ->get()
            ->map(function ($campanha) {
                $campanha->dias_restantes = $this->calcularDiasRestantes($campanha);
                return $campanha;
            });
    }

    /**
     * Obter ministérios com estatísticas
     */
    private function getMinisteriosComEstatisticas()
    {
        return Ministerio::with(['departamentos.cargos'])
            ->where('ativo', true)
            ->get()
            ->map(function ($ministerio) {
                $ministerio->total_membros = $ministerio->getMembrosCountAttribute();
                $ministerio->total_departamentos = $ministerio->departamentos()->count();
                $ministerio->total_cargos = $ministerio->departamentos->sum(function ($departamento) {
                    return $departamento->cargos()->count();
                });
                return $ministerio;
            })
            ->sortByDesc('total_membros')
            ->take(5);
    }

    /**
     * Obter aniversariantes próximos
     */
    private function getAniversariantesProximos()
    {
        $hoje = now();
        $proximos30Dias = $hoje->copy()->addDays(30);

        return Membro::where('ativo', true)
            ->whereRaw('DATE_FORMAT(data_nascimento, "%m-%d") BETWEEN ? AND ?', [
                $hoje->format('m-d'),
                $proximos30Dias->format('m-d')
            ])
            ->orderByRaw('DATE_FORMAT(data_nascimento, "%m-%d")')
            ->limit(10)
            ->get()
            ->map(function ($membro) {
                $membro->idade = $membro->data_nascimento ? $membro->data_nascimento->age : null;
                $membro->dias_para_aniversario = $this->calcularDiasParaAniversario($membro);
                return $membro;
            });
    }

    /**
     * Obter notificações recentes
     */
    private function getNotificacoesRecentes()
    {
        return \App\Models\Notification::with(['sender'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Obter devocional de hoje
     */
    private function getDevocionalHoje()
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
     * Obter estatísticas do quiz bíblico
     */
    private function getQuizEstatisticas()
    {
        return [
            'melhores_pontuacoes' => \App\Models\EbdQuizSessao::with('user')
                ->orderBy('pontuacao_total', 'desc')
                ->limit(5)
                ->get(),
            'sessoes_recentes' => \App\Models\EbdQuizSessao::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'estatisticas_nivel' => [
                'facil' => \App\Models\EbdQuizSessao::porNivel('facil')->count(),
                'medio' => \App\Models\EbdQuizSessao::porNivel('medio')->count(),
                'dificil' => \App\Models\EbdQuizSessao::porNivel('dificil')->count(),
            ]
        ];
    }

    /**
     * Obter estatísticas da EBD
     */
    private function getEbdEstatisticas()
    {
        return [
            'turmas_ativas' => \App\Models\EbdTurma::ativas()->count(),
            'alunos_ativos' => \App\Models\EbdAluno::ativos()->count(),
            'professores_ativos' => \App\Models\EbdProfessor::ativos()->count(),
            'licoes_ativas' => \App\Models\EbdLicao::ativas()->count(),
            'aulas_agendadas' => \App\Models\EbdAula::agendadas()->count(),
            'avaliacoes_ativas' => \App\Models\EbdAvaliacao::ativas()->count(),
        ];
    }

    /**
     * Obter status do sistema
     */
    private function getSistemaStatus()
    {
        $status = $this->devocionalService->verificarStatus();
        
        return [
            'biblia_offline' => $status['biblia_offline'] ?? false,
            'devocional_hoje' => $status['devocional_hoje'] ?? false,
            'cache_status' => $this->verificarCacheStatus(),
            'storage_status' => $this->verificarStorageStatus(),
        ];
    }

    /**
     * Verificar status do cache
     */
    private function verificarCacheStatus()
    {
        try {
            $cache = cache()->store();
            $cache->put('test', 'test', 1);
            $test = $cache->get('test');
            return $test === 'test';
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verificar status do storage
     */
    private function verificarStorageStatus()
    {
        try {
            $disk = \Storage::disk('public');
            return $disk->exists('test') || $disk->put('test', 'test');
        } catch (\Exception $e) {
            return false;
        }
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
     * Calcular dias para aniversário
     */
    private function calcularDiasParaAniversario($membro)
    {
        if (!$membro->data_nascimento) {
            return null;
        }

        $aniversario = $membro->data_nascimento->copy()->setYear(now()->year);
        
        if ($aniversario->isPast()) {
            $aniversario->addYear();
        }

        return now()->diffInDays($aniversario, false);
    }
}