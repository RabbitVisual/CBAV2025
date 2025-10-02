<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:members.access');
    }

    /**
     * Exibe a lista de usuários (membros).
     */
    public function index(Request $request)
    {
        $query = User::with(['profile', 'roles', 'cargos']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('profile', fn($p) => $p->where('telefone', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('ativo', $request->status === 'ativo');
        }

        $users = $query->paginate(15);

        return view('admin.people.members.index', compact('users'));
    }

    /**
     * Mostra o formulário para criar um novo usuário/membro.
     */
    public function create()
    {
        $cargos = Cargo::all();
        return view('admin.people.members.create', compact('cargos'));
    }

    /**
     * Armazena um novo usuário e seu perfil.
     */
    public function store(Request $request)
    {
        $userData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'ativo' => 'boolean',
        ]);
        $profileData = $request->validate([
            'telefone' => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'endereco' => 'nullable|string|max:255',
            // Adicionar outras validações de perfil aqui
        ]);

        $user = User::create($userData);
        $user->profile()->create($profileData);

        if ($request->filled('cargo_id')) {
            $user->cargos()->attach($request->cargo_id);
        }

        return redirect()->route('admin.members.index')->with('success', 'Membro criado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um membro.
     */
    public function edit(User $member) // Type-hinting para User
    {
        $member->load('profile', 'cargos');
        $cargos = Cargo::all();
        return view('admin.people.members.edit', ['membro' => $member, 'cargos' => $cargos]);
    }

    /**
     * Atualiza um usuário e seu perfil.
     */
    public function update(Request $request, User $member)
    {
        $userData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $member->id,
            'ativo' => 'boolean',
        ]);
        $profileData = $request->validate([
            'telefone' => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'endereco' => 'nullable|string|max:255',
        ]);

        $member->update($userData);
        $member->profile()->updateOrCreate(['user_id' => $member->id], $profileData);

        if ($request->filled('cargo_id')) {
            $member->cargos()->sync($request->cargo_id);
        }

        return redirect()->route('admin.members.index')->with('success', 'Membro atualizado com sucesso!');
    }

    /**
     * Exibe o perfil de um membro.
     */
    public function show(User $member)
    {
        $member->load('profile', 'cargos');
        return view('admin.people.members.show', ['membro' => $member]);
    }

    /**
     * Remove um membro (usuário).
     */
    public function destroy(User $member)
    {
        // A lógica no model User cuidará de deletar o profile associado
        $member->delete();
        return redirect()->route('admin.members.index')->with('success', 'Membro excluído com sucesso!');
    }
}