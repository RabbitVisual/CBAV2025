<?php

namespace App\Http\Controllers\Member\EBD;

use App\Http\Controllers\Controller;
use App\Models\EBD\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\EbdService;

class TurmaController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->middleware('auth');
        $this->ebdService = $ebdService;
    }

    /**
     * Display a listing of the turmas the user is part of.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        // The service would ideally handle this logic
        $turmas = $user->turmasComoAluno()->withCount('alunos')->get();

        return view('member.ebd.turmas.index', compact('turmas'));
    }

    /**
     * Display the specified turma.
     * The user must be a member of the turma to view it.
     *
     * @param  \App\Models\EBD\Turma  $turma
     * @return \Illuminate\View\View
     */
    public function show(Turma $turma)
    {
        $user = Auth::user();

        // Authorization: Ensure the user is part of this turma
        // This logic should ideally be in a FormRequest or in the EbdService
        $isMember = $turma->alunos()->where('user_id', $user->id)->exists() ||
                    $turma->professores()->where('user_id', $user->id)->exists();

        if (!$isMember) {
            abort(403, 'Você não tem permissão para acessar esta turma.');
        }

        $turma->load(['alunos', 'professores', 'grupos']);

        return view('member.ebd.turmas.show', compact('turma'));
    }
}