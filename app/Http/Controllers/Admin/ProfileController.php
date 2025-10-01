<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin.access');
    }

    /**
     * Exibir perfil do administrador
     */
    public function index()
    {
        $user = auth()->user();
        $membro = $user->membro;
        
        return view('admin.profile.index', compact('user', 'membro'));
    }

    /**
     * Editar perfil do administrador
     */
    public function edit()
    {
        $user = auth()->user();
        $membro = $user->membro;
        
        return view('admin.profile.edit', compact('user', 'membro'));
    }

    /**
     * Atualizar perfil do administrador
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:500',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload da foto
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoPath = $foto->store('users/fotos', 'public');
            
            // Remover foto antiga do usuário se existir
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            
            // Salvar foto no usuário
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'foto' => $fotoPath,
            ]);
        } else {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }

        // Atualizar dados do membro se existir
        if ($user->membro) {
            $membroData = [
                'telefone' => $request->telefone,
                'endereco' => $request->endereco,
            ];
            
            // Se foi feito upload de foto, salvar também no membro
            if ($request->hasFile('foto')) {
                // Remover foto antiga do membro se existir
                if ($user->membro->foto && Storage::disk('public')->exists($user->membro->foto)) {
                    Storage::disk('public')->delete($user->membro->foto);
                }
                
                $membroData['foto'] = $fotoPath;
            }
            
            $user->membro->update($membroData);
        }

        return redirect()->route('admin.profile.index')
            ->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Alterar senha
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'A senha atual está incorreta.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Senha alterada com sucesso!');
    }

    /**
     * Remover foto do perfil
     */
    public function deletePhoto()
    {
        $user = auth()->user();
        
        // Remover foto do usuário
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
            $user->update(['foto' => null]);
        }
        
        // Remover foto do membro se existir
        if ($user->membro && $user->membro->foto) {
            if (Storage::disk('public')->exists($user->membro->foto)) {
                Storage::disk('public')->delete($user->membro->foto);
            }
            $user->membro->update(['foto' => null]);
        }

        return redirect()->route('admin.profile.index')
            ->with('success', 'Foto removida com sucesso!');
    }
} 