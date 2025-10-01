<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdLicao;

class EbdLicaoController extends Controller
{
    public function index()
    {
        $licoes = EbdLicao::ativas()->orderBy('titulo')->get();
        return view('member.ebd.licoes.index', compact('licoes'));
    }

    public function show(EbdLicao $licao)
    {
        $licao->load(['aulas.turma', 'aulas.professor.membro']);
        return view('member.ebd.licoes.show', compact('licao'));
    }
} 