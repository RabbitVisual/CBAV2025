<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdLicao;

class EbdLicaoController extends Controller
{
    public function index()
    {
        $licoes = EbdLicao::orderBy('titulo')->get();
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
        EbdLicao::create($data);
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
        $licao->update($data);
        return redirect()->route('admin.ebd.licoes.index')->with('success', 'Lição atualizada com sucesso!');
    }

    public function destroy(EbdLicao $licao)
    {
        $licao->delete();
        return redirect()->route('admin.ebd.licoes.index')->with('success', 'Lição removida com sucesso!');
    }
} 