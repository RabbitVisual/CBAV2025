<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdCertificado;
use App\Models\EbdAluno;

class EbdCertificadoController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Tentar encontrar o aluno pelo email primeiro
        $aluno = EbdAluno::where('email', $user->email)->first();
        
        // Se não encontrar pelo email, tentar pelo membro_id
        if (!$aluno && $user->membro) {
            $aluno = EbdAluno::where('membro_id', $user->membro->id)->first();
        }
        
        // Se ainda não encontrar, tentar pelo nome do usuário
        if (!$aluno) {
            $aluno = EbdAluno::where('nome', 'like', '%' . $user->name . '%')->first();
        }
        
        if (!$aluno) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Você não está matriculado em nenhuma turma EBD.');
        }

        $certificados = EbdCertificado::where('aluno_id', $aluno->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('member.ebd.certificados.index', compact('certificados'));
    }

    public function show(EbdCertificado $certificado)
    {
        $user = auth()->user();
        
        // Tentar encontrar o aluno pelo email primeiro
        $aluno = EbdAluno::where('email', $user->email)->first();
        
        // Se não encontrar pelo email, tentar pelo membro_id
        if (!$aluno && $user->membro) {
            $aluno = EbdAluno::where('membro_id', $user->membro->id)->first();
        }
        
        // Se ainda não encontrar, tentar pelo nome do usuário
        if (!$aluno) {
            $aluno = EbdAluno::where('nome', 'like', '%' . $user->name . '%')->first();
        }
        
        if (!$aluno || $certificado->aluno_id !== $aluno->id) {
            return redirect()->route('member.ebd.certificados.index')
                ->with('error', 'Certificado não encontrado.');
        }

        return view('member.ebd.certificados.show', compact('certificado'));
    }

    public function download(EbdCertificado $certificado)
    {
        $user = auth()->user();
        
        // Tentar encontrar o aluno pelo email primeiro
        $aluno = EbdAluno::where('email', $user->email)->first();
        
        // Se não encontrar pelo email, tentar pelo membro_id
        if (!$aluno && $user->membro) {
            $aluno = EbdAluno::where('membro_id', $user->membro->id)->first();
        }
        
        // Se ainda não encontrar, tentar pelo nome do usuário
        if (!$aluno) {
            $aluno = EbdAluno::where('nome', 'like', '%' . $user->name . '%')->first();
        }
        
        if (!$aluno || $certificado->aluno_id !== $aluno->id) {
            return redirect()->route('member.ebd.certificados.index')
                ->with('error', 'Certificado não encontrado.');
        }

        // Implementar download do certificado
        return response()->json(['message' => 'Download do certificado em desenvolvimento']);
    }
} 