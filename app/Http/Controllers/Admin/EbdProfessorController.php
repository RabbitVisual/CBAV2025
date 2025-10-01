<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdProfessor;
use App\Services\EbdService;

class EbdProfessorController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->ebdService = $ebdService;
    }

    public function index()
    {
        $professores = $this->ebdService->getAllProfessores();
        return view('admin.ebd.professores.index', compact('professores'));
    }

    public function create()
    {
        $data = $this->ebdService->getProfessorFormData();
        return view('admin.ebd.professores.create', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'membro_id' => 'nullable|exists:membros,id',
            'turma_id' => 'required|exists:ebd_turmas,id',
            'tipo' => 'required|in:principal,auxiliar',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after:data_inicio',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');

        $this->ebdService->createProfessor($data);

        return redirect()->route('admin.ebd.professores.index')->with('success', 'Professor cadastrado com sucesso!');
    }

    public function show(EbdProfessor $professor)
    {
        return view('admin.ebd.professores.show', compact('professor'));
    }

    public function edit(EbdProfessor $professor)
    {
        $data = $this->ebdService->getProfessorFormData();
        return view('admin.ebd.professores.edit', array_merge($data, compact('professor')));
    }

    public function update(Request $request, EbdProfessor $professor)
    {
        $data = $request->validate([
            'membro_id' => 'nullable|exists:membros,id',
            'turma_id' => 'required|exists:ebd_turmas,id',
            'tipo' => 'required|in:principal,auxiliar',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after:data_inicio',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');

        $this->ebdService->updateProfessor($professor, $data);

        return redirect()->route('admin.ebd.professores.index')->with('success', 'Professor atualizado com sucesso!');
    }

    public function destroy(EbdProfessor $professor)
    {
        $this->ebdService->deleteProfessor($professor);

        return redirect()->route('admin.ebd.professores.index')->with('success', 'Professor removido com sucesso!');
    }
}