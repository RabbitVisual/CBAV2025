<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EbdQuestao;
use App\Models\EbdAvaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EbdQuestaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ebd.access');
    }

    /**
     * Lista de questões
     */
    public function index(Request $request)
    {
        $query = EbdQuestao::with(['avaliacao']);

        // Filtros
        if ($request->filled('avaliacao_id')) {
            $query->where('avaliacao_id', $request->avaliacao_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('search')) {
            $query->where('pergunta', 'like', '%' . $request->search . '%');
        }

        $questoes = $query->orderBy('created_at', 'desc')->paginate(15);
        $avaliacoes = EbdAvaliacao::where('ativo', true)->get();

        return view('admin.ebd.questoes.index', compact('questoes', 'avaliacoes'));
    }

    /**
     * Formulário para criar questão
     */
    public function create(Request $request)
    {
        $avaliacoes = EbdAvaliacao::where('ativo', true)->get();
        $tipos = [
            'multipla_escolha' => 'Múltipla Escolha',
            'verdadeiro_falso' => 'Verdadeiro/Falso',
            'dissertativa' => 'Dissertativa',
            'completar' => 'Completar',
            'associacao' => 'Associação'
        ];

        // Se foi passado um avaliacao_id, pré-selecionar
        $avaliacaoSelecionada = null;
        if ($request->has('avaliacao_id')) {
            $avaliacaoSelecionada = EbdAvaliacao::find($request->avaliacao_id);
        }

        return view('admin.ebd.questoes.create', compact('avaliacoes', 'tipos', 'avaliacaoSelecionada'));
    }

    /**
     * Salvar nova questão
     */
    public function store(Request $request)
    {
        $request->validate([
            'avaliacao_id' => 'required|exists:ebd_avaliacoes,id',
            'pergunta' => 'required|string|max:1000',
            'tipo' => 'required|in:multipla_escolha,verdadeiro_falso,dissertativa,completar,associacao',
            'opcoes' => 'nullable|array',
            'resposta_correta' => 'nullable|string|max:500',
            'pontuacao' => 'required|integer|min:1|max:100',
            'explicacao' => 'nullable|string|max:1000',
            'dificuldade' => 'required|in:facil,medio,dificil',
            'ativo' => 'boolean'
        ]);

        // Processar opções se for múltipla escolha
        if ($request->tipo === 'multipla_escolha' && $request->opcoes) {
            $opcoes = array_filter($request->opcoes, function($opcao) {
                return !empty(trim($opcao));
            });
            $request->merge(['opcoes' => $opcoes]);
        }

        EbdQuestao::create($request->all());

        return redirect()->route('admin.ebd.questoes.index')
            ->with('success', 'Questão criada com sucesso!');
    }

    /**
     * Mostrar questão
     */
    public function show(EbdQuestao $questao)
    {
        $questao->load(['avaliacao', 'respostasAlunos']);
        
        return view('admin.ebd.questoes.show', compact('questao'));
    }

    /**
     * Formulário para editar questão
     */
    public function edit(EbdQuestao $questao)
    {
        $avaliacoes = EbdAvaliacao::where('ativo', true)->get();
        $tipos = [
            'multipla_escolha' => 'Múltipla Escolha',
            'verdadeiro_falso' => 'Verdadeiro/Falso',
            'dissertativa' => 'Dissertativa',
            'completar' => 'Completar',
            'associacao' => 'Associação'
        ];

        return view('admin.ebd.questoes.edit', compact('questao', 'avaliacoes', 'tipos'));
    }

    /**
     * Atualizar questão
     */
    public function update(Request $request, EbdQuestao $questao)
    {
        $request->validate([
            'avaliacao_id' => 'required|exists:ebd_avaliacoes,id',
            'pergunta' => 'required|string|max:1000',
            'tipo' => 'required|in:multipla_escolha,verdadeiro_falso,dissertativa,completar,associacao',
            'opcoes' => 'nullable|array',
            'resposta_correta' => 'nullable|string|max:500',
            'pontuacao' => 'required|integer|min:1|max:100',
            'explicacao' => 'nullable|string|max:1000',
            'dificuldade' => 'required|in:facil,medio,dificil',
            'ativo' => 'boolean'
        ]);

        // Processar opções se for múltipla escolha
        if ($request->tipo === 'multipla_escolha' && $request->opcoes) {
            $opcoes = array_filter($request->opcoes, function($opcao) {
                return !empty(trim($opcao));
            });
            $request->merge(['opcoes' => $opcoes]);
        }

        $questao->update($request->all());

        return redirect()->route('admin.ebd.questoes.index')
            ->with('success', 'Questão atualizada com sucesso!');
    }

    /**
     * Excluir questão
     */
    public function destroy(EbdQuestao $questao)
    {
        // Verificar se há respostas de alunos
        if ($questao->respostasAlunos()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir uma questão que já possui respostas de alunos.');
        }

        $questao->delete();

        return redirect()->route('admin.ebd.questoes.index')
            ->with('success', 'Questão excluída com sucesso!');
    }

    /**
     * Importar questões de um arquivo
     */
    public function import(Request $request)
    {
        $request->validate([
            'avaliacao_id' => 'required|exists:ebd_avaliacoes,id',
            'arquivo' => 'required|file|mimes:csv,xlsx,xls|max:2048'
        ]);

        try {
            $arquivo = $request->file('arquivo');
            $avaliacao = EbdAvaliacao::findOrFail($request->avaliacao_id);

            // Processar arquivo baseado na extensão
            $extensao = $arquivo->getClientOriginalExtension();
            
            if ($extensao === 'csv') {
                $this->importarCSV($arquivo, $avaliacao);
            } else {
                $this->importarExcel($arquivo, $avaliacao);
            }

            return redirect()->route('admin.ebd.questoes.index')
                ->with('success', 'Questões importadas com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao importar questões: ' . $e->getMessage());
        }
    }

    /**
     * Exportar questões
     */
    public function export(Request $request)
    {
        $request->validate([
            'avaliacao_id' => 'required|exists:ebd_avaliacoes,id',
            'formato' => 'required|in:csv,xlsx,pdf'
        ]);

        $avaliacao = EbdAvaliacao::with('questoes')->findOrFail($request->avaliacao_id);
        $questoes = $avaliacao->questoes;

        if ($request->formato === 'csv') {
            return $this->exportarCSV($questoes, $avaliacao);
        } elseif ($request->formato === 'xlsx') {
            return $this->exportarExcel($questoes, $avaliacao);
        } else {
            return $this->exportarPDF($questoes, $avaliacao);
        }
    }

    /**
     * Importar CSV
     */
    private function importarCSV($arquivo, $avaliacao)
    {
        $handle = fopen($arquivo->getPathname(), 'r');
        $headers = fgetcsv($handle);
        
        while (($data = fgetcsv($handle)) !== false) {
            $row = array_combine($headers, $data);
            
            EbdQuestao::create([
                'avaliacao_id' => $avaliacao->id,
                'pergunta' => $row['pergunta'] ?? '',
                'tipo' => $row['tipo'] ?? 'multipla_escolha',
                'opcoes' => isset($row['opcoes']) ? json_decode($row['opcoes'], true) : null,
                'resposta_correta' => $row['resposta_correta'] ?? null,
                'pontuacao' => $row['pontuacao'] ?? 10,
                'explicacao' => $row['explicacao'] ?? null,
                'dificuldade' => $row['dificuldade'] ?? 'medio',
                'ativo' => true
            ]);
        }
        
        fclose($handle);
    }

    /**
     * Importar Excel
     */
    private function importarExcel($arquivo, $avaliacao)
    {
        // Implementar importação Excel se necessário
        throw new \Exception('Importação Excel ainda não implementada');
    }

    /**
     * Exportar CSV
     */
    private function exportarCSV($questoes, $avaliacao)
    {
        $filename = "questoes_avaliacao_{$avaliacao->id}_" . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($questoes) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalhos
            fputcsv($file, ['ID', 'Pergunta', 'Tipo', 'Opções', 'Resposta Correta', 'Pontuação', 'Explicação', 'Dificuldade']);
            
            // Dados
            foreach ($questoes as $questao) {
                fputcsv($file, [
                    $questao->id,
                    $questao->pergunta,
                    $questao->tipo,
                    $questao->opcoes ? json_encode($questao->opcoes) : '',
                    $questao->resposta_correta,
                    $questao->pontuacao,
                    $questao->explicacao,
                    $questao->dificuldade
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar Excel
     */
    private function exportarExcel($questoes, $avaliacao)
    {
        // Implementar exportação Excel se necessário
        throw new \Exception('Exportação Excel ainda não implementada');
    }

    /**
     * Exportar PDF
     */
    private function exportarPDF($questoes, $avaliacao)
    {
        // Implementar exportação PDF se necessário
        throw new \Exception('Exportação PDF ainda não implementada');
    }
} 