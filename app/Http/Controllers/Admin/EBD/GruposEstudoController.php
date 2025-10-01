<?php

namespace App\Http\Controllers\Admin\EBD;

use App\Http\Controllers\Controller;
use App\Models\EBD\EbdGrupoEstudo;
use App\Models\EBD\EbdGrupoMembro;
use App\Models\EbdTurma;
use App\Models\EbdAluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GruposEstudoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EbdGrupoEstudo::with(['turma', 'lider', 'membrosAtivos']);

        // Filtros
        if ($request->filled('turma_id')) {
            $query->where('turma_id', $request->turma_id);
        }

        if ($request->filled('status')) {
            $query->where('ativo', $request->status === 'ativo');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%")
                    ->orWhereHas('turma', function ($tq) use ($search) {
                        $tq->where('nome', 'like', "%{$search}%");
                    });
            });
        }

        $grupos = $query->orderBy('nome')->paginate(15);
        $turmas = EbdTurma::where('ativo', true)->orderBy('nome')->get();

        return view('admin.ebd.grupos-estudo.index', compact('grupos', 'turmas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $turmas = EbdTurma::where('ativo', true)->orderBy('nome')->get();
        return view('admin.ebd.grupos-estudo.create', compact('turmas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'turma_id' => 'required|exists:ebd_turmas,id',
            'descricao' => 'nullable|string',
            'cor' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'capacidade_maxima' => 'required|integer|min:2|max:50',
            'lider_id' => 'nullable|exists:ebd_alunos,id',
            'membros_iniciais' => 'nullable|array',
            'membros_iniciais.*' => 'exists:ebd_alunos,id'
        ]);

        DB::beginTransaction();
        try {
            // Verificar se o líder pertence à turma
            if ($validated['lider_id']) {
                $lider = EbdAluno::find($validated['lider_id']);
                if ($lider->turma_id !== (int)$validated['turma_id']) {
                    return back()->withErrors(['lider_id' => 'O líder deve pertencer à turma selecionada.'])->withInput();
                }
            }

            // Criar o grupo
            $grupo = EbdGrupoEstudo::create([
                'nome' => $validated['nome'],
                'turma_id' => $validated['turma_id'],
                'descricao' => $validated['descricao'],
                'cor' => $validated['cor'],
                'capacidade_maxima' => $validated['capacidade_maxima'],
                'lider_id' => $validated['lider_id'],
                'ativo' => true
            ]);

            // Adicionar membros iniciais
            if (!empty($validated['membros_iniciais'])) {
                foreach ($validated['membros_iniciais'] as $alunoId) {
                    // Verificar se o aluno pertence à turma
                    $aluno = EbdAluno::find($alunoId);
                    if ($aluno->turma_id === (int)$validated['turma_id']) {
                        EbdGrupoMembro::create([
                            'grupo_id' => $grupo->id,
                            'aluno_id' => $alunoId,
                            'data_entrada' => now(),
                            'status' => 'ativo'
                        ]);
                    }
                }
            }

            // Se o líder foi definido, garantir que ele seja membro do grupo
            if ($validated['lider_id']) {
                $liderJaMembro = EbdGrupoMembro::where('grupo_id', $grupo->id)
                    ->where('aluno_id', $validated['lider_id'])
                    ->exists();

                if (!$liderJaMembro) {
                    EbdGrupoMembro::create([
                        'grupo_id' => $grupo->id,
                        'aluno_id' => $validated['lider_id'],
                        'data_entrada' => now(),
                        'status' => 'ativo'
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.ebd.grupos-estudo.index')
                ->with('success', 'Grupo de estudo criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erro ao criar grupo: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EbdGrupoEstudo $gruposEstudo)
    {
        $grupo = $gruposEstudo->load([
            'turma',
            'lider',
            'membrosAtivos.aluno',
            'avaliacoes.avaliacao'
        ]);

        return view('admin.ebd.grupos-estudo.show', compact('grupo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EbdGrupoEstudo $gruposEstudo)
    {
        $grupo = $gruposEstudo->load(['turma', 'lider', 'membrosAtivos.aluno']);
        $turmas = EbdTurma::where('ativo', true)->orderBy('nome')->get();

        // Buscar alunos da turma do grupo
        $alunosTurma = EbdAluno::where('turma_id', $grupo->turma_id)
            ->where('ativo', true)
            ->orderBy('nome')
            ->get();

        return view('admin.ebd.grupos-estudo.edit', compact('grupo', 'turmas', 'alunosTurma'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EbdGrupoEstudo $gruposEstudo)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'turma_id' => 'required|exists:ebd_turmas,id',
            'descricao' => 'nullable|string',
            'cor' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'capacidade_maxima' => 'required|integer|min:2|max:50',
            'lider_id' => 'nullable|exists:ebd_alunos,id',
            'ativo' => 'boolean'
        ]);

        DB::beginTransaction();
        try {
            // Verificar se o líder pertence à turma
            if ($validated['lider_id']) {
                $lider = EbdAluno::find($validated['lider_id']);
                if ($lider->turma_id !== (int)$validated['turma_id']) {
                    return back()->withErrors(['lider_id' => 'O líder deve pertencer à turma selecionada.'])->withInput();
                }
            }

            // Se a turma mudou, remover todos os membros que não pertencem à nova turma
            if ($gruposEstudo->turma_id !== (int)$validated['turma_id']) {
                $membrosInvalidos = EbdGrupoMembro::where('grupo_id', $gruposEstudo->id)
                    ->whereHas('aluno', function ($q) use ($validated) {
                        $q->where('turma_id', '!=', $validated['turma_id']);
                    })
                    ->get();

                foreach ($membrosInvalidos as $membro) {
                    $membro->update([
                        'status' => 'removido',
                        'data_saida' => now()
                    ]);
                }
            }

            // Atualizar o grupo
            $gruposEstudo->update($validated);

            // Se o líder foi definido, garantir que ele seja membro do grupo
            if ($validated['lider_id']) {
                $liderJaMembro = EbdGrupoMembro::where('grupo_id', $gruposEstudo->id)
                    ->where('aluno_id', $validated['lider_id'])
                    ->where('status', 'ativo')
                    ->exists();

                if (!$liderJaMembro) {
                    // Verificar se já foi membro antes
                    $membroAnterior = EbdGrupoMembro::where('grupo_id', $gruposEstudo->id)
                        ->where('aluno_id', $validated['lider_id'])
                        ->first();

                    if ($membroAnterior) {
                        $membroAnterior->update([
                            'status' => 'ativo',
                            'data_saida' => null
                        ]);
                    } else {
                        EbdGrupoMembro::create([
                            'grupo_id' => $gruposEstudo->id,
                            'aluno_id' => $validated['lider_id'],
                            'data_entrada' => now(),
                            'status' => 'ativo'
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.ebd.grupos-estudo.show', $gruposEstudo)
                ->with('success', 'Grupo de estudo atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erro ao atualizar grupo: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EbdGrupoEstudo $gruposEstudo)
    {
        DB::beginTransaction();
        try {
            // Remover todos os membros do grupo
            EbdGrupoMembro::where('grupo_id', $gruposEstudo->id)
                ->update([
                    'status' => 'removido',
                    'data_saida' => now()
                ]);

            // Excluir o grupo
            $gruposEstudo->delete();

            DB::commit();
            return redirect()->route('admin.ebd.grupos-estudo.index')
                ->with('success', 'Grupo de estudo excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erro ao excluir grupo: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle the status of the group.
     */
    public function toggleStatus(EbdGrupoEstudo $gruposEstudo)
    {
        $gruposEstudo->update(['ativo' => !$gruposEstudo->ativo]);

        $status = $gruposEstudo->ativo ? 'ativado' : 'desativado';
        return back()->with('success', "Grupo {$status} com sucesso!");
    }

    /**
     * Add a member to the group.
     */
    public function adicionarMembro(Request $request, EbdGrupoEstudo $gruposEstudo)
    {
        $validated = $request->validate([
            'aluno_id' => 'required|exists:ebd_alunos,id'
        ]);

        $aluno = EbdAluno::find($validated['aluno_id']);

        // Verificar se o aluno pertence à turma do grupo
        if ($aluno->turma_id !== $gruposEstudo->turma_id) {
            return back()->withErrors(['error' => 'O aluno deve pertencer à turma do grupo.']);
        }

        // Verificar se o grupo não está lotado
        if ($gruposEstudo->membrosAtivos->count() >= $gruposEstudo->capacidade_maxima) {
            return back()->withErrors(['error' => 'O grupo já atingiu sua capacidade máxima.']);
        }

        // Verificar se já é membro ativo
        $jaEMembro = EbdGrupoMembro::where('grupo_id', $gruposEstudo->id)
            ->where('aluno_id', $validated['aluno_id'])
            ->where('status', 'ativo')
            ->exists();

        if ($jaEMembro) {
            return back()->withErrors(['error' => 'Este aluno já é membro do grupo.']);
        }

        // Verificar se já foi membro antes
        $membroAnterior = EbdGrupoMembro::where('grupo_id', $gruposEstudo->id)
            ->where('aluno_id', $validated['aluno_id'])
            ->first();

        if ($membroAnterior) {
            $membroAnterior->update([
                'status' => 'ativo',
                'data_saida' => null
            ]);
        } else {
            EbdGrupoMembro::create([
                'grupo_id' => $gruposEstudo->id,
                'aluno_id' => $validated['aluno_id'],
                'data_entrada' => now(),
                'status' => 'ativo'
            ]);
        }

        return back()->with('success', 'Membro adicionado ao grupo com sucesso!');
    }

    /**
     * Remove a member from the group.
     */
    public function removerMembro(EbdGrupoEstudo $gruposEstudo, EbdGrupoMembro $membro)
    {
        if ($membro->grupo_id !== $gruposEstudo->id) {
            return back()->withErrors(['error' => 'Membro não pertence a este grupo.']);
        }

        // Se for o líder, remover a liderança
        if ($gruposEstudo->lider_id === $membro->aluno_id) {
            $gruposEstudo->update(['lider_id' => null]);
        }

        $membro->update([
            'status' => 'removido',
            'data_saida' => now()
        ]);

        return back()->with('success', 'Membro removido do grupo com sucesso!');
    }

    /**
     * Get students by class for AJAX requests.
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
     * Get all active students for AJAX requests.
     */
    public function getTodosAlunos(Request $request)
    {
        $alunos = EbdAluno::where('ativo', true)
            ->with('turma:id,nome')
            ->orderBy('nome')
            ->get(['id', 'nome', 'email', 'turma_id']);

        return response()->json($alunos);
    }

    /**
     * Generate group report.
     */
    public function relatorio(EbdGrupoEstudo $gruposEstudo)
    {
        $grupo = $gruposEstudo->load([
            'turma',
            'lider',
            'membrosAtivos.aluno',
            'avaliacoes.avaliacao',
            'avaliacoes.respostas.contribuicoes'
        ]);

        return view('admin.ebd.grupos-estudo.relatorio', compact('grupo'));
    }
}
