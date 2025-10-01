<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\EbdGrupoEstudo;
use App\Models\EbdAvaliacaoGrupo;
use App\Models\EbdRespostaGrupo;
use App\Models\EbdContribuicaoResposta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EbdGrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aluno = Auth::user()->ebdAluno;
        
        if (!$aluno) {
            return redirect()->route('member.ebd.dashboard')
                ->with('error', 'Você não está matriculado em nenhuma turma EBD.');
        }

        // Grupos do aluno
        $meuGrupo = $aluno->gruposComoMembro()->with(['turma', 'lider', 'membros'])->first();
        $grupoLiderado = $aluno->gruposComoLider()->with(['turma', 'membros'])->first();
        
        // Grupos disponíveis da mesma turma (se não estiver em nenhum grupo)
        $gruposDisponiveis = collect();
        if (!$meuGrupo && !$grupoLiderado) {
            $gruposDisponiveis = EbdGrupoEstudo::where('turma_id', $aluno->turma_id)
                ->ativos()
                ->whereRaw('(SELECT COUNT(*) FROM ebd_grupo_membros WHERE grupo_id = ebd_grupos_estudo.id) < max_membros')
                ->with(['lider', 'membros'])
                ->orderBy('nome')
                ->get();
        }

        return view('member.ebd.grupos.index', compact(
            'meuGrupo', 'grupoLiderado', 'gruposDisponiveis', 'aluno'
        ));
    }

    /**
     * Show the specified resource.
     */
    public function show(EbdGrupoEstudo $grupo)
    {
        $aluno = Auth::user()->ebdAluno;
        
        // Verificar se o aluno pertence ao grupo
        if (!$grupo->pertenceAoGrupo($aluno->id) && $grupo->lider_id !== $aluno->id) {
            return redirect()->route('member.ebd.grupos.index')
                ->with('error', 'Você não tem acesso a este grupo.');
        }

        $grupo->load([
            'turma', 
            'lider', 
            'membros', 
            'avaliacoes.avaliacao.questoes',
            'avaliacoes.respostasGrupo.questao'
        ]);
        
        $estatisticas = $grupo->estatisticas();
        $isLider = $grupo->lider_id === $aluno->id;

        return view('member.ebd.grupos.show', compact('grupo', 'estatisticas', 'isLider', 'aluno'));
    }

    /**
     * Solicitar entrada no grupo
     */
    public function solicitarEntrada(EbdGrupoEstudo $grupo)
    {
        $aluno = Auth::user()->ebdAluno;
        
        // Verificações
        if ($grupo->turma_id !== $aluno->turma_id) {
            return back()->with('error', 'Você só pode entrar em grupos da sua turma.');
        }

        if ($grupo->status !== 'ativo') {
            return back()->with('error', 'Este grupo não está ativo.');
        }

        if ($grupo->membros()->count() >= $grupo->max_membros) {
            return back()->with('error', 'Este grupo já atingiu o número máximo de membros.');
        }

        if ($grupo->pertenceAoGrupo($aluno->id)) {
            return back()->with('error', 'Você já pertence a este grupo.');
        }

        // Verificar se já está em outro grupo
        $outroGrupo = $aluno->gruposComoMembro()->first();
        if ($outroGrupo) {
            return back()->with('error', 'Você já pertence ao grupo: ' . $outroGrupo->nome);
        }

        DB::beginTransaction();
        try {
            $grupo->adicionarMembro($aluno->id);
            DB::commit();
            
            return redirect()->route('member.ebd.grupos.show', $grupo)
                ->with('success', 'Você entrou no grupo com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erro ao entrar no grupo: ' . $e->getMessage());
        }
    }

    /**
     * Sair do grupo
     */
    public function sairDoGrupo(EbdGrupoEstudo $grupo)
    {
        $aluno = Auth::user()->ebdAluno;
        
        if (!$grupo->pertenceAoGrupo($aluno->id)) {
            return back()->with('error', 'Você não pertence a este grupo.');
        }

        if ($grupo->lider_id === $aluno->id) {
            return back()->with('error', 'O líder não pode sair do grupo. Transfira a liderança primeiro.');
        }

        // Verificar se há avaliações em andamento
        $avaliacoesEmAndamento = $grupo->avaliacoes()
            ->whereIn('status', ['pendente', 'em_andamento'])
            ->exists();

        if ($avaliacoesEmAndamento) {
            return back()->with('error', 'Não é possível sair do grupo com avaliações em andamento.');
        }

        DB::beginTransaction();
        try {
            $grupo->removerMembro($aluno->id);
            DB::commit();
            
            return redirect()->route('member.ebd.grupos.index')
                ->with('success', 'Você saiu do grupo com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erro ao sair do grupo: ' . $e->getMessage());
        }
    }

    /**
     * Transferir liderança (apenas para líderes)
     */
    public function transferirLideranca(Request $request, EbdGrupoEstudo $grupo)
    {
        $aluno = Auth::user()->ebdAluno;
        
        if ($grupo->lider_id !== $aluno->id) {
            return back()->with('error', 'Apenas o líder pode transferir a liderança.');
        }

        $request->validate([
            'novo_lider_id' => 'required|exists:ebd_alunos,id'
        ]);

        $novoLider = $grupo->membros()->find($request->novo_lider_id);
        if (!$novoLider) {
            return back()->with('error', 'O novo líder deve ser um membro do grupo.');
        }

        DB::beginTransaction();
        try {
            $grupo->definirLider($request->novo_lider_id);
            DB::commit();
            
            return back()->with('success', 'Liderança transferida com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erro ao transferir liderança: ' . $e->getMessage());
        }
    }

    /**
     * Listar avaliações do grupo
     */
    public function avaliacoes(EbdGrupoEstudo $grupo)
    {
        $aluno = Auth::user()->ebdAluno;
        
        if (!$grupo->pertenceAoGrupo($aluno->id) && $grupo->lider_id !== $aluno->id) {
            return redirect()->route('member.ebd.grupos.index')
                ->with('error', 'Você não tem acesso a este grupo.');
        }

        $avaliacoes = $grupo->avaliacoes()
            ->with(['avaliacao.questoes', 'respostasGrupo'])
            ->orderBy('created_at', 'desc')
            ->get();

        $isLider = $grupo->lider_id === $aluno->id;

        return view('member.ebd.grupos.avaliacoes', compact('grupo', 'avaliacoes', 'isLider', 'aluno'));
    }

    /**
     * Realizar avaliação em grupo
     */
    public function realizarAvaliacao(EbdAvaliacaoGrupo $avaliacaoGrupo)
    {
        $aluno = Auth::user()->ebdAluno;
        $grupo = $avaliacaoGrupo->grupo;
        
        if (!$grupo->pertenceAoGrupo($aluno->id) && $grupo->lider_id !== $aluno->id) {
            return redirect()->route('member.ebd.grupos.index')
                ->with('error', 'Você não tem acesso a esta avaliação.');
        }

        if ($avaliacaoGrupo->status === 'concluida') {
            return redirect()->route('member.ebd.grupos.avaliacoes', $grupo)
                ->with('info', 'Esta avaliação já foi concluída.');
        }

        // Verificar se ainda está no prazo
        if ($avaliacaoGrupo->data_limite && Carbon::now()->gt($avaliacaoGrupo->data_limite)) {
            return redirect()->route('member.ebd.grupos.avaliacoes', $grupo)
                ->with('error', 'O prazo para esta avaliação expirou.');
        }

        $avaliacaoGrupo->load([
            'avaliacao.questoes',
            'respostasGrupo.questao',
            'respostasGrupo.contribuicoes.aluno'
        ]);

        $isLider = $grupo->lider_id === $aluno->id;
        $progresso = $avaliacaoGrupo->progresso();

        return view('member.ebd.grupos.realizar-avaliacao', compact(
            'avaliacaoGrupo', 'grupo', 'aluno', 'isLider', 'progresso'
        ));
    }

    /**
     * Contribuir com resposta
     */
    public function contribuirResposta(Request $request, EbdAvaliacaoGrupo $avaliacaoGrupo)
    {
        $aluno = Auth::user()->ebdAluno;
        $grupo = $avaliacaoGrupo->grupo;
        
        if (!$grupo->pertenceAoGrupo($aluno->id) && $grupo->lider_id !== $aluno->id) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        if ($avaliacaoGrupo->status !== 'em_andamento') {
            return response()->json(['error' => 'Avaliação não está em andamento'], 400);
        }

        $request->validate([
            'questao_id' => 'required|exists:ebd_questoes,id',
            'resposta' => 'required|string',
            'tipo' => 'required|in:resposta,discussao,sugestao'
        ]);

        // Verificar se a questão pertence à avaliação
        $questao = $avaliacaoGrupo->avaliacao->questoes()->find($request->questao_id);
        if (!$questao) {
            return response()->json(['error' => 'Questão não encontrada'], 404);
        }

        DB::beginTransaction();
        try {
            // Buscar ou criar resposta do grupo para esta questão
            $respostaGrupo = EbdRespostaGrupo::firstOrCreate([
                'avaliacao_grupo_id' => $avaliacaoGrupo->id,
                'questao_id' => $request->questao_id
            ], [
                'aluno_respondeu_id' => $aluno->id,
                'tempo_inicio' => now()
            ]);

            // Criar contribuição
            $contribuicao = EbdContribuicaoResposta::create([
                'resposta_grupo_id' => $respostaGrupo->id,
                'aluno_id' => $aluno->id,
                'conteudo' => $request->resposta,
                'tipo' => $request->tipo,
                'status' => 'pendente'
            ]);

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Contribuição adicionada com sucesso!',
                'contribuicao' => $contribuicao->load('aluno')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Erro ao salvar contribuição'], 500);
        }
    }

    /**
     * Finalizar resposta do grupo (apenas líder)
     */
    public function finalizarResposta(Request $request, EbdRespostaGrupo $respostaGrupo)
    {
        $aluno = Auth::user()->ebdAluno;
        $grupo = $respostaGrupo->avaliacaoGrupo->grupo;
        
        if ($grupo->lider_id !== $aluno->id) {
            return response()->json(['error' => 'Apenas o líder pode finalizar respostas'], 403);
        }

        $request->validate([
            'resposta_final' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $respostaGrupo->update([
                'resposta_final' => $request->resposta_final,
                'tempo_fim' => now(),
                'finalizada' => true
            ]);

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Resposta finalizada com sucesso!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Erro ao finalizar resposta'], 500);
        }
    }

    /**
     * Submeter avaliação do grupo (apenas líder)
     */
    public function submeterAvaliacao(EbdAvaliacaoGrupo $avaliacaoGrupo)
    {
        $aluno = Auth::user()->ebdAluno;
        $grupo = $avaliacaoGrupo->grupo;
        
        if ($grupo->lider_id !== $aluno->id) {
            return back()->with('error', 'Apenas o líder pode submeter a avaliação.');
        }

        if ($avaliacaoGrupo->status !== 'em_andamento') {
            return back()->with('error', 'Esta avaliação não está em andamento.');
        }

        // Verificar se todas as questões foram respondidas
        $totalQuestoes = $avaliacaoGrupo->avaliacao->questoes()->count();
        $respostasFinalizadas = $avaliacaoGrupo->respostasGrupo()->where('finalizada', true)->count();

        if ($respostasFinalizadas < $totalQuestoes) {
            return back()->with('error', 'Todas as questões devem ser respondidas antes de submeter.');
        }

        DB::beginTransaction();
        try {
            $avaliacaoGrupo->finalizar();
            DB::commit();
            
            return redirect()->route('member.ebd.grupos.avaliacoes', $grupo)
                ->with('success', 'Avaliação submetida com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erro ao submeter avaliação: ' . $e->getMessage());
        }
    }
}