<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EbdAvaliacaoGrupo;
use App\Models\EbdAvaliacao;
use App\Models\EbdGrupoEstudo;
use App\Models\EbdTurma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EbdAvaliacaoGrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EbdAvaliacaoGrupo::with(['avaliacao.aula', 'grupo.turma', 'grupo.lider'])
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('turma_id')) {
            $query->whereHas('grupo', function($q) use ($request) {
                $q->where('turma_id', $request->turma_id);
            });
        }

        if ($request->filled('avaliacao_id')) {
            $query->where('avaliacao_id', $request->avaliacao_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('grupo_id')) {
            $query->where('grupo_id', $request->grupo_id);
        }

        $avaliacoesGrupo = $query->paginate(15);
        $turmas = EbdTurma::ativas()->orderBy('nome')->get();
        $avaliacoes = EbdAvaliacao::permiteGrupos()->ativas()->orderBy('titulo')->get();
        $grupos = EbdGrupoEstudo::ativos()->orderBy('nome')->get();

        return view('admin.ebd.avaliacoes-grupo.index', compact(
            'avaliacoesGrupo', 'turmas', 'avaliacoes', 'grupos'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $avaliacoes = EbdAvaliacao::permiteGrupos()->ativas()->orderBy('titulo')->get();
        $grupos = collect();
        
        if ($request->filled('avaliacao_id')) {
            $avaliacao = EbdAvaliacao::find($request->avaliacao_id);
            if ($avaliacao) {
                // Buscar grupos da mesma turma da avaliação
                $grupos = EbdGrupoEstudo::whereHas('turma.aulas.avaliacoes', function($q) use ($avaliacao) {
                    $q->where('id', $avaliacao->id);
                })->ativos()->orderBy('nome')->get();
            }
        }
        
        return view('admin.ebd.avaliacoes-grupo.create', compact('avaliacoes', 'grupos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'avaliacao_id' => 'required|exists:ebd_avaliacoes,id',
            'grupo_id' => 'required|exists:ebd_grupos_estudo,id',
            'data_inicio' => 'nullable|date|after_or_equal:today',
            'data_limite' => 'nullable|date|after:data_inicio',
            'observacoes' => 'nullable|string|max:1000'
        ]);

        // Verificar se a avaliação permite grupos
        $avaliacao = EbdAvaliacao::find($validated['avaliacao_id']);
        if (!$avaliacao->permiteGrupos()) {
            return back()->withErrors(['avaliacao_id' => 'Esta avaliação não permite modo em grupo.']);
        }

        // Verificar se já existe uma avaliação para este grupo
        $existeAvaliacao = EbdAvaliacaoGrupo::where('avaliacao_id', $validated['avaliacao_id'])
            ->where('grupo_id', $validated['grupo_id'])
            ->exists();

        if ($existeAvaliacao) {
            return back()->withErrors(['grupo_id' => 'Este grupo já possui uma avaliação para esta atividade.']);
        }

        // Definir datas padrão se não fornecidas
        if (!$validated['data_inicio']) {
            $validated['data_inicio'] = now();
        }

        if (!$validated['data_limite'] && $avaliacao->temTempoLimite()) {
            $validated['data_limite'] = Carbon::parse($validated['data_inicio'])
                ->addMinutes($avaliacao->tempo_limite_minutos);
        }

        $validated['status'] = 'pendente';
        $validated['percentual'] = 0;
        $validated['tempo_gasto_minutos'] = 0;

        DB::beginTransaction();
        try {
            $avaliacaoGrupo = EbdAvaliacaoGrupo::create($validated);
            
            DB::commit();
            
            return redirect()->route('admin.ebd.avaliacoes-grupo.index')
                ->with('success', 'Avaliação em grupo criada com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erro ao criar avaliação: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EbdAvaliacaoGrupo $avaliacaoGrupo)
    {
        $avaliacaoGrupo->load([
            'avaliacao.questoes',
            'grupo.turma',
            'grupo.lider',
            'grupo.membros',
            'respostasGrupo.questao',
            'respostasGrupo.contribuicoes.aluno'
        ]);
        
        $estatisticas = $avaliacaoGrupo->estatisticas();
        $progresso = $avaliacaoGrupo->progresso();
        
        return view('admin.ebd.avaliacoes-grupo.show', compact(
            'avaliacaoGrupo', 'estatisticas', 'progresso'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EbdAvaliacaoGrupo $avaliacaoGrupo)
    {
        $avaliacaoGrupo->load(['avaliacao', 'grupo']);
        
        // Não permitir edição se já foi iniciada
        if ($avaliacaoGrupo->status !== 'pendente') {
            return redirect()->route('admin.ebd.avaliacoes-grupo.show', $avaliacaoGrupo)
                ->with('warning', 'Não é possível editar uma avaliação que já foi iniciada.');
        }
        
        return view('admin.ebd.avaliacoes-grupo.edit', compact('avaliacaoGrupo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EbdAvaliacaoGrupo $avaliacaoGrupo)
    {
        // Não permitir edição se já foi iniciada
        if ($avaliacaoGrupo->status !== 'pendente') {
            return back()->withErrors(['error' => 'Não é possível editar uma avaliação que já foi iniciada.']);
        }

        $validated = $request->validate([
            'data_inicio' => 'nullable|date|after_or_equal:today',
            'data_limite' => 'nullable|date|after:data_inicio',
            'observacoes' => 'nullable|string|max:1000'
        ]);

        // Definir data limite baseada no tempo limite da avaliação
        if (!$validated['data_limite'] && $avaliacaoGrupo->avaliacao->temTempoLimite()) {
            $validated['data_limite'] = Carbon::parse($validated['data_inicio'] ?? $avaliacaoGrupo->data_inicio)
                ->addMinutes($avaliacaoGrupo->avaliacao->tempo_limite_minutos);
        }

        DB::beginTransaction();
        try {
            $avaliacaoGrupo->update($validated);
            
            DB::commit();
            
            return redirect()->route('admin.ebd.avaliacoes-grupo.index')
                ->with('success', 'Avaliação em grupo atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erro ao atualizar avaliação: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EbdAvaliacaoGrupo $avaliacaoGrupo)
    {
        // Não permitir exclusão se já foi iniciada
        if ($avaliacaoGrupo->status !== 'pendente') {
            return back()->withErrors(['error' => 'Não é possível excluir uma avaliação que já foi iniciada.']);
        }

        DB::beginTransaction();
        try {
            $avaliacaoGrupo->delete();
            DB::commit();
            
            return redirect()->route('admin.ebd.avaliacoes-grupo.index')
                ->with('success', 'Avaliação em grupo excluída com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erro ao excluir avaliação: ' . $e->getMessage()]);
        }
    }

    /**
     * Iniciar avaliação em grupo
     */
    public function iniciar(EbdAvaliacaoGrupo $avaliacaoGrupo)
    {
        if ($avaliacaoGrupo->status !== 'pendente') {
            return back()->withErrors(['error' => 'Esta avaliação já foi iniciada ou concluída.']);
        }

        DB::beginTransaction();
        try {
            $avaliacaoGrupo->iniciar();
            DB::commit();
            
            return back()->with('success', 'Avaliação iniciada com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erro ao iniciar avaliação: ' . $e->getMessage()]);
        }
    }

    /**
     * Finalizar avaliação em grupo
     */
    public function finalizar(EbdAvaliacaoGrupo $avaliacaoGrupo)
    {
        if ($avaliacaoGrupo->status !== 'em_andamento') {
            return back()->withErrors(['error' => 'Esta avaliação não está em andamento.']);
        }

        DB::beginTransaction();
        try {
            $avaliacaoGrupo->finalizar();
            DB::commit();
            
            return back()->with('success', 'Avaliação finalizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erro ao finalizar avaliação: ' . $e->getMessage()]);
        }
    }

    /**
     * Buscar grupos por avaliação via AJAX
     */
    public function getGruposPorAvaliacao(Request $request)
    {
        $avaliacaoId = $request->get('avaliacao_id');
        
        if (!$avaliacaoId) {
            return response()->json([]);
        }

        $avaliacao = EbdAvaliacao::find($avaliacaoId);
        if (!$avaliacao || !$avaliacao->permiteGrupos()) {
            return response()->json([]);
        }

        // Buscar grupos da turma relacionada à avaliação
        $grupos = EbdGrupoEstudo::whereHas('turma.aulas', function($q) use ($avaliacao) {
            $q->where('id', $avaliacao->aula_id);
        })
        ->ativos()
        ->whereDoesntHave('avaliacoes', function($q) use ($avaliacaoId) {
            $q->where('avaliacao_id', $avaliacaoId);
        })
        ->with(['lider', 'membros'])
        ->orderBy('nome')
        ->get(['id', 'nome', 'lider_id']);

        return response()->json($grupos);
    }

    /**
     * Relatório de avaliações em grupo
     */
    public function relatorio(Request $request)
    {
        $query = EbdAvaliacaoGrupo::with([
            'avaliacao.aula',
            'grupo.turma',
            'grupo.lider',
            'respostasGrupo'
        ]);

        if ($request->filled('turma_id')) {
            $query->whereHas('grupo', function($q) use ($request) {
                $q->where('turma_id', $request->turma_id);
            });
        }

        if ($request->filled('avaliacao_id')) {
            $query->where('avaliacao_id', $request->avaliacao_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('data_inicio', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data_inicio', '<=', $request->data_fim);
        }

        $avaliacoesGrupo = $query->get();
        $turmas = EbdTurma::ativas()->orderBy('nome')->get();
        $avaliacoes = EbdAvaliacao::permiteGrupos()->ativas()->orderBy('titulo')->get();
        
        $estatisticasGerais = [
            'total_avaliacoes' => $avaliacoesGrupo->count(),
            'pendentes' => $avaliacoesGrupo->where('status', 'pendente')->count(),
            'em_andamento' => $avaliacoesGrupo->where('status', 'em_andamento')->count(),
            'concluidas' => $avaliacoesGrupo->where('status', 'concluida')->count(),
            'media_percentual' => $avaliacoesGrupo->where('status', 'concluida')->avg('percentual') ?? 0,
            'tempo_medio_minutos' => $avaliacoesGrupo->where('status', 'concluida')->avg('tempo_gasto_minutos') ?? 0
        ];

        return view('admin.ebd.avaliacoes-grupo.relatorio', compact(
            'avaliacoesGrupo', 'turmas', 'avaliacoes', 'estatisticasGerais'
        ));
    }
}