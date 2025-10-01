<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\EbdService;
use App\Models\EbdAluno;
use Illuminate\Support\Facades\Auth;

class EbdAlunoDashboardController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->ebdService = $ebdService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // A lógica para encontrar o aluno pode ser movida para o serviço no futuro
        $aluno = EbdAluno::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->first();

        if (!$aluno) {
            return view('member.ebd.dashboard.nao-matriculado');
        }

        // Utiliza o serviço para buscar os dados da nova estrutura pedagógica
        $disciplinas = $this->ebdService->getAllDisciplinas(); // Simplificado: idealmente, buscaria apenas as disciplinas do aluno
        $progressoAluno = $this->ebdService->getProgressoAluno($aluno);

        return view('member.ebd.dashboard.index', compact('aluno', 'disciplinas', 'progressoAluno'));
    }
}