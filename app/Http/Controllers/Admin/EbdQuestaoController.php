<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdQuestao;
use App\Services\EbdService;

class EbdQuestaoController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->ebdService = $ebdService;
        // Adicionar middleware de permissão, se necessário
        // $this->middleware('permission:ebd.access');
    }

    public function index(Request $request)
    {
        $data = $this->ebdService->getQuestoes($request);
        return view('admin.ebd.questoes.index', $data);
    }

    public function create(Request $request)
    {
        $data = $this->ebdService->getQuestaoFormData($request);
        return view('admin.ebd.questoes.create', $data);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
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

        $this->ebdService->createQuestao($validatedData);

        return redirect()->route('admin.ebd.questoes.index')
            ->with('success', 'Questão criada com sucesso!');
    }

    public function show(EbdQuestao $questao)
    {
        $questao->load(['avaliacao', 'respostasAlunos']);
        return view('admin.ebd.questoes.show', compact('questao'));
    }

    public function edit(Request $request, EbdQuestao $questao)
    {
        $data = $this->ebdService->getQuestaoFormData($request);
        return view('admin.ebd.questoes.edit', array_merge($data, compact('questao')));
    }

    public function update(Request $request, EbdQuestao $questao)
    {
        $validatedData = $request->validate([
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

        $this->ebdService->updateQuestao($questao, $validatedData);

        return redirect()->route('admin.ebd.questoes.index')
            ->with('success', 'Questão atualizada com sucesso!');
    }

    public function destroy(EbdQuestao $questao)
    {
        try {
            $this->ebdService->deleteQuestao($questao);
            return redirect()->route('admin.ebd.questoes.index')
                ->with('success', 'Questão excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    // A lógica de importação/exportação será adicionada aqui, se necessário,
    // chamando os respectivos métodos do EbdService.
}