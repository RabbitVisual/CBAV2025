<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdAluno;
use App\Models\EbdTurma;
use App\Models\Membro;

class EbdAlunoController extends Controller
{
    public function index()
    {
        $alunos = EbdAluno::with(['membro', 'turma'])->orderBy('nome')->get();
        return view('admin.ebd.alunos.index', compact('alunos'));
    }

    public function create()
    {
        $membros = Membro::orderBy('nome')->get();
        $turmas = EbdTurma::ativas()->orderBy('nome')->get();
        return view('admin.ebd.alunos.create', compact('membros', 'turmas'));
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
        EbdAluno::create($data);
        return redirect()->route('admin.ebd.alunos.index')->with('success', 'Aluno matriculado com sucesso!');
    }

    public function show(EbdAluno $aluno)
    {
        return view('admin.ebd.alunos.show', compact('aluno'));
    }

    public function edit(EbdAluno $aluno)
    {
        $membros = Membro::orderBy('nome')->get();
        $turmas = EbdTurma::ativas()->orderBy('nome')->get();
        return view('admin.ebd.alunos.edit', compact('aluno', 'membros', 'turmas'));
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
        $aluno->update($data);
        return redirect()->route('admin.ebd.alunos.index')->with('success', 'Aluno atualizado com sucesso!');
    }

    public function destroy(EbdAluno $aluno)
    {
        $aluno->delete();
        return redirect()->route('admin.ebd.alunos.index')->with('success', 'Aluno removido com sucesso!');
    }
} 