<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdTurma;
use App\Services\EbdService;

class EbdTurmaController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->ebdService = $ebdService;
    }

    public function index()
    {
        $turmas = $this->ebdService->getAllTurmas();
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

        $this->ebdService->createTurma($data);

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

        $this->ebdService->updateTurma($turma, $data);

        return redirect()->route('admin.ebd.turmas.index')->with('success', 'Turma atualizada com sucesso!');
    }

    public function destroy(EbdTurma $turma)
    {
        $this->ebdService->deleteTurma($turma);

        return redirect()->route('admin.ebd.turmas.index')->with('success', 'Turma removida com sucesso!');
    }
}