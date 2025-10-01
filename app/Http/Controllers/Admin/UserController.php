<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Membro;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsuariosExport;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users.access');
    }

    /**
     * Lista de usuários
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'permissions']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'ativo') {
                $query->where('ativo', true);
            } elseif ($request->status === 'inativo') {
                $query->where('ativo', false);
            }
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('roles.id', $request->role);
            });
        }

        $users = $query->paginate(15);
        $roles = Role::all();

        return view('admin.people.users.index', compact('users', 'roles'));
    }

    /**
     * Formulário para criar novo usuário
     */
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $membros = Membro::where('ativo', true)->orderBy('nome')->get();

        return view('admin.people.users.create', compact('roles', 'permissions', 'membros'));
    }

    /**
     * Salvar novo usuário
     */
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->is_admin,
            'ativo' => $request->has('ativo'),
            'email_verified_at' => $request->email_verified ? now() : null,
        ]);

        if ($request->filled('membro_id')) {
            $membro = Membro::find($request->membro_id);
            if ($membro) {
                $membro->update(['user_id' => $user->id]);
            }
        }

        if ($request->filled('roles')) {
            $user->assignRole($request->roles);
        }

        if ($request->filled('permissions')) {
            $user->givePermissionTo($request->permissions);
        }

        if ($request->is_admin) {
            $user->assignRole('admin');
        } else {
            $user->assignRole('member');
        }

        return redirect()->route('admin.people.users.index')->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Formulário para editar usuário
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $user->load('roles', 'permissions');

        return view('admin.people.users.edit', compact('user', 'roles', 'permissions'));
    }

    /**
     * Atualizar usuário
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'ativo' => $request->has('ativo'),
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($request->filled('roles')) {
            $user->syncRoles($request->roles);
        } else {
            $user->syncRoles([]);
        }

        if ($request->filled('permissions')) {
            $user->syncPermissions($request->permissions);
        } else {
            $user->syncPermissions([]);
        }

        return redirect()->route('admin.people.users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Visualizar usuário
     */
    public function show(User $user)
    {
        return view('admin.people.users.show', compact('user'));
    }

    /**
     * Excluir usuário
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.people.users.index')->with('success', 'Usuário excluído com sucesso!');
    }

    /**
     * Exportar usuários para Excel
     */
    public function export()
    {
        return Excel::download(new UsuariosExport(), 'usuarios.xlsx');
    }

    /**
     * Ações em lote para usuários
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $request->users);

        switch ($request->action) {
            case 'activate':
                $users->update(['ativo' => true]);
                $message = 'Usuários ativados com sucesso!';
                break;
            case 'deactivate':
                $users->update(['ativo' => false]);
                $message = 'Usuários desativados com sucesso!';
                break;
            case 'delete':
                $users->delete();
                $message = 'Usuários excluídos com sucesso!';
                break;
        }

        return redirect()->route('admin.people.users.index')->with('success', $message);
    }
}