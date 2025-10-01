<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdAluno;
use App\Services\EbdService;

class EbdAlunoController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->ebdService = $ebdService;
    }

    public function index()
    {
        $alunos = $this->ebdService->getAllAlunos();
        return view('admin.ebd.alunos.index', compact('alunos'));
    }

    public function create()
    {
        $data = $this->ebdService->getAlunoFormData();
        return view('admin.ebd.alunos.create', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'membro_id' => 'nullable|exists:membros,id',
            'nome' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'telefone' => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'nome_responsavel' => 'nullable|string|max:100',
            'telefone_responsavel' => 'nullable|string|max:20',
            'turma_id' => 'required|exists:ebd_turmas,id',
            'data_matricula' => 'required|date',
            'status' => 'required|in:ativo,inativo,transferido',
            'observacoes' => 'nullable|string',
        ]);

        $this->ebdService->createAluno($data);

        return redirect()->route('admin.ebd.alunos.index')->with('success', 'Aluno matriculado com sucesso!');
    }

    public function show(EbdAluno $aluno)
    {
        return view('admin.ebd.alunos.show', compact('aluno'));
    }

    public function edit(EbdAluno $aluno)
    {
        $data = $this->ebdService->getAlunoFormData();
        return view('admin.ebd.alunos.edit', array_merge($data, compact('aluno')));
    }

    public function update(Request $request, EbdAluno $aluno)
    {
        $data = $request->validate([
            'membro_id' => 'nullable|exists:membros,id',
            'nome' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'telefone' => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'nome_responsavel' => 'nullable|string|max:100',
            'telefone_responsavel' => 'nullable|string|max:20',
            'turma_id' => 'required|exists:ebd_turmas,id',
            'data_matricula' => 'required|date',
            'status' => 'required|in:ativo,inativo,transferido',
            'observacoes' => 'nullable|string',
        ]);

        $this->ebdService->updateAluno($aluno, $data);

        return redirect()->route('admin.ebd.alunos.index')->with('success', 'Aluno atualizado com sucesso!');
    }

    public function destroy(EbdAluno $aluno)
    {
        $this->ebdService->deleteAluno($aluno);

        return redirect()->route('admin.ebd.alunos.index')->with('success', 'Aluno removido com sucesso!');
    }
}