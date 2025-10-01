<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdAula;
use App\Models\EbdTurma;
use App\Models\EbdLicao;
use App\Models\EbdProfessor;

class EbdAulaController extends Controller
{
    public function index()
    {
        $aulas = EbdAula::with(['turma', 'licao', 'professor'])->orderBy('data_aula', 'desc')->get();
        return view('admin.ebd.aulas.index', compact('aulas'));
    }

    public function create()
    {
        $turmas = EbdTurma::ativas()->orderBy('nome')->get();
        $licoes = EbdLicao::ativas()->orderBy('titulo')->get();
        $professores = EbdProfessor::ativos()->with('membro')->get();
        return view('admin.ebd.aulas.create', compact('turmas', 'licoes', 'professores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'turma_id' => 'required|exists:ebd_turmas,id',
            'licao_id' => 'required|exists:ebd_licoes,id',
            'professor_id' => 'nullable|exists:ebd_professores,id',
            'data_aula' => 'required|date',
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fim' => 'required|date_format:H:i|after:horario_inicio',
            'observacoes' => 'nullable|string',
            'status' => 'required|in:agendada,realizada,cancelada,adiada',
        ]);
        $data['horario_inicio'] = $data['data_aula'] . ' ' . $data['horario_inicio'];
        $data['horario_fim'] = $data['data_aula'] . ' ' . $data['horario_fim'];
        EbdAula::create($data);
        return redirect()->route('admin.ebd.aulas.index')->with('success', 'Aula agendada com sucesso!');
    }

    public function show(EbdAula $aula)
    {
        return view('admin.ebd.aulas.show', compact('aula'));
    }

    public function edit(EbdAula $aula)
    {
        $turmas = EbdTurma::ativas()->orderBy('nome')->get();
        $licoes = EbdLicao::ativas()->orderBy('titulo')->get();
        $professores = EbdProfessor::ativos()->with('membro')->get();
        return view('admin.ebd.aulas.edit', compact('aula', 'turmas', 'licoes', 'professores'));
    }

    public function update(Request $request, EbdAula $aula)
    {
        $data = $request->validate([
            'turma_id' => 'required|exists:ebd_turmas,id',
            'licao_id' => 'required|exists:ebd_licoes,id',
            'professor_id' => 'nullable|exists:ebd_professores,id',
            'data_aula' => 'required|date',
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fim' => 'required|date_format:H:i|after:horario_inicio',
            'observacoes' => 'nullable|string',
            'status' => 'required|in:agendada,realizada,cancelada,adiada',
        ]);
        $data['horario_inicio'] = $data['data_aula'] . ' ' . $data['horario_inicio'];
        $data['horario_fim'] = $data['data_aula'] . ' ' . $data['horario_fim'];
        $aula->update($data);
        return redirect()->route('admin.ebd.aulas.index')->with('success', 'Aula atualizada com sucesso!');
    }

    public function destroy(EbdAula $aula)
    {
        $aula->delete();
        return redirect()->route('admin.ebd.aulas.index')->with('success', 'Aula removida com sucesso!');
    }
} 