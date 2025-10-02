<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Services\ProfileService;
use App\Models\User;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
        $this->middleware('auth');
    }

    /**
     * Exibe o perfil do membro.
     */
    public function index()
    {
        return view('member.profile.index', ['user' => Auth::user()->load('profile')]);
    }

    /**
     * Mostra o formulário para editar o perfil do membro.
     */
    public function edit()
    {
        return view('member.profile.edit', ['user' => Auth::user()->load('profile')]);
    }

    /**
     * Atualiza o perfil do membro.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $this->profileService->updateProfile($user, $data, $request->file('foto'));
            return redirect()->route('member.profile.index')->with('success', 'Perfil atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar o perfil: ' . $e->getMessage());
        }
    }

    /**
     * Exibe o formulário de alteração de senha.
     */
    public function changePasswordForm()
    {
        return view('member.profile.password');
    }

    /**
     * Altera a senha do membro.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $this->profileService->changePassword(
                Auth::user(),
                $request->current_password,
                $request->password
            );
            return redirect()->route('member.profile.index')->with('success', 'Senha alterada com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Remove a foto de perfil do membro.
     */
    public function deletePhoto()
    {
        $this->profileService->deletePhoto(Auth::user());
        return back()->with('success', 'Foto removida com sucesso!');
    }
}