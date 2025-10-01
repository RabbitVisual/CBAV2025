<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EbdAluno;
use App\Models\EbdAula;
use App\Models\EbdAvaliacao;

class EbdAlunoDashboardController extends Controller
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
            return view('member.ebd.dashboard.nao-matriculado');
        }

        $proximasAulas = EbdAula::where('turma_id', $aluno->turma_id)
                                ->where('data_aula', '>=', now()->toDateString())
                                ->where('status', 'agendada')
                                ->with(['licao', 'professor.membro'])
                                ->orderBy('data_aula')
                                ->limit(5)
                                ->get();

        $ultimasAulas = EbdAula::where('turma_id', $aluno->turma_id)
                               ->where('data_aula', '<', now()->toDateString())
                               ->where('status', 'realizada')
                               ->with(['licao', 'professor.membro'])
                               ->orderBy('data_aula', 'desc')
                               ->limit(5)
                               ->get();

        $avaliacoesPendentes = EbdAvaliacao::whereHas('aula', function($query) use ($aluno) {
                                    $query->where('turma_id', $aluno->turma_id);
                                })
                                ->whereDoesntHave('notas', function($query) use ($aluno) {
                                    $query->where('aluno_id', $aluno->id);
                                })
                                ->with(['aula.licao'])
                                ->get();

        $estatisticas = [
            'total_presencas' => $aluno->total_presencas,
            'total_ausencias' => $aluno->total_ausencias,
            'percentual_presenca' => $aluno->percentual_presenca,
            'media_geral' => $aluno->media_geral,
        ];

        return view('member.ebd.dashboard.index', compact('aluno', 'proximasAulas', 'ultimasAulas', 'avaliacoesPendentes', 'estatisticas'));
    }
} 