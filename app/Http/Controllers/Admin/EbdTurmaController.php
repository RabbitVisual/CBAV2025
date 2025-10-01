<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdTurma;

class EbdTurmaController extends Controller
{
    public function index()
    {
        $turmas = EbdTurma::orderBy('nome')->get();
        return view('admin.ebd.turmas.index', compact('turmas'));
    }

    public function create()
    {
        return view('admin.ebd.turmas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string',
            'faixa_etaria' => 'nullable|string|max:50',
            'cor' => 'nullable|string|max:20',
            'capacidade_maxima' => 'nullable|integer|min:1',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');
        EbdTurma::create($data);
        return redirect()->route('admin.ebd.turmas.index')->with('success', 'Turma criada com sucesso!');
    }

    public function show(EbdTurma $turma)
    {
        return view('admin.ebd.turmas.show', compact('turma'));
    }

    public function edit(EbdTurma $turma)
    {
        return view('admin.ebd.turmas.edit', compact('turma'));
    }

    public function update(Request $request, EbdTurma $turma)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string',
            'faixa_etaria' => 'nullable|string|max:50',
            'cor' => 'nullable|string|max:20',
            'capacidade_maxima' => 'nullable|integer|min:1',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');
        $turma->update($data);
        return redirect()->route('admin.ebd.turmas.index')->with('success', 'Turma atualizada com sucesso!');
    }

    public function destroy(EbdTurma $turma)
    {
        $turma->delete();
        return redirect()->route('admin.ebd.turmas.index')->with('success', 'Turma removida com sucesso!');
    }
} 