<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdAula;

class EbdAulaController extends Controller
{
    public function index()
    {
        $aulas = EbdAula::with(['turma', 'licao', 'professor.membro'])
            ->orderBy('data_aula', 'desc')
            ->get();
        return view('member.ebd.aulas.index', compact('aulas'));
    }

    public function show(EbdAula $aula)
    {
        $aula->load(['turma', 'licao', 'professor.membro', 'presencas.aluno']);
        return view('member.ebd.aulas.show', compact('aula'));
    }
} 