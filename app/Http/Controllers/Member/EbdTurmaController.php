<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdTurma;
use App\Models\EbdAluno;

class EbdTurmaController extends Controller
{
    public function index()
    {
        $turmas = EbdTurma::ativas()->with(['professores.membro', 'alunos'])->get();
        return view('member.ebd.turmas.index', compact('turmas'));
    }

    public function show(EbdTurma $turma)
    {
        $turma->load(['professores.membro', 'alunos', 'aulas.licao']);
        return view('member.ebd.turmas.show', compact('turma'));
    }
} 