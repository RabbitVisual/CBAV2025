<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EbdGrupoEstudo;
use App\Models\EbdTurma;
use App\Models\EbdAluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EbdGrupoEstudoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EbdGrupoEstudo::with(['turma', 'lider', 'membros'])
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('turma_id')) {
            $query->where('turma_id', $request->turma_id);
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

        $grupos = $query->paginate(15);
        $turmas = EbdTurma::ativas()->orderBy('nome')->get();

        return view('admin.ebd.grupos.index', compact('grupos', 'turmas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $turmas = EbdTurma::ativas()->orderBy('nome')->get();
        $alunos = collect();
        
        return view('admin.ebd.grupos.create', compact('turmas', 'alunos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'turma_id' => 'required|exists:ebd_turmas,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'lider_id' => 'required|exists:ebd_alunos,id',
            'max_membros' => 'required|integer|min:2|max:10',
            'status' => 'required|in:ativo,inativo',
            'membros' => 'required|array|min:1',
            'membros.*' => 'exists:ebd_alunos,id'
        ]);

        // Verificar se o líder está na mesma turma
        $lider = EbdAluno::find($validated['lider_id']);
        if ($lider->turma_id != $validated['turma_id']) {
            return back()->withErrors(['lider_id' => 'O líder deve pertencer à turma selecionada.']);
        }

        // Verificar se todos os membros estão na mesma turma
        $membros = EbdAluno::whereIn('id', $validated['membros'])->get();
        foreach ($membros as $membro) {
            if ($membro->turma_id != $validated['turma_id']) {
                return back()->withErrors(['membros' => 'Todos os membros devem pertencer à turma selecionada.']);
            }
        }

        // Verificar se não excede o máximo de membros
        if (count($validated['membros']) > $validated['max_membros']) {
            return back()->withErrors(['membros' => 'O número de membros não pode exceder o máximo permitido.']);
        }

        DB::beginTransaction();
        try {
            $grupo = EbdGrupoEstudo::create($validated);
            
            // Adicionar membros
            $grupo->adicionarMembros($validated['membros']);
            
            DB::commit();
            
            return redirect()->route('admin.ebd.grupos.index')
                ->with('success', 'Grupo de estudo criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erro ao criar grupo: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EbdGrupoEstudo $grupo)
    {
        $grupo->load(['turma', 'lider', 'membros', 'avaliacoes.avaliacao']);
        $estatisticas = $grupo->estatisticas();
        
        return view('admin.ebd.grupos.show', compact('grupo', 'estatisticas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EbdGrupoEstudo $grupo)
    {
        $grupo->load(['turma', 'lider', 'membros']);
        $turmas = EbdTurma::ativas()->orderBy('nome')->get();
        $alunos = EbdAluno::where('turma_id', $grupo->turma_id)
            ->orderBy('nome')
            ->get();
        
        return view('admin.ebd.grupos.edit', compact('grupo', 'turmas', 'alunos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EbdGrupoEstudo $grupo)
    {
        $validated = $request->validate([
            'turma_id' => 'required|exists:ebd_turmas,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'lider_id' => 'required|exists:ebd_alunos,id',
            'max_membros' => 'required|integer|min:2|max:10',
            'status' => 'required|in:ativo,inativo',
            'membros' => 'required|array|min:1',
            'membros.*' => 'exists:ebd_alunos,id'
        ]);

        // Verificar se o líder está na mesma turma
        $lider = EbdAluno::find($validated['lider_id']);
        if ($lider->turma_id != $validated['turma_id']) {
            return back()->withErrors(['lider_id' => 'O líder deve pertencer à turma selecionada.']);
        }

        // Verificar se todos os membros estão na mesma turma
        $membros = EbdAluno::whereIn('id', $validated['membros'])->get();
        foreach ($membros as $membro) {
            if ($membro->turma_id != $validated['turma_id']) {
                return back()->withErrors(['membros' => 'Todos os membros devem pertencer à turma selecionada.']);
            }
        }

        // Verificar se não excede o máximo de membros
        if (count($validated['membros']) > $validated['max_membros']) {
            return back()->withErrors(['membros' => 'O número de membros não pode exceder o máximo permitido.']);
        }

        DB::beginTransaction();
        try {
            $grupo->update($validated);
            
            // Atualizar membros
            $grupo->atualizarMembros($validated['membros']);
            
            DB::commit();
            
            return redirect()->route('admin.ebd.grupos.index')
                ->with('success', 'Grupo de estudo atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erro ao atualizar grupo: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EbdGrupoEstudo $grupo)
    {
        // Verificar se o grupo tem avaliações em andamento
        if ($grupo->avaliacoes()->whereIn('status', ['pendente', 'em_andamento'])->exists()) {
            return back()->withErrors(['error' => 'Não é possível excluir um grupo com avaliações em andamento.']);
        }

        DB::beginTransaction();
        try {
            $grupo->delete();
            DB::commit();
            
            return redirect()->route('admin.ebd.grupos.index')
                ->with('success', 'Grupo de estudo excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erro ao excluir grupo: ' . $e->getMessage()]);
        }
    }

    /**
     * Buscar alunos por turma via AJAX
     */
    public function getAlunosPorTurma(Request $request)
    {
        $turmaId = $request->get('turma_id');
        
        if (!$turmaId) {
            return response()->json([]);
        }

        $alunos = EbdAluno::where('turma_id', $turmaId)
            ->where('ativo', true)
            ->orderBy('nome')
            ->get(['id', 'nome', 'email']);

        return response()->json($alunos);
    }

    /**
     * Ativar/Desativar grupo
     */
    public function toggleStatus(EbdGrupoEstudo $grupo)
    {
        $novoStatus = $grupo->status === 'ativo' ? 'inativo' : 'ativo';
        
        $grupo->update(['status' => $novoStatus]);
        
        $mensagem = $novoStatus === 'ativo' ? 'Grupo ativado com sucesso!' : 'Grupo desativado com sucesso!';
        
        return back()->with('success', $mensagem);
    }

    /**
     * Relatório de grupos
     */
    public function relatorio(Request $request)
    {
        $query = EbdGrupoEstudo::with(['turma', 'lider', 'membros', 'avaliacoes']);

        if ($request->filled('turma_id')) {
            $query->where('turma_id', $request->turma_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $grupos = $query->get();
        $turmas = EbdTurma::ativas()->orderBy('nome')->get();
        
        $estatisticasGerais = [
            'total_grupos' => $grupos->count(),
            'grupos_ativos' => $grupos->where('status', 'ativo')->count(),
            'grupos_inativos' => $grupos->where('status', 'inativo')->count(),
            'media_membros_por_grupo' => $grupos->avg(function($grupo) {
                return $grupo->membros->count();
            }),
            'total_avaliacoes_grupo' => $grupos->sum(function($grupo) {
                return $grupo->avaliacoes->count();
            })
        ];

        return view('admin.ebd.grupos.relatorio', compact('grupos', 'turmas', 'estatisticasGerais'));
    }
}