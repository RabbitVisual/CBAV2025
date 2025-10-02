<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Services\ProfileService;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
        $this->middleware('auth');
        $this->middleware('admin.access');
    }

    /**
     * Mostra o formulário para editar o perfil do administrador.
     */
    public function edit()
    {
        return view('admin.profile.edit', ['user' => Auth::user()]);
    }

    /**
     * Atualiza o perfil do administrador.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:500',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $this->profileService->updateProfile($user, $data, $request->file('foto'));
            return redirect()->route('admin.profile.edit')->with('success', 'Perfil atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar o perfil: ' . $e->getMessage());
        }
    }

    /**
     * Altera a senha do administrador.
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
            return redirect()->route('admin.profile.edit')->with('success', 'Senha alterada com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Remove a foto de perfil do administrador.
     */
    public function deletePhoto()
    {
        $this->profileService->deletePhoto(Auth::user());
        return redirect()->route('admin.profile.edit')->with('success', 'Foto removida com sucesso!');
    }
}