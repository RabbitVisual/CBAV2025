<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdProfessor;
use App\Models\EbdTurma;
use App\Models\Membro;

class EbdProfessorController extends Controller
{
    public function index()
    {
        $professores = EbdProfessor::with(['membro', 'turma'])->orderBy('created_at', 'desc')->get();
        return view('admin.ebd.professores.index', compact('professores'));
    }

    public function create()
    {
        $membros = Membro::orderBy('nome')->get();
        $turmas = EbdTurma::ativas()->orderBy('nome')->get();
        return view('admin.ebd.professores.create', compact('membros', 'turmas'));
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
        EbdProfessor::create($data);
        return redirect()->route('admin.ebd.professores.index')->with('success', 'Professor cadastrado com sucesso!');
    }

    public function show(EbdProfessor $professor)
    {
        return view('admin.ebd.professores.show', compact('professor'));
    }

    public function edit(EbdProfessor $professor)
    {
        $membros = Membro::orderBy('nome')->get();
        $turmas = EbdTurma::ativas()->orderBy('nome')->get();
        return view('admin.ebd.professores.edit', compact('professor', 'membros', 'turmas'));
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
        $professor->update($data);
        return redirect()->route('admin.ebd.professores.index')->with('success', 'Professor atualizado com sucesso!');
    }

    public function destroy(EbdProfessor $professor)
    {
        $professor->delete();
        return redirect()->route('admin.ebd.professores.index')->with('success', 'Professor removido com sucesso!');
    }
} 