<?php

namespace App\Http\Controllers\Admin\EBD;

use App\Http\Controllers\Controller;
use App\Models\EBD\Grupo;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\EbdService;

class GrupoUserController extends Controller
{
    protected $ebdService;

    public function __construct(EbdService $ebdService)
    {
        $this->middleware('permission:ebd.access');
        $this->ebdService = $ebdService;
    }

    /**
     * Add a user as a member to a Grupo.
     */
    public function store(Request $request, Grupo $grupo)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->user_id);

        // Here you would call the service method
        // Ex: $this->ebdService->addUserToGrupo($grupo, $user);

        // Basic attachment logic
        if ($grupo->membros()->where('user_id', $user->id)->doesntExist()) {
            $grupo->membros()->attach($user->id);
            return back()->with('success', 'Membro adicionado ao grupo com sucesso.');
        }

        return back()->with('info', 'Este usuário já é membro do grupo.');
    }

    /**
     * Remove a user from a Grupo.
     */
    public function destroy(Grupo $grupo, User $user)
    {
        // Here you would call the service method
        // Ex: $this->ebdService->removeUserFromGrupo($grupo, $user);

        $grupo->membros()->detach($user->id);

        // If the removed user was the leader, nullify the leader_id
        if ($grupo->lider_id === $user->id) {
            $grupo->update(['lider_id' => null]);
        }

        return back()->with('success', 'Membro removido do grupo com sucesso.');
    }
}