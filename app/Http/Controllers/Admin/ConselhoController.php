<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conselho;
use App\Models\PautaConselho;
use App\Models\VotacaoConselho;
use App\Models\ParticipanteConselho;
use App\Models\VotoConselho;
use App\Models\TemplatePauta;
use App\Models\TemplateItemPauta;
use App\Models\User;
use App\Models\Membro;
use App\Models\Configuracao;
use App\Helpers\CouncilSettingsHelper;
use App\Events\CouncilMeetingScheduled;
use App\Events\CouncilMeetingStarted;
use App\Events\CouncilMeetingFinished;
use App\Events\CouncilVotingStarted;
use App\Events\CouncilVotingFinished;
use App\Events\CouncilAgendaItemAdded;
use App\Events\CouncilQuorumAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ConselhoPresencaExport;

class ConselhoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:council.access');
    }

    /**
     * Dashboard do Conselho
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Estatísticas gerais
        $totalReunioes = Conselho::count();
        $reunioesAtivas = Conselho::ativas()->count();
        $votacoesPendentes = VotacaoConselho::pendentes()->count();
        $pautasPendentes = PautaConselho::pendentes()->count();
        
        // Conselho ativo (em andamento)
        $conselhoAtivo = Conselho::ativas()->first();
        
        // Reuniões recentes
        $reunioesRecentes = Conselho::with(['criadoPor', 'presidente', 'secretario'])
            ->orderBy('data_reuniao', 'desc')
            ->limit(5)
            ->get();
            
        // Próximas reuniões
        $proximasReunioes = Conselho::with(['criadoPor', 'presidente', 'secretario'])
            ->proximas()
            ->limit(3)
            ->get();
            
        // Votações em andamento
        $votacoesEmAndamento = VotacaoConselho::with(['conselho', 'pauta'])
            ->emAndamento()
            ->get();
            
        // Participação do usuário
        $minhasParticipacoes = ParticipanteConselho::with(['conselho'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.council.dashboard', compact(
            'totalReunioes',
            'reunioesAtivas',
            'votacoesPendentes',
            'pautasPendentes',
            'conselhoAtivo',
            'reunioesRecentes',
            'proximasReunioes',
            'votacoesEmAndamento',
            'minhasParticipacoes'
        ));
    }

    /**
     * Listar todas as reuniões
     */
    public function index(Request $request)
    {
        $query = Conselho::with(['criadoPor', 'presidente', 'secretario', 'participantes']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%")
                  ->orWhere('local', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('data_inicio')) {
            $query->where('data_reuniao', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->where('data_reuniao', '<=', $request->data_fim);
        }

        // Ordenação
        $sort = $request->get('sort', 'data_desc');
        switch ($sort) {
            case 'data_asc':
                $query->orderBy('data_reuniao', 'asc');
                break;
            case 'titulo':
                $query->orderBy('titulo', 'asc');
                break;
            case 'status':
                $query->orderBy('status', 'asc');
                break;
            default:
                $query->orderBy('data_reuniao', 'desc');
        }

        $reunioes = $query->paginate(15);

        // Estatísticas
        $estatisticas = [
            'total' => Conselho::count(),
            'agendadas' => Conselho::where('status', 'agendada')->count(),
            'em_andamento' => Conselho::where('status', 'em_andamento')->count(),
            'finalizadas' => Conselho::where('status', 'finalizada')->count(),
        ];

        return view('admin.council.index', compact('reunioes', 'estatisticas'));
    }

    /**
     * Criar nova reunião
     */
    public function create()
    {
        // Carregar configurações usando o helper
        $configuracoes = CouncilSettingsHelper::getAll();
        
        // Buscar membros que podem participar do conselho
        $membros = User::with(['roles', 'cargos'])
            ->whereHas('roles', function($query) {
                $query->whereIn('name', ['Pastor', 'Líder', 'Super Admin', 'Conselheiro']);
            })
            ->orWhereHas('cargos', function($query) {
                $query->where('sistema', true);
            })
            ->orderBy('name')
            ->get();

        // Carregar templates de agenda
        $templates = TemplatePauta::where('status', 'ativo')
            ->orderBy('nome')
            ->get();

        return view('admin.council.create', compact('membros', 'configuracoes', 'templates'));
    }

    /**
     * Salvar nova reunião
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_reuniao' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required',
            'hora_fim' => 'nullable|after:hora_inicio',
            'local' => 'nullable|string|max:255',
            'tipo' => 'required|in:reuniao_ordinaria,reuniao_extraordinaria,votacao',
            'quorum_minimo' => 'required|integer|min:1',
            'presidente_id' => 'nullable|exists:users,id',
            'secretario_id' => 'nullable|exists:users,id',
            'observacoes' => 'nullable|string',
            'participantes' => 'required|array|min:1',
            'participantes.*' => 'exists:users,id',
            'template_id' => 'nullable|exists:template_pautas,id'
        ]);

        DB::beginTransaction();

        try {
            $conselho = Conselho::create([
                'titulo' => $request->titulo,
                'descricao' => $request->descricao,
                'data_reuniao' => $request->data_reuniao,
                'hora_inicio' => $request->hora_inicio,
                'hora_fim' => $request->hora_fim,
                'local' => $request->local,
                'tipo' => $request->tipo,
                'quorum_minimo' => $request->quorum_minimo,
                'criado_por' => Auth::id(),
                'presidente_id' => $request->presidente_id,
                'secretario_id' => $request->secretario_id,
                'observacoes' => $request->observacoes,
                'template_id' => $request->template_id
            ]);

            // Adicionar participantes
            foreach ($request->participantes as $participanteId) {
                $funcao = 'membro';
                
                if ($participanteId == $request->presidente_id) {
                    $funcao = 'presidente';
                } elseif ($participanteId == $request->secretario_id) {
                    $funcao = 'secretario';
                }

                ParticipanteConselho::create([
                    'conselho_id' => $conselho->id,
                    'user_id' => $participanteId,
                    'funcao' => $funcao
                ]);
            }

            // Se um template foi selecionado, criar as pautas automaticamente
            if ($request->template_id) {
                $template = TemplatePauta::find($request->template_id);
                if ($template && $template->itens_pauta) {
                    $itens = json_decode($template->itens_pauta, true);
                    if (is_array($itens)) {
                        foreach ($itens as $index => $item) {
                            PautaConselho::create([
                                'conselho_id' => $conselho->id,
                                'titulo' => $item,
                                'descricao' => null,
                                'tipo' => 'discussao',
                                'ordem' => $index + 1,
                                'tempo_estimado' => 15, // 15 minutos por padrão
                                'status' => 'pendente'
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            // Disparar evento de reunião agendada
            event(new CouncilMeetingScheduled($conselho, Auth::user()));

            return redirect()->route('admin.council.show', $conselho)
                ->with('success', 'Reunião do conselho criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erro ao criar reunião: ' . $e->getMessage());
        }
    }

    /**
     * Visualizar reunião
     */
    public function show(Conselho $conselho)
    {
        $conselho->load([
            'criadoPor',
            'presidente',
            'secretario',
            'participantes.user'
        ]);

        // Carregar apenas os dados básicos para evitar problemas de rota
        $pautas = $conselho->pautas()->orderBy('ordem')->limit(5)->get();
        $votacoes = $conselho->votacoes()->limit(5)->get();

        // Adicionar os relacionamentos ao objeto
        $conselho->setRelation('pautas', $pautas);
        $conselho->setRelation('votacoes', $votacoes);

        $pautasPendentes = $pautas->where('status', 'pendente');
        $pautasEmDiscussao = $pautas->where('status', 'em_discussao');
        $pautasFinalizadas = $pautas->whereIn('status', ['aprovado', 'rejeitado', 'adiado']);

        $votacoesPendentes = $votacoes->where('status', 'pendente');
        $votacoesEmAndamento = $votacoes->where('status', 'em_andamento');
        $votacoesFinalizadas = $votacoes->where('status', 'finalizada');

        return view('admin.council.show', compact(
            'conselho',
            'pautasPendentes',
            'pautasEmDiscussao',
            'pautasFinalizadas',
            'votacoesPendentes',
            'votacoesEmAndamento',
            'votacoesFinalizadas'
        ));
    }

    /**
     * Editar reunião
     */
    public function edit(Conselho $conselho)
    {
        // Carregar configurações usando o helper
        $configuracoes = CouncilSettingsHelper::getAll();
        
        $membros = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['Pastor', 'Líder', 'Super Admin']);
        })->get();

        $participantes = $conselho->participantes->pluck('user_id')->toArray();

        return view('admin.council.edit', compact('conselho', 'membros', 'participantes', 'configuracoes'));
    }

    /**
     * Atualizar reunião
     */
    public function update(Request $request, Conselho $conselho)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_reuniao' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fim' => 'nullable|after:hora_inicio',
            'local' => 'nullable|string|max:255',
            'tipo' => 'required|in:reuniao_ordinaria,reuniao_extraordinaria,votacao',
            'quorum_minimo' => 'required|integer|min:1',
            'presidente_id' => 'nullable|exists:users,id',
            'secretario_id' => 'nullable|exists:users,id',
            'observacoes' => 'nullable|string',
            'participantes' => 'required|array|min:1',
            'participantes.*' => 'exists:users,id'
        ]);

        DB::beginTransaction();

        try {
            $conselho->update([
                'titulo' => $request->titulo,
                'descricao' => $request->descricao,
                'data_reuniao' => $request->data_reuniao,
                'hora_inicio' => $request->hora_inicio,
                'hora_fim' => $request->hora_fim,
                'local' => $request->local,
                'tipo' => $request->tipo,
                'quorum_minimo' => $request->quorum_minimo,
                'presidente_id' => $request->presidente_id,
                'secretario_id' => $request->secretario_id,
                'observacoes' => $request->observacoes
            ]);

            // Atualizar participantes
            $conselho->participantes()->delete();

            foreach ($request->participantes as $participanteId) {
                $funcao = 'membro';
                
                if ($participanteId == $request->presidente_id) {
                    $funcao = 'presidente';
                } elseif ($participanteId == $request->secretario_id) {
                    $funcao = 'secretario';
                }

                ParticipanteConselho::create([
                    'conselho_id' => $conselho->id,
                    'user_id' => $participanteId,
                    'funcao' => $funcao
                ]);
            }

            DB::commit();

            return redirect()->route('admin.council.show', $conselho)
                ->with('success', 'Reunião do conselho atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erro ao atualizar reunião: ' . $e->getMessage());
        }
    }

    /**
     * Iniciar reunião
     */
    public function iniciar(Conselho $conselho)
    {
        if (!$conselho->podeIniciar()) {
            return back()->with('error', 'Esta reunião não pode ser iniciada.');
        }

        $conselho->update(['status' => 'em_andamento']);

        // Disparar evento de reunião iniciada
        event(new CouncilMeetingStarted($conselho));

        // Verificar quórum e disparar alerta se necessário
        $this->checkQuorumAndAlert($conselho);

        return redirect()->route('admin.council.show', $conselho)
            ->with('success', 'Reunião iniciada com sucesso!');
    }

    /**
     * Finalizar reunião
     */
    public function finalizar(Conselho $conselho)
    {
        if (!$conselho->podeFinalizar()) {
            return back()->with('error', 'Esta reunião não pode ser finalizada.');
        }

        $conselho->update([
            'status' => 'finalizada',
            'hora_fim' => now()
        ]);

        // Disparar evento de reunião finalizada
        event(new CouncilMeetingFinished($conselho));

        return redirect()->route('admin.council.show', $conselho)
            ->with('success', 'Reunião finalizada com sucesso!');
    }

    /**
     * Cancelar reunião
     */
    public function cancelar(Conselho $conselho)
    {
        if (!$conselho->podeCancelar()) {
            return back()->with('error', 'Esta reunião não pode ser cancelada.');
        }

        $conselho->update(['status' => 'cancelada']);

        return redirect()->route('admin.council.index')
            ->with('success', 'Reunião cancelada com sucesso!');
    }

    /**
     * Gerenciar presença
     */
    public function presenca(Conselho $conselho)
    {
        $conselho->load('participantes.user');
        $participantes = $conselho->participantes;

        $estatisticas = [
            'total_participantes' => $conselho->participantes()->count(),
            'presentes' => $conselho->participantes()->where('presente', true)->count(),
            'ausentes' => $conselho->participantes()->where('presente', false)->count(),
            'justificados' => 0, // Remover por enquanto até criar a coluna justificativa
            'quorum_percentual' => $conselho->participantes()->count() > 0 
                ? round(($conselho->participantes()->where('presente', true)->count() / $conselho->participantes()->count()) * 100, 1)
                : 0
        ];

        return view('admin.council.attendance.index', compact('conselho', 'participantes', 'estatisticas'));
    }

    /**
     * Atualizar presença
     */
    public function atualizarPresenca(Request $request, Conselho $conselho)
    {
        $request->validate([
            'participantes' => 'required|array',
            'participantes.*.id' => 'required|exists:participante_conselhos,id',
            'participantes.*.presente' => 'required|boolean',
            'participantes.*.hora_chegada' => 'nullable|date',
            'participantes.*.observacoes' => 'nullable|string'
        ]);

        foreach ($request->participantes as $participante) {
            $participanteModel = ParticipanteConselho::find($participante['id']);
            
            if ($participanteModel) {
                $participanteModel->update([
                    'presente' => $participante['presente'],
                    'hora_chegada' => $participante['hora_chegada'] ?? null,
                    'observacoes' => $participante['observacoes'] ?? null
                ]);
            }
        }

        return redirect()->route('admin.council.show', $conselho)
            ->with('success', 'Presença atualizada com sucesso!');
    }

    /**
     * Gerenciar pautas
     */
    public function pautas(Conselho $conselho)
    {
        $conselho->load(['pautas' => function($query) {
            $query->orderBy('ordem');
        }, 'pautas.responsavel']);

        $membros = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['Pastor', 'Líder', 'Super Admin']);
        })->get();

        $pautas = $conselho->pautas()->with('responsavel')->orderBy('ordem')->paginate(10);

        $estatisticas = [
            'total_pautas' => $conselho->pautas()->count(),
            'pautas_pendentes' => $conselho->pautas()->where('status', 'pendente')->count(),
            'pautas_discussao' => $conselho->pautas()->where('status', 'em_discussao')->count(),
            'pautas_finalizadas' => $conselho->pautas()->where('status', 'concluida')->count(),
            'tempo_estimado_total' => $conselho->pautas()->sum('tempo_estimado')
        ];

        return view('admin.council.agenda.index', compact('conselho', 'membros', 'pautas', 'estatisticas'));
    }

    /**
     * Adicionar pauta
     */
    public function adicionarPauta(Request $request, Conselho $conselho)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:informativo,deliberativo,votacao',
            'prioridade' => 'required|in:baixa,media,alta,urgente',
            'ordem' => 'required|integer|min:1',
            'tempo_estimado' => 'nullable|integer|min:1',
            'responsavel_id' => 'nullable|exists:users,id',
            'observacoes' => 'nullable|string'
        ]);

        $pauta = PautaConselho::create([
            'conselho_id' => $conselho->id,
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'tipo' => $request->tipo,
            'prioridade' => $request->prioridade,
            'ordem' => $request->ordem,
            'tempo_estimado' => $request->tempo_estimado,
            'responsavel_id' => $request->responsavel_id,
            'observacoes' => $request->observacoes
        ]);

        // Disparar evento de pauta adicionada
        event(new CouncilAgendaItemAdded($conselho, $pauta));

        return redirect()->route('admin.council.agenda.index', $conselho)
            ->with('success', 'Pauta adicionada com sucesso!');
    }

    /**
     * Iniciar discussão de pauta
     */
    public function iniciarDiscussao(PautaConselho $pauta)
    {
        if (!$pauta->podeIniciarDiscussao()) {
            return back()->with('error', 'Esta pauta não pode ser iniciada.');
        }

        $pauta->update(['status' => 'em_discussao']);

        return back()->with('success', 'Discussão iniciada com sucesso!');
    }

    /**
     * Finalizar pauta
     */
    public function finalizarPauta(Request $request, PautaConselho $pauta)
    {
        $request->validate([
            'status' => 'required|in:aprovado,rejeitado,adiado',
            'decisao_final' => 'nullable|string'
        ]);

        $pauta->update([
            'status' => $request->status,
            'decisao_final' => $request->decisao_final,
            'data_decisao' => now()
        ]);

        return back()->with('success', 'Pauta finalizada com sucesso!');
    }

    /**
     * Gerenciar votações
     */
    public function votacoes(Conselho $conselho)
    {
        $votacoes = $conselho->votacoes()->with(['pauta', 'votos'])->paginate(10);

        $estatisticas = [
            'total_votacoes' => $conselho->votacoes()->count(),
            'votacoes_pendentes' => $conselho->votacoes()->where('status', 'pendente')->count(),
            'votacoes_andamento' => $conselho->votacoes()->where('status', 'em_andamento')->count(),
            'votacoes_finalizadas' => $conselho->votacoes()->where('status', 'finalizada')->count()
        ];

        return view('admin.council.voting.index', compact('conselho', 'votacoes', 'estatisticas'));
    }

    /**
     * Criar votação
     */
    public function criarVotacao(Request $request, Conselho $conselho)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo_votacao' => 'required|in:aprovacao_rejeicao,multipla_escolha,escala',
            'pauta_id' => 'nullable|exists:pauta_conselhos,id',
            'quorum_necessario' => 'required|integer|min:1',
            'tempo_limite' => 'nullable|integer|min:1',
            'voto_secreto' => 'boolean',
            'opcoes_votacao' => 'nullable|array'
        ]);

        VotacaoConselho::create([
            'conselho_id' => $conselho->id,
            'pauta_id' => $request->pauta_id,
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'tipo_votacao' => $request->tipo_votacao,
            'opcoes_votacao' => $request->opcoes_votacao,
            'quorum_necessario' => $request->quorum_necessario,
            'tempo_limite' => $request->tempo_limite,
            'voto_secreto' => $request->boolean('voto_secreto')
        ]);

        return redirect()->route('admin.council.voting.index', $conselho)
            ->with('success', 'Votação criada com sucesso!');
    }

    /**
     * Iniciar votação
     */
    public function iniciarVotacao(VotacaoConselho $votacao)
    {
        if (!$votacao->podeIniciar()) {
            return back()->with('error', 'Esta votação não pode ser iniciada.');
        }

        $votacao->update([
            'status' => 'em_andamento',
            'data_inicio' => now()
        ]);

        // Disparar evento de votação iniciada
        event(new CouncilVotingStarted($votacao->conselho, $votacao));

        return back()->with('success', 'Votação iniciada com sucesso!');
    }

    /**
     * Finalizar votação
     */
    public function finalizarVotacao(VotacaoConselho $votacao)
    {
        if (!$votacao->podeFinalizar()) {
            return back()->with('error', 'Esta votação não pode ser finalizada.');
        }

        $votacao->calcularResultado();
        $votacao->update([
            'status' => 'finalizada',
            'data_fim' => now()
        ]);

        // Preparar resultados da votação
        $results = [
            'resultado' => $votacao->resultado,
            'votos_sim' => $votacao->votos_sim,
            'votos_nao' => $votacao->votos_nao,
            'abstencoes' => $votacao->abstencoes,
            'aprovada' => $votacao->aprovada
        ];

        // Disparar evento de votação finalizada
        event(new CouncilVotingFinished($votacao->conselho, $votacao, $results));

        return back()->with('success', 'Votação finalizada com sucesso!');
    }

    /**
     * Votar
     */
    public function votar(Request $request, VotacaoConselho $votacao)
    {
        $request->validate([
            'voto' => 'required|string',
            'justificativa' => 'nullable|string'
        ]);

        $user = Auth::user();

        // Verificar se já votou
        $votoExistente = VotoConselho::where('votacao_id', $votacao->id)
            ->where('user_id', $user->id)
            ->first();

        if ($votoExistente) {
            return back()->with('error', 'Você já votou nesta votação.');
        }

        // Verificar se é participante
        $participante = ParticipanteConselho::where('conselho_id', $votacao->conselho_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$participante) {
            return back()->with('error', 'Você não é participante desta reunião.');
        }

        // Criar voto
        VotoConselho::create([
            'votacao_id' => $votacao->id,
            'user_id' => $user->id,
            'voto' => $request->voto,
            'justificativa' => $request->justificativa,
            'data_voto' => now(),
            'voto_anonimo' => $votacao->voto_secreto
        ]);

        // Atualizar contadores
        $votacao->increment('total_votos');
        
        if ($request->voto === 'favoravel') {
            $votacao->increment('votos_favoraveis');
        } elseif ($request->voto === 'contrario') {
            $votacao->increment('votos_contrarios');
        } elseif ($request->voto === 'abstencao') {
            $votacao->increment('votos_abstencao');
        }

        return back()->with('success', 'Voto registrado com sucesso!');
    }

    /**
     * Gerar relatório
     */
    public function relatorio(Conselho $conselho)
    {
        $conselho->load([
            'criadoPor',
            'presidente',
            'secretario',
            'pautas.responsavel',
            'participantes.user',
            'votacoes.pauta',
            'votacoes.votos.user'
        ]);

        $pdf = Pdf::loadView('pdf.relatorio-conselho', compact('conselho'));
        
        return $pdf->download('relatorio-conselho-' . $conselho->id . '.pdf');
    }

    /**
     * Exportar dados
     */
    public function exportar(Request $request)
    {
        $query = Conselho::with(['criadoPor', 'presidente', 'secretario', 'pautas', 'votacoes']);

        // Aplicar filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $conselhos = $query->orderBy('data_reuniao', 'desc')->get();

        return Excel::download(new ConselhosExport($conselhos), 'conselhos.xlsx');
    }

    /**
     * Histórico de votações
     */
    public function votingHistory()
    {
        $votacoes = VotacaoConselho::with(['conselho', 'pauta'])
            ->finalizadas()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.council.voting.history', compact('votacoes'));
    }

    /**
     * Templates de agenda
     */
    public function agendaTemplates(Request $request)
    {
        $query = TemplatePauta::with(['criadoPor', 'itens']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ordenação
        $sort = $request->get('sort', 'created_desc');
        switch ($sort) {
            case 'nome':
                $query->orderBy('nome', 'asc');
                break;
            case 'categoria':
                $query->orderBy('categoria', 'asc');
                break;
            case 'usos':
                // Remover ordenação por usos_count que não existe
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $templates = $query->paginate(12);

        return view('admin.council.agenda.templates.index', compact('templates'));
    }

    /**
     * Configurações do conselho
     */
    public function settings()
    {
        // Carregar configurações usando o helper
        $configuracoes = CouncilSettingsHelper::getAll();
        
        return view('admin.council.settings.index', compact('configuracoes'));
    }

    /**
     * Criar pauta
     */
    public function criarPauta(Conselho $conselho)
    {
        return view('admin.council.agenda.create', compact('conselho'));
    }

    /**
     * Mostrar pauta
     */
    public function mostrarPauta(Conselho $conselho, PautaConselho $pauta)
    {
        return view('admin.council.agenda.show', compact('conselho', 'pauta'));
    }

    /**
     * Editar pauta
     */
    public function editarPauta(Conselho $conselho, PautaConselho $pauta)
    {
        return view('admin.council.agenda.edit', compact('conselho', 'pauta'));
    }

    /**
     * Atualizar pauta
     */
    public function atualizarPauta(Request $request, Conselho $conselho, PautaConselho $pauta)
    {
        // Implementar atualização de pauta
        return redirect()->route('admin.council.agenda.index', $conselho);
    }

    /**
     * Excluir pauta
     */
    public function excluirPauta(Conselho $conselho, PautaConselho $pauta)
    {
        // Implementar exclusão de pauta
        return redirect()->route('admin.council.agenda.index', $conselho);
    }

    /**
     * Criar votação form
     */
    public function criarVotacaoForm(Conselho $conselho)
    {
        // Carregar configurações usando o helper
        $configuracoes = CouncilSettingsHelper::getAll();
        
        return view('admin.council.voting.create', compact('conselho', 'configuracoes'));
    }

    /**
     * Mostrar votação
     */
    public function mostrarVotacao(Conselho $conselho, VotacaoConselho $votacao)
    {
        return view('admin.council.voting.show', compact('conselho', 'votacao'));
    }

    /**
     * Cancelar votação
     */
    public function cancelarVotacao(VotacaoConselho $votacao)
    {
        // Implementar cancelamento de votação
        return redirect()->back();
    }

    /**
     * Excluir votação
     */
    public function excluirVotacao(Conselho $conselho, VotacaoConselho $votacao)
    {
        // Implementar exclusão de votação
        return redirect()->route('admin.council.voting.index', $conselho);
    }

    /**
     * Exportar presença
     */
    public function exportarPresenca(Conselho $conselho)
    {
        try {
            // Carregar participantes com dados dos usuários
            $participantes = $conselho->participantes()->with('user')->get();
            
            // Criar o nome do arquivo
            $nomeArquivo = 'presenca_' . Str::slug($conselho->titulo) . '_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
            
            // Exportar usando a classe ConselhoPresencaExport
            return Excel::download(new ConselhoPresencaExport($conselho, $participantes), $nomeArquivo);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao exportar presença do conselho: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao exportar relatório de presença: ' . $e->getMessage());
        }
    }

    /**
     * Imprimir presença
     */
    public function imprimirPresenca(Conselho $conselho)
    {
        try {
            // Carregar dados necessários
            $conselho->load(['participantes.user', 'presidente', 'secretario']);
            $participantes = $conselho->participantes;
            
            // Carregar configurações do sistema
            $configuracoes = [
                'app_name' => \App\Models\Configuracao::get('app_name', config('app.name', 'CBAV')),
                'igreja_nome' => \App\Models\Configuracao::get('igreja_nome', 'Congregação Batista Avenida'),
                'app_logo' => \App\Models\Configuracao::get('app_logo', ''),
                'contact_email' => \App\Models\Configuracao::get('contact_email', ''),
                'contact_phone' => \App\Models\Configuracao::get('contact_phone', ''),
                'address' => \App\Models\Configuracao::get('address', ''),
            ];
            
            // Gerar PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.relatorio-presenca-conselho', compact('conselho', 'participantes', 'configuracoes'));
            
            // Configurações do PDF
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);
            
            // Nome do arquivo
            $nomeArquivo = 'relatorio_presenca_' . Str::slug($conselho->titulo) . '_' . now()->format('Y_m_d') . '.pdf';
            
            return $pdf->download($nomeArquivo);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao imprimir presença do conselho: ' . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao gerar relatório de presença: ' . $e->getMessage());
        }
    }

    /**
     * Atualizar configurações
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'quorum_padrao' => 'required|integer|min:1|max:100',
            'duracao_padrao' => 'required|integer|min:30|max:480',
            'max_pautas' => 'required|integer|min:1|max:50',
            'tempo_votacao' => 'required|integer|min:1|max:60',
            'maioria_qualificada' => 'required|integer|min:51|max:100',
            'antecedencia_notificacao' => 'required|integer|min:1|max:168',
            'tempo_sessao' => 'required|integer|min:5|max:480',
            'max_tentativas' => 'required|integer|min:1|max:10',
        ]);

        try {
            // Salvar configurações gerais
            CouncilSettingsHelper::set('quorum_padrao', $request->quorum_padrao, 'integer', 'Quórum padrão para reuniões do conselho');
            CouncilSettingsHelper::set('duracao_padrao', $request->duracao_padrao, 'integer', 'Duração padrão das reuniões em minutos');
            CouncilSettingsHelper::set('max_pautas', $request->max_pautas, 'integer', 'Número máximo de pautas por reunião');
            CouncilSettingsHelper::set('tempo_votacao', $request->tempo_votacao, 'integer', 'Tempo padrão para votações em minutos');
            
            // Configurações de votação
            CouncilSettingsHelper::set('voto_secreto_padrao', $request->has('voto_secreto_padrao') ? '1' : '0', 'boolean', 'Ativar voto secreto como padrão');
            CouncilSettingsHelper::set('permitir_abstencao', $request->has('permitir_abstencao') ? '1' : '0', 'boolean', 'Permitir que participantes se abstenham');
            CouncilSettingsHelper::set('justificativa_obrigatoria', $request->has('justificativa_obrigatoria') ? '1' : '0', 'boolean', 'Exigir justificativa para votos contrários');
            CouncilSettingsHelper::set('maioria_qualificada', $request->maioria_qualificada, 'integer', 'Percentual para maioria qualificada');
            
            // Configurações de notificação
            CouncilSettingsHelper::set('notificar_reuniao', $request->has('notificar_reuniao') ? '1' : '0', 'boolean', 'Enviar notificação quando nova reunião for criada');
            CouncilSettingsHelper::set('notificar_votacao', $request->has('notificar_votacao') ? '1' : '0', 'boolean', 'Enviar notificação quando votação for iniciada');
            CouncilSettingsHelper::set('notificar_resultado', $request->has('notificar_resultado') ? '1' : '0', 'boolean', 'Enviar notificação com resultado da votação');
            CouncilSettingsHelper::set('antecedencia_notificacao', $request->antecedencia_notificacao, 'integer', 'Horas de antecedência para notificar reunião');
            
            // Configurações de segurança
            CouncilSettingsHelper::set('registrar_logs', $request->has('registrar_logs') ? '1' : '0', 'boolean', 'Manter registro de todas as atividades');
            CouncilSettingsHelper::set('backup_automatico', $request->has('backup_automatico') ? '1' : '0', 'boolean', 'Fazer backup automático dos dados');
            CouncilSettingsHelper::set('tempo_sessao', $request->tempo_sessao, 'integer', 'Tempo de inatividade para logout');
            CouncilSettingsHelper::set('max_tentativas', $request->max_tentativas, 'integer', 'Número máximo de tentativas de login');

            // Limpar cache de configurações
            CouncilSettingsHelper::clearCache();
            
            return redirect()->route('admin.council.settings')
                ->with('success', 'Configurações atualizadas com sucesso!');
                
        } catch (\Exception $e) {
            \Log::error('Erro ao salvar configurações do conselho', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.council.settings')
                ->with('error', 'Erro ao salvar configurações: ' . $e->getMessage());
        }
    }

    // ========================================
    // MÉTODOS PARA TEMPLATES DE PAUTA
    // ========================================

    /**
     * Criar template
     */
    public function criarTemplate()
    {
        $membros = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['Pastor', 'Líder', 'Super Admin', 'Conselheiro']);
        })->orderBy('name')->get();

        return view('admin.council.agenda.templates.create', compact('membros'));
    }

    /**
     * Salvar template
     */
    public function salvarTemplate(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'categoria' => 'required|in:reuniao_ordinaria,reuniao_extraordinaria,votacao,evento,geral',
            'status' => 'required|in:ativo,inativo,rascunho',
            'itens' => 'nullable|array',
            'itens.*.titulo' => 'required|string|max:255',
            'itens.*.descricao' => 'nullable|string',
            'itens.*.tipo' => 'required|in:informativo,deliberativo,votacao,discussao,apresentacao',
            'itens.*.prioridade' => 'required|in:baixa,media,alta,urgente',
            'itens.*.ordem' => 'required|integer|min:1',
            'itens.*.tempo_estimado' => 'nullable|integer|min:1',
            'itens.*.responsavel_id' => 'nullable|exists:users,id',
            'itens.*.observacoes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $template = TemplatePauta::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'categoria' => $request->categoria,
                'status' => $request->status,
                'criado_por' => Auth::id()
            ]);

            // Salvar itens do template
            if ($request->has('itens')) {
                foreach ($request->itens as $item) {
                    TemplateItemPauta::create([
                        'template_id' => $template->id,
                        'titulo' => $item['titulo'],
                        'descricao' => $item['descricao'] ?? null,
                        'tipo' => $item['tipo'],
                        'prioridade' => $item['prioridade'],
                        'ordem' => $item['ordem'],
                        'tempo_estimado' => $item['tempo_estimado'] ?? null,
                        'responsavel_id' => $item['responsavel_id'] ?? null,
                        'observacoes' => $item['observacoes'] ?? null
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.council.agenda.template.index')
                ->with('success', 'Template criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erro ao criar template: ' . $e->getMessage());
        }
    }

    /**
     * Editar template
     */
    public function editarTemplate(TemplatePauta $template)
    {
        $template->load(['itens.responsavel']);
        $membros = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['Pastor', 'Líder', 'Super Admin', 'Conselheiro']);
        })->orderBy('name')->get();

        return view('admin.council.agenda.templates.edit', compact('template', 'membros'));
    }

    /**
     * Atualizar template
     */
    public function atualizarTemplate(Request $request, TemplatePauta $template)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'categoria' => 'required|in:reuniao_ordinaria,reuniao_extraordinaria,votacao,evento,geral',
            'status' => 'required|in:ativo,inativo,rascunho',
            'itens' => 'nullable|array',
            'itens.*.titulo' => 'required|string|max:255',
            'itens.*.descricao' => 'nullable|string',
            'itens.*.tipo' => 'required|in:informativo,deliberativo,votacao,discussao,apresentacao',
            'itens.*.prioridade' => 'required|in:baixa,media,alta,urgente',
            'itens.*.ordem' => 'required|integer|min:1',
            'itens.*.tempo_estimado' => 'nullable|integer|min:1',
            'itens.*.responsavel_id' => 'nullable|exists:users,id',
            'itens.*.observacoes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $template->update([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'categoria' => $request->categoria,
                'status' => $request->status
            ]);

            // Atualizar itens do template
            $template->itens()->delete();

            if ($request->has('itens')) {
                foreach ($request->itens as $item) {
                    TemplateItemPauta::create([
                        'template_id' => $template->id,
                        'titulo' => $item['titulo'],
                        'descricao' => $item['descricao'] ?? null,
                        'tipo' => $item['tipo'],
                        'prioridade' => $item['prioridade'],
                        'ordem' => $item['ordem'],
                        'tempo_estimado' => $item['tempo_estimado'] ?? null,
                        'responsavel_id' => $item['responsavel_id'] ?? null,
                        'observacoes' => $item['observacoes'] ?? null
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.council.agenda.template.index')
                ->with('success', 'Template atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erro ao atualizar template: ' . $e->getMessage());
        }
    }

    /**
     * Excluir template
     */
    public function excluirTemplate(TemplatePauta $template)
    {
        if (!$template->podeExcluir()) {
            return back()->with('error', 'Este template não pode ser excluído pois está sendo usado.');
        }

        try {
            $template->delete();
            return redirect()->route('admin.council.agenda.template.index')
                ->with('success', 'Template excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir template: ' . $e->getMessage());
        }
    }

    /**
     * Duplicar template
     */
    public function duplicarTemplate(TemplatePauta $template)
    {
        try {
            $novoTemplate = $template->duplicar();
            return redirect()->route('admin.council.agenda.template.index')
                ->with('success', 'Template duplicado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao duplicar template: ' . $e->getMessage());
        }
    }

    /**
     * Usar template
     */
    public function usarTemplate(TemplatePauta $template)
    {
        // Carregar configurações usando o helper
        $configuracoes = CouncilSettingsHelper::getAll();
        
        $template->load(['itens.responsavel']);
        
        $membros = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['Pastor', 'Líder', 'Super Admin', 'Conselheiro']);
        })->orderBy('name')->get();

        return view('admin.council.agenda.templates.usar', compact('template', 'membros', 'configuracoes'));
    }

    /**
     * Histórico de usos dos templates
     */
    public function templateHistory(Request $request)
    {
        $query = Conselho::with(['template', 'criadoPor'])
            ->whereNotNull('template_id');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhereHas('template', function($q) use ($search) {
                      $q->where('nome', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('template_id')) {
            $query->where('template_id', $request->template_id);
        }

        if ($request->filled('periodo')) {
            switch ($request->periodo) {
                case 'hoje':
                    $query->whereDate('created_at', today());
                    break;
                case 'semana':
                    $query->where('created_at', '>=', now()->subWeek());
                    break;
                case 'mes':
                    $query->where('created_at', '>=', now()->subMonth());
                    break;
                case 'ano':
                    $query->where('created_at', '>=', now()->subYear());
                    break;
            }
        }

        // Ordenação
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'old':
                $query->orderBy('created_at', 'asc');
                break;
            case 'template':
                $query->orderBy('template_id')->orderBy('created_at', 'desc');
                break;
            case 'usos':
                $query->orderBy('template_id')->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $usos = $query->paginate(15);

        // Estatísticas
        $estatisticas = [
            'total_templates' => TemplatePauta::count(),
            'templates_ativos' => TemplatePauta::where('status', 'ativo')->count(),
            'total_usos' => Conselho::whereNotNull('template_id')->count(),
            'template_mais_usado' => TemplatePauta::withCount('conselhos')
                ->orderBy('conselhos_count', 'desc')
                ->first()?->nome ?? 'N/A'
        ];

        $templates = TemplatePauta::orderBy('nome')->get();

        return view('admin.council.agenda.templates.history', compact('usos', 'estatisticas', 'templates'));
    }

    /**
     * Exportar templates
     */
    public function exportarTemplates(Request $request)
    {
        $query = TemplatePauta::with(['criadoPor', 'itens']);

        // Aplicar filtros
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        $templates = $query->orderBy('created_at', 'desc')->get();

        return Excel::download(new TemplatePautaExport($templates), 'templates-pauta-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Verificar quórum e disparar alerta se necessário
     */
    private function checkQuorumAndAlert(Conselho $conselho)
    {
        $totalParticipantes = $conselho->participantes()->count();
        $presentesCount = $conselho->participantes()->where('presente', true)->count();
        
        if ($totalParticipantes == 0) {
            return;
        }
        
        $currentQuorum = round(($presentesCount / $totalParticipantes) * 100, 1);
        $requiredQuorum = $conselho->quorum_minimo ?? CouncilSettingsHelper::get('quorum_padrao', 50);
        
        if ($currentQuorum < $requiredQuorum) {
            event(new CouncilQuorumAlert($conselho, $currentQuorum, $requiredQuorum));
        }
    }

    /**
     * Método para atualizar presença e verificar quórum
     */
    public function atualizarPresencaComVerificacao(Request $request, Conselho $conselho)
    {
        // Chamar método original de atualização de presença
        $response = $this->atualizarPresenca($request, $conselho);
        
        // Verificar quórum após atualizar presença
        if ($conselho->status === 'em_andamento') {
            $this->checkQuorumAndAlert($conselho);
        }
        
        return $response;
    }

    /**
     * Obter status atual do conselho (para JavaScript)
     */
    public function status(Conselho $conselho)
    {
        $totalParticipantes = $conselho->participantes()->count();
        $presentesCount = $conselho->participantes()->where('presente', true)->count();
        $currentQuorum = $totalParticipantes > 0 ? round(($presentesCount / $totalParticipantes) * 100, 1) : 0;
        $requiredQuorum = $conselho->quorum_minimo ?? CouncilSettingsHelper::get('quorum_padrao', 50);
        
        $votacoesPendentes = $conselho->votacoes()->where('status', 'em_andamento')->count();
        $pautasPendentes = $conselho->pautas()->where('status', 'pendente')->count();

        return response()->json([
            'status' => $conselho->status,
            'quorum' => [
                'current' => $currentQuorum,
                'required' => $requiredQuorum,
                'presentes' => $presentesCount,
                'total' => $totalParticipantes
            ],
            'votacoes_pendentes' => $votacoesPendentes,
            'pautas_pendentes' => $pautasPendentes,
            'last_updated' => now()->toISOString()
        ]);
    }
} 