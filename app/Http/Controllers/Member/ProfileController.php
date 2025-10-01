<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Storage};
use App\Models\Membro;

class ProfileController extends Controller
{
    /**
     * Exibir perfil do membro
     */
    public function index()
    {
        $user = Auth::user();
        $membro = $user->membro;

        // Se não tem membro associado, criar um perfil básico
        if (!$membro) {
            $membro = $this->createBasicMemberProfile($user);
            
            if (!$membro) {
                return redirect()->route('member.dashboard')
                    ->with('error', 'Erro ao criar perfil de membro.');
            }
        }

        return view('member.profile.index', compact('membro', 'user'));
    }

    /**
     * Exibir formulário de edição do perfil
     */
    public function edit()
    {
        $user = Auth::user();
        $membro = $user->membro;

        // Se não tem membro associado, criar um perfil básico
        if (!$membro) {
            $membro = $this->createBasicMemberProfile($user);
            
            if (!$membro) {
                return redirect()->route('member.dashboard')
                    ->with('error', 'Erro ao criar perfil de membro.');
            }
        }

        return view('member.profile.edit', compact('membro', 'user'));
    }

    /**
     * Atualizar perfil do membro
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $membro = $user->membro;

        if (!$membro) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Perfil de membro não encontrado.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
            'data_nascimento' => 'nullable|date',
            'estado_civil' => 'nullable|string|in:solteiro,casado,divorciado,viuvo',
            'data_batismo' => 'nullable|date',
            'observacoes' => 'nullable|string|max:1000',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
            'email_notifications' => 'nullable|boolean',
            'push_notifications' => 'nullable|boolean',
            'public_profile' => 'nullable|boolean',
        ]);

        try {
            // Atualizar dados do usuário (apenas campos permitidos)
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            // Processar configurações de notificação
            $userData['email_notifications'] = $request->has('email_notifications');
            $userData['push_notifications'] = $request->has('push_notifications');
            $userData['public_profile'] = $request->has('public_profile');

            $user->update($userData);

            // Atualizar dados do membro (apenas campos permitidos)
            $membroData = $request->only([
                'telefone', 'endereco', 'cidade', 'estado', 'cep',
                'data_nascimento', 'estado_civil', 'data_batismo', 'observacoes'
            ]);

            // Processar foto se fornecida
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                
                // Validar tipo de arquivo
                $extensao = strtolower($foto->getClientOriginalExtension());
                if (!in_array($extensao, ['jpg', 'jpeg', 'png', 'webp'])) {
                    return redirect()->back()
                        ->with('error', 'Formato de imagem não suportado. Use JPG, PNG ou WebP.')
                        ->withInput();
                }

                // Gerar nome único para o arquivo
                $nomeArquivo = 'foto_' . $user->id . '_' . time() . '_' . uniqid() . '.' . $extensao;
                
                // Salvar nova foto
                $caminho = $foto->storeAs('users/fotos', $nomeArquivo, 'public');
                $membroData['foto'] = $caminho;
                
                // Excluir foto antiga se existir
                if ($membro->foto && Storage::disk('public')->exists($membro->foto)) {
                    Storage::disk('public')->delete($membro->foto);
                }
            }

            $membro->update($membroData);

            return redirect()->route('member.profile.index')
                ->with('success', 'Perfil atualizado com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar perfil: ' . $e->getMessage())
                ->withInput();
        }
    }



    /**
     * Excluir foto do perfil
     */
    public function deletePhoto()
    {
        $user = Auth::user();
        $membro = $user->membro;

        if (!$membro || !$membro->foto) {
            return response()->json(['error' => 'Nenhuma foto encontrada para excluir.'], 404);
        }

        try {
            // Excluir arquivo físico
            if (Storage::disk('public')->exists($membro->foto)) {
                Storage::disk('public')->delete($membro->foto);
            }

            // Limpar referência no banco
            $membro->update(['foto' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Foto excluída com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao excluir foto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Upload de foto via AJAX
     */
    public function uploadPhoto(Request $request)
    {
        $user = Auth::user();
        $membro = $user->membro;

        if (!$membro) {
            return response()->json(['error' => 'Perfil de membro não encontrado.'], 404);
        }

        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        try {
            $foto = $request->file('foto');
            
            // Validar tipo de arquivo
            $extensao = strtolower($foto->getClientOriginalExtension());
            if (!in_array($extensao, ['jpg', 'jpeg', 'png', 'webp'])) {
                return response()->json(['error' => 'Formato de imagem não suportado. Use JPG, PNG ou WebP.'], 400);
            }

            // Gerar nome único para o arquivo
            $nomeArquivo = 'foto_' . $user->id . '_' . time() . '_' . uniqid() . '.' . $extensao;
            
            // Salvar nova foto
            $caminho = $foto->storeAs('users/fotos', $nomeArquivo, 'public');
            
            // Excluir foto antiga se existir
            if ($membro->foto && Storage::disk('public')->exists($membro->foto)) {
                Storage::disk('public')->delete($membro->foto);
            }

            // Atualizar no banco
            $membro->update(['foto' => $caminho]);

            return response()->json([
                'success' => true,
                'message' => 'Foto atualizada com sucesso!',
                'foto_url' => Storage::disk('public')->url($caminho),
                'foto_path' => $caminho
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao fazer upload da foto: ' . $e->getMessage()], 500);
        }
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

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Senha atual incorreta.');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('member.profile.index')
            ->with('success', 'Senha alterada com sucesso!');
    }

    /**
     * Exibir formulário de alteração de senha
     */
    public function changePasswordForm()
    {
        return view('member.profile.password');
    }

    /**
     * Exibir formulário de foto do perfil
     */
    public function photoForm()
    {
        $user = Auth::user();
        $membro = $user->membro;

        if (!$membro) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Perfil de membro não encontrado.');
        }

        return view('member.profile.photo', compact('membro', 'user'));
    }

    /**
     * Criar perfil básico de membro para usuário
     */
    private function createBasicMemberProfile($user)
    {
        try {
            // Verificar se já existe um membro com este email
            $membro = Membro::where('email', $user->email)->first();
            
            if (!$membro) {
                // Criar membro básico
                $membro = Membro::create([
                    'nome' => $user->name,
                    'email' => $user->email,
                    'data_nascimento' => now()->subYears(25), // Data padrão
                    'telefone' => '',
                    'endereco' => '',
                    'cidade' => '',
                    'estado' => '',
                    'cep' => '',
                    'data_batismo' => null,
                    'ativo' => true,
                    'data_ingresso' => now(),
                    'observacoes' => 'Perfil criado automaticamente pelo sistema.',
                ]);
            }
            
            return $membro;
        } catch (\Exception $e) {
            \Log::error('Erro ao criar perfil de membro: ' . $e->getMessage());
            return null;
        }
    }
}