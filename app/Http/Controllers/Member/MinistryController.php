<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Ministerio, Departamento, SolicitacaoMinisterio, Membro};

class MinistryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Lista de ministérios disponíveis
     */
    public function index()
    {
        $membro = auth()->user()->membro;
        
        if (!$membro) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Você precisa ter um perfil de membro para acessar esta área.');
        }

        $ministeriosDisponiveis = Ministerio::where('ativo', true)
            ->with(['departamentos', 'membros'])
            ->get();

        // Obter ministérios que o membro participa através dos cargos
        $ministeriosParticipando = Ministerio::join('departamentos', 'ministerios.id', '=', 'departamentos.ministerio_id')
            ->join('cargos', 'departamentos.id', '=', 'cargos.departamento_id')
            ->join('membro_cargo', 'cargos.id', '=', 'membro_cargo.cargo_id')
            ->where('membro_cargo.membro_id', $membro->id)
            ->where('membro_cargo.ativo', true)
            ->where('ministerios.ativo', true)
            ->select('ministerios.*')
            ->with('departamentos')  // Adicionar carregamento explícito
            ->get();

        $solicitacoesPendentes = SolicitacaoMinisterio::where('membro_id', $membro->id)
            ->where('status', 'pendente')
            ->with(['ministerio', 'cargo'])
            ->get();

        // Estatísticas para a view
        $ministeriosAtivos = Ministerio::where('ativo', true)->count();
        $participacoesConfirmadas = $ministeriosParticipando->count();

        // Variável que a view espera
        $meusMinisterios = $ministeriosParticipando;

        return view('member.ministries.index', compact(
            'ministeriosDisponiveis',
            'ministeriosParticipando',
            'solicitacoesPendentes',
            'ministeriosAtivos',
            'participacoesConfirmadas',
            'meusMinisterios'
        ));
    }

    /**
     * Lista de ministérios disponíveis para participação
     */
    public function available()
    {
        $membro = auth()->user()->membro;
        
        if (!$membro) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Você precisa ter um perfil de membro para acessar esta área.');
        }

        // Obter IDs dos ministérios que o membro já participa
        $ministeriosParticipando = Ministerio::join('departamentos', 'ministerios.id', '=', 'departamentos.ministerio_id')
            ->join('cargos', 'departamentos.id', '=', 'cargos.departamento_id')
            ->join('membro_cargo', 'cargos.id', '=', 'membro_cargo.cargo_id')
            ->where('membro_cargo.membro_id', $membro->id)
            ->where('membro_cargo.ativo', true)
            ->where('ministerios.ativo', true)
            ->pluck('ministerios.id')
            ->toArray();

        // Obter IDs dos ministérios com solicitações pendentes
        $ministeriosComSolicitacao = SolicitacaoMinisterio::where('membro_id', $membro->id)
            ->where('status', 'pendente')
            ->pluck('ministerio_id')
            ->toArray();

        // Ministérios disponíveis (que não participa e não tem solicitação pendente)
        $ministeriosDisponiveis = Ministerio::where('ativo', true)
            ->whereNotIn('id', array_merge($ministeriosParticipando, $ministeriosComSolicitacao))
            ->with(['departamentos.cargos'])
            ->get()
            ->map(function ($ministerio) {
                $ministerio->total_membros = $ministerio->membros()->where('membros.ativo', true)->count();
                $ministerio->total_cargos = $ministerio->departamentos->sum(function($dept) {
                    return $dept->cargos->count();
                });
                return $ministerio;
            });

        // Estatísticas
        $totalDisponiveis = $ministeriosDisponiveis->count();
        $totalMinisterios = Ministerio::where('ativo', true)->count();
        $participandoCount = count($ministeriosParticipando);

        return view('member.ministries.available', compact(
            'ministeriosDisponiveis',
            'totalDisponiveis',
            'totalMinisterios',
            'participandoCount'
        ));
    }

    /**
     * Ver detalhes do ministério
     */
    public function show(Ministerio $ministerio)
    {
        $membro = auth()->user()->membro;
        
        if (!$membro) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Você precisa ter um perfil de membro para acessar esta área.');
        }

        $membroParticipa = $membro->ministerios()->where('ministerios.id', $ministerio->id)->exists();
        
        $departamentos = $ministerio->departamentos()->with('cargos')->get();
        
        $membrosParticipantes = $ministerio->membros()
            ->with(['user', 'cargos'])
            ->get();

        $solicitacaoExistente = SolicitacaoMinisterio::where('membro_id', $membro->id)
            ->where('ministerio_id', $ministerio->id)
            ->where('status', 'pendente')
            ->first();

        // Solicitações pendentes do membro para este ministério
        $solicitacoesPendentes = SolicitacaoMinisterio::where('membro_id', $membro->id)
            ->where('ministerio_id', $ministerio->id)
            ->where('status', 'pendente')
            ->with(['cargo.departamento'])
            ->get();

        // Estatísticas
        $totalCargos = $departamentos->sum(function($departamento) {
            return $departamento->cargos->count();
        });

        $atividadesMes = 0; // Placeholder - seria calculado baseado em atividades reais

        // Atividades recentes (placeholder)
        $atividades = collect([
            [
                'titulo' => 'Ministério criado',
                'descricao' => 'Ministério foi criado e está ativo',
                'data' => $ministerio->created_at
            ]
        ]);

        return view('member.ministries.show', compact(
            'ministerio',
            'membroParticipa',
            'departamentos',
            'membrosParticipantes',
            'solicitacaoExistente',
            'solicitacoesPendentes',
            'totalCargos',
            'atividadesMes',
            'atividades'
        ));
    }

    /**
     * Solicitar participação em ministério
     */
    public function requestParticipation(Request $request, Ministerio $ministerio)
    {
        $membro = auth()->user()->membro;
        
        if (!$membro) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Você precisa ter um perfil de membro para acessar esta área.');
        }

        $request->validate([
            'cargo_id' => 'required|exists:cargos,id',
            'motivo' => 'nullable|string|max:1000',
        ]);

        // Verificar se já existe uma solicitação pendente
        $solicitacaoExistente = SolicitacaoMinisterio::where('membro_id', $membro->id)
            ->where('ministerio_id', $ministerio->id)
            ->where('status', 'pendente')
            ->first();

        if ($solicitacaoExistente) {
            return redirect()->route('member.ministries.show', $ministerio)
                ->with('error', 'Você já possui uma solicitação pendente para este ministério.');
        }

        // Verificar se já participa do ministério
        if ($membro->ministerios()->where('ministerios.id', $ministerio->id)->exists()) {
            return redirect()->route('member.ministries.show', $ministerio)
                ->with('error', 'Você já participa deste ministério.');
        }

        $solicitacao = SolicitacaoMinisterio::create([
            'membro_id' => $membro->id,
            'ministerio_id' => $ministerio->id,
            'cargo_id' => $request->cargo_id,
            'motivo' => $request->motivo,
            'status' => 'pendente',
        ]);

        // Notificar líderes do ministério
        $this->notificarLideresMinisterio($solicitacao);

        return redirect()->route('member.ministries.show', $ministerio)
            ->with('success', 'Solicitação enviada com sucesso! Aguarde a aprovação dos líderes.');
    }

    /**
     * Formulário de solicitação de participação
     */
    public function requestForm(Ministerio $ministerio)
    {
        $membro = auth()->user()->membro;
        
        if (!$membro) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Você precisa ter um perfil de membro para acessar esta área.');
        }

        // Verificar se já existe uma solicitação pendente
        $solicitacaoExistente = SolicitacaoMinisterio::where('membro_id', $membro->id)
            ->where('ministerio_id', $ministerio->id)
            ->where('status', 'pendente')
            ->first();

        if ($solicitacaoExistente) {
            return redirect()->route('member.ministries.show', $ministerio)
                ->with('error', 'Você já possui uma solicitação pendente para este ministério.');
        }

        // Verificar se já participa do ministério
        if ($membro->ministerios()->where('ministerios.id', $ministerio->id)->exists()) {
            return redirect()->route('member.ministries.show', $ministerio)
                ->with('error', 'Você já participa deste ministério.');
        }

        $departamentos = $ministerio->departamentos()->with('cargos')->get();
        $cargos = collect();
        
        foreach ($departamentos as $departamento) {
            $cargos = $cargos->merge($departamento->cargos);
        }

        return view('member.ministries.request-form', compact('ministerio', 'cargos'));
    }

    /**
     * Cancelar solicitação
     */
    public function cancelRequest(SolicitacaoMinisterio $solicitacao)
    {
        $membro = auth()->user()->membro;
        
        if (!$membro || $solicitacao->membro_id !== $membro->id) {
            return redirect()->route('member.ministries.index')
                ->with('error', 'Você não tem permissão para cancelar esta solicitação.');
        }

        if ($solicitacao->status !== 'pendente') {
            return redirect()->route('member.ministries.index')
                ->with('error', 'Apenas solicitações pendentes podem ser canceladas.');
        }

        $solicitacao->update(['status' => 'cancelada']);

        return redirect()->route('member.ministries.index')
            ->with('success', 'Solicitação cancelada com sucesso!');
    }

    /**
     * Sair do ministério
     */
    public function leaveMinistry(Ministerio $ministerio)
    {
        $membro = auth()->user()->membro;
        
        if (!$membro) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Você precisa ter um perfil de membro para acessar esta área.');
        }

        if (!$membro->ministerios()->where('ministerios.id', $ministerio->id)->exists()) {
            return redirect()->route('member.ministries.show', $ministerio)
                ->with('error', 'Você não participa deste ministério.');
        }

        $membro->ministerios()->detach($ministerio->id);

        return redirect()->route('member.ministries.index')
            ->with('success', 'Você saiu do ministério com sucesso!');
    }

    /**
     * Minhas participações
     */
    public function myParticipations()
    {
        $membro = auth()->user()->membro;
        
        if (!$membro) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Você precisa ter um perfil de membro para acessar esta área.');
        }

        $ministeriosParticipando = $membro->ministerios()
            ->with(['departamentos'])
            ->get();

        $solicitacoes = SolicitacaoMinisterio::where('membro_id', $membro->id)
            ->with(['ministerio', 'cargo', 'respondidoPor'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('member.ministries.participations', compact('ministeriosParticipando', 'solicitacoes'));
    }

    /**
     * Histórico de atividades
     */
    public function activities()
    {
        $membro = auth()->user()->membro;
        
        if (!$membro) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Você precisa ter um perfil de membro para acessar esta área.');
        }

        $atividades = collect();

        // Adicionar participações em ministérios
        foreach ($membro->ministerios as $ministerio) {
            $atividades->push([
                'tipo' => 'participacao',
                'titulo' => "Participação no ministério {$ministerio->nome}",
                'data' => $membro->pivot->created_at ?? now(),
                'descricao' => "Você começou a participar do ministério {$ministerio->nome}",
                'ministerio' => $ministerio
            ]);
        }

        // Adicionar solicitações
        foreach ($membro->solicitacoesMinisterio as $solicitacao) {
            $atividades->push([
                'tipo' => 'solicitacao',
                'titulo' => "Solicitação para {$solicitacao->ministerio->nome}",
                'data' => $solicitacao->created_at,
                'descricao' => "Solicitação enviada para participar do ministério {$solicitacao->ministerio->nome}",
                'status' => $solicitacao->status,
                'ministerio' => $solicitacao->ministerio
            ]);
        }

        // Ordenar por data
        $atividades = $atividades->sortByDesc('data');

        return view('member.ministries.activities', compact('atividades'));
    }

    /**
     * Notificar líderes do ministério
     */
    private function notificarLideresMinisterio($solicitacao)
    {
        $ministerio = $solicitacao->ministerio;
        $membro = $solicitacao->membro;
        $cargo = $solicitacao->cargo;

        // Buscar usuários com permissões de liderança
        $lideres = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['Pastor', 'Líder', 'Coordenador']);
        })->get();

        foreach ($lideres as $lider) {
            \App\Models\Notificacao::create([
                'user_id' => $lider->id,
                'titulo' => 'Nova solicitação de ministério',
                'mensagem' => "O membro {$membro->nome} solicitou participação no ministério {$ministerio->nome} para o cargo {$cargo->nome}.",
                'tipo' => 'info',
                'prioridade' => 'media',
                'dados_extras' => [
                    'solicitacao_id' => $solicitacao->id,
                    'membro_id' => $membro->id,
                    'ministerio_id' => $ministerio->id,
                    'cargo_id' => $cargo->id,
                ]
            ]);
        }
    }
}