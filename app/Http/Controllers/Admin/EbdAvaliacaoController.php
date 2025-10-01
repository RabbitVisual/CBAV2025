<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdAvaliacao;
use App\Models\EbdAula;

class EbdAvaliacaoController extends Controller
{
    public function index()
    {
        $avaliacoes = EbdAvaliacao::with(['aula.turma', 'aula.licao'])->orderBy('created_at', 'desc')->get();
        return view('admin.ebd.avaliacoes.index', compact('avaliacoes'));
    }

    public function create()
    {
        $aulas = EbdAula::with(['turma', 'licao'])->orderBy('data_aula', 'desc')->get();
        return view('admin.ebd.avaliacoes.create', compact('aulas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'aula_id' => 'required|exists:ebd_aulas,id',
            'titulo' => 'required|string|max:200',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:quiz,prova,trabalho,participacao',
            'pontuacao_maxima' => 'required|integer|min:1',
            'obrigatoria' => 'boolean',
            'permite_grupos' => 'boolean',
            'tempo_limite_minutos' => 'nullable|integer|min:1|max:480',
            'modo_avaliacao' => 'required|in:individual,grupo,ambos'
        ]);
        
        $data['obrigatoria'] = $request->has('obrigatoria');
        $data['permite_grupos'] = $request->has('permite_grupos');
        
        // Se não permite grupos, forçar modo individual
        if (!$data['permite_grupos']) {
            $data['modo_avaliacao'] = 'individual';
        }
        
        EbdAvaliacao::create($data);
        return redirect()->route('admin.ebd.avaliacoes.index')->with('success', 'Avaliação criada com sucesso!');
    }

    public function show(EbdAvaliacao $avaliacao)
    {
        return view('admin.ebd.avaliacoes.show', compact('avaliacao'));
    }

    public function edit(EbdAvaliacao $avaliacao)
    {
        $aulas = EbdAula::with(['turma', 'licao'])->orderBy('data_aula', 'desc')->get();
        return view('admin.ebd.avaliacoes.edit', compact('avaliacao', 'aulas'));
    }

    public function update(Request $request, EbdAvaliacao $avaliacao)
    {
        $data = $request->validate([
            'aula_id' => 'required|exists:ebd_aulas,id',
            'titulo' => 'required|string|max:200',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:quiz,prova,trabalho,participacao',
            'pontuacao_maxima' => 'required|integer|min:1',
            'obrigatoria' => 'boolean',
            'permite_grupos' => 'boolean',
            'tempo_limite_minutos' => 'nullable|integer|min:1|max:480',
            'modo_avaliacao' => 'required|in:individual,grupo,ambos'
        ]);
        
        $data['obrigatoria'] = $request->has('obrigatoria');
        $data['permite_grupos'] = $request->has('permite_grupos');
        
        // Se não permite grupos, forçar modo individual
        if (!$data['permite_grupos']) {
            $data['modo_avaliacao'] = 'individual';
        }
        
        $avaliacao->update($data);
        return redirect()->route('admin.ebd.avaliacoes.index')->with('success', 'Avaliação atualizada com sucesso!');
    }

    public function destroy(EbdAvaliacao $avaliacao)
    {
        $avaliacao->delete();
        return redirect()->route('admin.ebd.avaliacoes.index')->with('success', 'Avaliação removida com sucesso!');
    }

    /**
     * Relatório da avaliação
     */
    public function relatorio(EbdAvaliacao $avaliacao)
    {
        $avaliacao->load([
            'aula.turma', 
            'aula.licao', 
            'questoes', 
            'notas.aluno',
            'avaliacoesGrupo.grupo.membros'
        ]);
        
        // Estatísticas individuais
        $totalAlunos = $avaliacao->aula->turma->alunos->count();
        $alunosQueFizeram = $avaliacao->notas->count();
        $mediaGeral = $avaliacao->notas->avg('percentual') ?? 0;
        $maiorNota = $avaliacao->notas->max('nota') ?? 0;
        $menorNota = $avaliacao->notas->min('nota') ?? 0;
        
        // Distribuição de notas individuais
        $distribuicaoNotas = [
            'excelente' => $avaliacao->notas->where('percentual', '>=', 90)->count(),
            'bom' => $avaliacao->notas->whereBetween('percentual', [70, 89])->count(),
            'regular' => $avaliacao->notas->whereBetween('percentual', [50, 69])->count(),
            'insuficiente' => $avaliacao->notas->where('percentual', '<', 50)->count(),
        ];
        
        // Estatísticas de grupos (se aplicável)
        $estatisticasGrupos = null;
        if ($avaliacao->permiteGrupos()) {
            $avaliacoesGrupo = $avaliacao->avaliacoesGrupo;
            $estatisticasGrupos = [
                'total_grupos' => $avaliacoesGrupo->count(),
                'grupos_concluidos' => $avaliacoesGrupo->where('status', 'concluida')->count(),
                'grupos_em_andamento' => $avaliacoesGrupo->where('status', 'em_andamento')->count(),
                'grupos_pendentes' => $avaliacoesGrupo->where('status', 'pendente')->count(),
                'media_grupos' => $avaliacoesGrupo->where('status', 'concluida')->avg('percentual') ?? 0,
                'tempo_medio_grupos' => $avaliacoesGrupo->where('status', 'concluida')->avg('tempo_gasto_minutos') ?? 0,
                'total_alunos_grupos' => $avaliacoesGrupo->sum(function($ag) {
                    return $ag->grupo->membros->count();
                })
            ];
            
            // Distribuição de notas dos grupos
            $gruposConcluidos = $avaliacoesGrupo->where('status', 'concluida');
            $estatisticasGrupos['distribuicao_notas'] = [
                'excelente' => $gruposConcluidos->where('percentual', '>=', 90)->count(),
                'bom' => $gruposConcluidos->whereBetween('percentual', [70, 89])->count(),
                'regular' => $gruposConcluidos->whereBetween('percentual', [50, 69])->count(),
                'insuficiente' => $gruposConcluidos->where('percentual', '<', 50)->count(),
            ];
        }
        
        return view('admin.ebd.avaliacoes.relatorio', compact(
            'avaliacao', 
            'totalAlunos', 
            'alunosQueFizeram', 
            'mediaGeral', 
            'maiorNota', 
            'menorNota',
            'distribuicaoNotas',
            'estatisticasGrupos'
        ));
    }
}