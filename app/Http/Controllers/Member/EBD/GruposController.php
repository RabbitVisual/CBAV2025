<?php

namespace App\Http\Controllers\Member\EBD;

use App\Http\Controllers\Controller;
use App\Models\EBD\EbdGrupoEstudo;
use App\Models\EBD\EbdAvaliacaoGrupo;
use App\Models\EBD\EbdRespostaGrupo;
use App\Models\EBD\EbdContribuicaoResposta;
use App\Models\EBD\EbdQuestao;
use App\Models\EBD\EbdAvaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GruposController extends Controller
{
    /**
     * Listar grupos de estudo disponíveis para o membro
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $aluno = $user->aluno;
        
        if (!$aluno) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Você precisa estar cadastrado como aluno para acessar os grupos de estudo.');
        }

        $query = EbdGrupoEstudo::with(['turma', 'lider', 'membros.aluno'])
            ->where('turma_id', $aluno->turma_id)
            ->where('status', 'ativo');

        // Filtros
        if ($request->filled('busca')) {
            $query->where(function($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->busca . '%')
                  ->orWhere('descricao', 'like', '%' . $request->busca . '%');
            });
        }

        if ($request->filled('status_membro')) {
            if ($request->status_membro === 'membro') {
                $query->whereHas('membros', function($q) use ($aluno) {
                    $q->where('aluno_id', $aluno->id)
                      ->where('status', 'ativo');
                });
            } elseif ($request->status_membro === 'nao_membro') {
                $query->whereDoesntHave('membros', function($q) use ($aluno) {
                    $q->where('aluno_id', $aluno->id)
                      ->where('status', 'ativo');
                });
            }
        }

        $grupos = $query->withCount('membros')
            ->orderBy('nome')
            ->paginate(12);

        // Verificar quais grupos o aluno é membro
        $gruposDoAluno = $aluno->gruposEstudo()
            ->where('status', 'ativo')
            ->pluck('grupo_id')
            ->toArray();

        // Estatísticas
        $estatisticas = [
            'total_grupos' => EbdGrupoEstudo::where('turma_id', $aluno->turma_id)
                ->where('status', 'ativo')
                ->count(),
            'grupos_participando' => count($gruposDoAluno),
            'grupos_disponiveis' => EbdGrupoEstudo::where('turma_id', $aluno->turma_id)
                ->where('status', 'ativo')
                ->whereDoesntHave('membros', function($q) use ($aluno) {
                    $q->where('aluno_id', $aluno->id)
                      ->where('status', 'ativo');
                })
                ->whereRaw('(SELECT COUNT(*) FROM ebd_grupo_membros WHERE grupo_id = ebd_grupos_estudo.id AND status = "ativo") < capacidade_maxima')
                ->count(),
            'avaliacoes_pendentes' => EbdAvaliacaoGrupo::whereIn('grupo_id', $gruposDoAluno)
                ->where('status', 'em_andamento')
                ->count()
        ];

        return view('member.ebd.grupos.index', compact(
            'grupos', 
            'gruposDoAluno', 
            'estatisticas'
        ));
    }

    /**
     * Visualizar detalhes de um grupo
     */
    public function show(EbdGrupoEstudo $grupo)
    {
        $user = Auth::user();
        $aluno = $user->aluno;
        
        if (!$aluno) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Você precisa estar cadastrado como aluno para acessar os grupos de estudo.');
        }

        // Verificar se o grupo é da mesma turma do aluno
        if ($grupo->turma_id !== $aluno->turma_id) {
            return redirect()->route('member.ebd.grupos.index')
                ->with('error', 'Você não tem acesso a este grupo de estudo.');
        }

        $grupo->load(['turma', 'lider', 'membros.aluno']);
        
        // Verificar se o aluno é membro do grupo
        $isMember = $grupo->membros
            ->where('aluno_id', $aluno->id)
            ->where('status', 'ativo')
            ->isNotEmpty();
            
        $isLeader = $grupo->lider_id === $aluno->id;

        // Avaliações recentes do grupo
        $avaliacoesRecentes = EbdAvaliacaoGrupo::with('avaliacao')
            ->where('grupo_id', $grupo->id)
            ->orderBy('data_inicio', 'desc')
            ->limit(5)
            ->get();

        // Estatísticas (apenas para membros)
        $estatisticas = [];
        if ($isMember) {
            $estatisticas = [
                'total_avaliacoes' => $avaliacoesRecentes->count(),
                'avaliacoes_concluidas' => $avaliacoesRecentes->where('status', 'concluida')->count(),
                'media_pontuacao' => $avaliacoesRecentes->where('status', 'concluida')
                    ->avg('pontuacao_total') ?? 0,
                'tempo_medio_resposta' => $this->calcularTempoMedioResposta($grupo->id)
            ];
        }

        return view('member.ebd.grupos.show', compact(
            'grupo', 
            'isMember', 
            'isLeader', 
            'avaliacoesRecentes', 
            'estatisticas'
        ));
    }

    /**
     * Entrar em um grupo
     */
    public function entrar(EbdGrupoEstudo $grupo)
    {
        $user = Auth::user();
        $aluno = $user->aluno;
        
        if (!$aluno) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Você precisa estar cadastrado como aluno para entrar em grupos de estudo.');
        }

        // Verificar se o grupo é da mesma turma
        if ($grupo->turma_id !== $aluno->turma_id) {
            return redirect()->route('member.ebd.grupos.index')
                ->with('error', 'Você não pode entrar neste grupo de estudo.');
        }

        // Verificar se o grupo está ativo
        if ($grupo->status !== 'ativo') {
            return redirect()->route('member.ebd.grupos.index')
                ->with('error', 'Este grupo não está ativo.');
        }

        // Verificar se o grupo não está lotado
        if ($grupo->isLotado()) {
            return redirect()->route('member.ebd.grupos.show', $grupo)
                ->with('error', 'Este grupo já atingiu sua capacidade máxima.');
        }

        // Verificar se já é membro
        $jaEMembro = $grupo->membros
            ->where('aluno_id', $aluno->id)
            ->where('status', 'ativo')
            ->isNotEmpty();

        if ($jaEMembro) {
            return redirect()->route('member.ebd.grupos.show', $grupo)
                ->with('info', 'Você já é membro deste grupo.');
        }

        try {
            DB::beginTransaction();

            // Adicionar aluno ao grupo
            $grupo->adicionarAluno($aluno->id);

            DB::commit();

            return redirect()->route('member.ebd.grupos.show', $grupo)
                ->with('success', 'Você entrou no grupo com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('member.ebd.grupos.show', $grupo)
                ->with('error', 'Erro ao entrar no grupo: ' . $e->getMessage());
        }
    }

    /**
     * Sair de um grupo
     */
    public function sair(EbdGrupoEstudo $grupo)
    {
        $user = Auth::user();
        $aluno = $user->aluno;
        
        if (!$aluno) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Você precisa estar cadastrado como aluno.');
        }

        // Verificar se é membro do grupo
        $membro = $grupo->membros
            ->where('aluno_id', $aluno->id)
            ->where('status', 'ativo')
            ->first();

        if (!$membro) {
            return redirect()->route('member.ebd.grupos.index')
                ->with('error', 'Você não é membro deste grupo.');
        }

        // Verificar se é o líder
        if ($grupo->lider_id === $aluno->id) {
            return redirect()->route('member.ebd.grupos.show', $grupo)
                ->with('error', 'O líder não pode sair do grupo. Transfira a liderança primeiro.');
        }

        try {
            DB::beginTransaction();

            // Remover aluno do grupo
            $grupo->removerAluno($aluno->id);

            DB::commit();

            return redirect()->route('member.ebd.grupos.index')
                ->with('success', 'Você saiu do grupo com sucesso.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('member.ebd.grupos.show', $grupo)
                ->with('error', 'Erro ao sair do grupo: ' . $e->getMessage());
        }
    }

    /**
     * Participar de uma avaliação em grupo
     */
    public function avaliar(EbdAvaliacaoGrupo $avaliacao, $questaoOrdem = 1)
    {
        $user = Auth::user();
        $aluno = $user->aluno;
        
        if (!$aluno) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Você precisa estar cadastrado como aluno.');
        }

        // Verificar se é membro do grupo
        $isMember = $avaliacao->grupo->membros
            ->where('aluno_id', $aluno->id)
            ->where('status', 'ativo')
            ->isNotEmpty();

        if (!$isMember) {
            return redirect()->route('member.ebd.grupos.index')
                ->with('error', 'Você não é membro deste grupo.');
        }

        // Verificar se a avaliação está em andamento
        if ($avaliacao->status !== 'em_andamento') {
            return redirect()->route('member.ebd.grupos.show', $avaliacao->grupo)
                ->with('error', 'Esta avaliação não está disponível.');
        }

        $avaliacao->load(['grupo', 'avaliacao', 'respostas.contribuicoes.aluno']);
        
        // Buscar questões da avaliação
        $questoes = EbdQuestao::where('avaliacao_id', $avaliacao->avaliacao_id)
            ->orderBy('ordem')
            ->get();

        $totalQuestoes = $questoes->count();
        
        // Questão atual
        $questaoAtual = $questoes->where('ordem', $questaoOrdem)->first();
        
        if (!$questaoAtual) {
            return redirect()->route('member.ebd.avaliacoes.questao', [$avaliacao, 1])
                ->with('error', 'Questão não encontrada.');
        }

        $questaoAtual->load('opcoes');

        // Resposta atual do grupo para esta questão
        $respostaAtual = $avaliacao->respostas
            ->where('questao_id', $questaoAtual->id)
            ->first();

        // Contribuições para esta questão
        $contribuicoes = collect();
        if ($respostaAtual) {
            $contribuicoes = $respostaAtual->contribuicoes
                ->load('aluno')
                ->sortBy('created_at');
        }

        // Calcular progresso
        $questoesRespondidas = $avaliacao->respostas
            ->where('resposta_final', '!=', null)
            ->count();
        $progresso = $totalQuestoes > 0 ? round(($questoesRespondidas / $totalQuestoes) * 100) : 0;

        return view('member.ebd.grupos.avaliar', compact(
            'avaliacao',
            'questoes',
            'questaoAtual',
            'totalQuestoes',
            'respostaAtual',
            'contribuicoes',
            'progresso'
        ));
    }

    /**
     * Responder uma questão
     */
    public function responder(Request $request, EbdAvaliacaoGrupo $avaliacao, EbdQuestao $questao)
    {
        $user = Auth::user();
        $aluno = $user->aluno;
        
        if (!$aluno) {
            return response()->json(['error' => 'Usuário não autorizado'], 403);
        }

        // Verificar se é membro do grupo
        $isMember = $avaliacao->grupo->membros
            ->where('aluno_id', $aluno->id)
            ->where('status', 'ativo')
            ->isNotEmpty();

        if (!$isMember) {
            return response()->json(['error' => 'Você não é membro deste grupo'], 403);
        }

        $request->validate([
            'resposta' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            // Buscar ou criar resposta do grupo
            $respostaGrupo = EbdRespostaGrupo::firstOrCreate([
                'avaliacao_grupo_id' => $avaliacao->id,
                'questao_id' => $questao->id
            ]);

            // Atualizar resposta final (apenas líder pode definir)
            if ($avaliacao->grupo->lider_id === $aluno->id) {
                $respostaGrupo->resposta_final = $request->resposta;
                $respostaGrupo->data_resposta = now();
                $respostaGrupo->save();
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('member.ebd.avaliacoes.questao', [$avaliacao, $questao->ordem])
                ->with('success', 'Resposta salva com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erro ao salvar resposta: ' . $e->getMessage());
        }
    }

    /**
     * Adicionar contribuição para uma questão
     */
    public function contribuir(Request $request, EbdAvaliacaoGrupo $avaliacao, EbdQuestao $questao)
    {
        $user = Auth::user();
        $aluno = $user->aluno;
        
        if (!$aluno) {
            return response()->json(['error' => 'Usuário não autorizado'], 403);
        }

        // Verificar se é membro do grupo
        $isMember = $avaliacao->grupo->membros
            ->where('aluno_id', $aluno->id)
            ->where('status', 'ativo')
            ->isNotEmpty();

        if (!$isMember) {
            return response()->json(['error' => 'Você não é membro deste grupo'], 403);
        }

        $request->validate([
            'contribuicao' => 'required|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Buscar ou criar resposta do grupo
            $respostaGrupo = EbdRespostaGrupo::firstOrCreate([
                'avaliacao_grupo_id' => $avaliacao->id,
                'questao_id' => $questao->id
            ]);

            // Adicionar contribuição
            $respostaGrupo->adicionarContribuicao($aluno->id, $request->contribuicao);

            DB::commit();

            return redirect()->route('member.ebd.avaliacoes.questao', [$avaliacao, $questao->ordem])
                ->with('success', 'Contribuição adicionada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erro ao adicionar contribuição: ' . $e->getMessage());
        }
    }

    /**
     * Finalizar avaliação
     */
    public function finalizar(EbdAvaliacaoGrupo $avaliacao)
    {
        $user = Auth::user();
        $aluno = $user->aluno;
        
        if (!$aluno) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Usuário não autorizado.');
        }

        // Verificar se é o líder do grupo
        if ($avaliacao->grupo->lider_id !== $aluno->id) {
            return redirect()->route('member.ebd.grupos.show', $avaliacao->grupo)
                ->with('error', 'Apenas o líder pode finalizar a avaliação.');
        }

        try {
            DB::beginTransaction();

            $avaliacao->concluir();

            DB::commit();

            return redirect()->route('member.ebd.grupos.show', $avaliacao->grupo)
                ->with('success', 'Avaliação finalizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erro ao finalizar avaliação: ' . $e->getMessage());
        }
    }

    /**
     * Calcular tempo médio de resposta do grupo
     */
    private function calcularTempoMedioResposta($grupoId)
    {
        $avaliacoes = EbdAvaliacaoGrupo::where('grupo_id', $grupoId)
            ->where('status', 'concluida')
            ->get();

        if ($avaliacoes->isEmpty()) {
            return 'N/A';
        }

        $tempoTotal = 0;
        $totalRespostas = 0;

        foreach ($avaliacoes as $avaliacao) {
            foreach ($avaliacao->respostas as $resposta) {
                if ($resposta->data_resposta) {
                    $tempoResposta = $resposta->calcularTempoResposta();
                    if ($tempoResposta > 0) {
                        $tempoTotal += $tempoResposta;
                        $totalRespostas++;
                    }
                }
            }
        }

        if ($totalRespostas === 0) {
            return 'N/A';
        }

        $tempoMedio = $tempoTotal / $totalRespostas;
        
        // Converter para formato legível
        if ($tempoMedio < 60) {
            return round($tempoMedio) . 's';
        } elseif ($tempoMedio < 3600) {
            return round($tempoMedio / 60) . 'min';
        } else {
            return round($tempoMedio / 3600, 1) . 'h';
        }
    }
}