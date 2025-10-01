<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdAvaliacao;
use App\Services\EbdService;

class EbdAvaliacaoController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->ebdService = $ebdService;
    }

    public function index()
    {
        $avaliacoes = $this->ebdService->getAllAvaliacoes();
        return view('admin.ebd.avaliacoes.index', compact('avaliacoes'));
    }

    public function create()
    {
        $data = $this->ebdService->getAvaliacaoFormData();
        return view('admin.ebd.avaliacoes.create', $data);
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

        $this->ebdService->createAvaliacao($request->all());

        return redirect()->route('admin.ebd.avaliacoes.index')->with('success', 'Avaliação criada com sucesso!');
    }

    public function show(EbdAvaliacao $avaliacao)
    {
        return view('admin.ebd.avaliacoes.show', compact('avaliacao'));
    }

    public function edit(EbdAvaliacao $avaliacao)
    {
        $data = $this->ebdService->getAvaliacaoFormData();
        return view('admin.ebd.avaliacoes.edit', array_merge($data, compact('avaliacao')));
    }

    public function update(Request $request, EbdAvaliacao $avaliacao)
    {
        $request->validate([
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

        $this->ebdService->updateAvaliacao($avaliacao, $request->all());

        return redirect()->route('admin.ebd.avaliacoes.index')->with('success', 'Avaliação atualizada com sucesso!');
    }

    public function destroy(EbdAvaliacao $avaliacao)
    {
        $this->ebdService->deleteAvaliacao($avaliacao);

        return redirect()->route('admin.ebd.avaliacoes.index')->with('success', 'Avaliação removida com sucesso!');
    }

    public function relatorio(EbdAvaliacao $avaliacao)
    {
        $relatorioData = $this->ebdService->getAvaliacaoRelatorioData($avaliacao);
        
        return view('admin.ebd.avaliacoes.relatorio', $relatorioData);
    }
}