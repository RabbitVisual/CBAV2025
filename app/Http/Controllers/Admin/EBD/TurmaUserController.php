<?php

namespace App\Http\Controllers\Admin\EBD;

use App\Http\Controllers\Controller;
use App\Models\EBD\Turma;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\EbdService;

class TurmaUserController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->middleware('permission:ebd.access');
        $this->ebdService = $ebdService;
    }

    /**
     * Add a user (student or teacher) to a Turma.
     */
    public function store(Request $request, Turma $turma)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'funcao' => 'required|in:aluno,professor',
        ]);

        $user = User::find($request->user_id);

        // Here you would call the service method
        // Ex: $this->ebdService->addUserToTurma($turma, $user, $request->funcao);

        $turma->{$request->funcao . 's'}()->attach($user->id);

        return back()->with('success', ucfirst($request->funcao) . ' adicionado(a) com sucesso.');
    }

    /**
     * Remove a user from a Turma.
     */
    public function destroy(Turma $turma, User $user)
    {
        // To be more specific, you might need the role from the request
        // For now, we detach from both possibilities if needed, or adjust as per form submission
        $turma->alunos()->detach($user->id);
        $turma->professores()->detach($user->id);

        // Here you would call the service method
        // Ex: $this->ebdService->removeUserFromTurma($turma, $user);

        return back()->with('success', 'Usuário removido da turma com sucesso.');
    }
}