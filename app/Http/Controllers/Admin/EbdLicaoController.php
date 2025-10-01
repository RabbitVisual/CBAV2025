<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdLicao;
use App\Services\EbdService;

class EbdLicaoController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->ebdService = $ebdService;
    }

    public function index()
    {
        $licoes = $this->ebdService->getAllLicoes();
        return view('admin.ebd.licoes.index', compact('licoes'));
    }

    public function create()
    {
        return view('admin.ebd.licoes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:200',
            'descricao' => 'nullable|string',
            'objetivos' => 'nullable|string',
            'versiculo_chave' => 'nullable|string',
            'conteudo' => 'required|string',
            'aplicacao_pratica' => 'nullable|string',
            'oracao' => 'nullable|string',
            'material_necessario' => 'nullable|string',
            'duracao_minutos' => 'required|integer|min:15',
            'dificuldade' => 'required|in:facil,medio,dificil',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');

        $this->ebdService->createLicao($data);

        return redirect()->route('admin.ebd.licoes.index')->with('success', 'Lição criada com sucesso!');
    }

    public function show(EbdLicao $licao)
    {
        return view('admin.ebd.licoes.show', compact('licao'));
    }

    public function edit(EbdLicao $licao)
    {
        return view('admin.ebd.licoes.edit', compact('licao'));
    }

    public function update(Request $request, EbdLicao $licao)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:200',
            'descricao' => 'nullable|string',
            'objetivos' => 'nullable|string',
            'versiculo_chave' => 'nullable|string',
            'conteudo' => 'required|string',
            'aplicacao_pratica' => 'nullable|string',
            'oracao' => 'nullable|string',
            'material_necessario' => 'nullable|string',
            'duracao_minutos' => 'required|integer|min:15',
            'dificuldade' => 'required|in:facil,medio,dificil',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');

        $this->ebdService->updateLicao($licao, $data);

        return redirect()->route('admin.ebd.licoes.index')->with('success', 'Lição atualizada com sucesso!');
    }

    public function destroy(EbdLicao $licao)
    {
        $this->ebdService->deleteLicao($licao);

        return redirect()->route('admin.ebd.licoes.index')->with('success', 'Lição removida com sucesso!');
    }
}