<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\{Role, Permission};
use App\Models\User;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin.access']);
    }

    /**
     * Dashboard de permissões
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        $users = User::with(['roles', 'permissions'])->paginate(20);

        $estatisticas = [
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'total_users' => User::count(),
            'users_with_roles' => User::whereHas('roles')->count(),
        ];

        return view('admin.permissions.index', compact('roles', 'permissions', 'users', 'estatisticas'));
    }

    /**
     * Gerenciar roles
     */
    public function roles()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        return view('admin.permissions.roles', compact('roles', 'permissions'));
    }

    /**
     * Criar role
     */
    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.permissions.roles')
                        ->with('success', 'Role criada com sucesso!');
    }

    /**
     * Editar role
     */
    public function editRole(Role $role)
    {
        $permissions = Permission::all();
        return view('admin.permissions.edit-role', compact('role', 'permissions'));
    }

    /**
     * Atualizar role
     */
    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'array'
        ]);

        $role->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.permissions.roles')
                        ->with('success', 'Role atualizada com sucesso!');
    }

    /**
     * Excluir role
     */
    public function deleteRole(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Não é possível excluir uma role que possui usuários.');
        }

        $role->delete();

        return redirect()->route('admin.permissions.roles')
                        ->with('success', 'Role excluída com sucesso!');
    }

    /**
     * Gerenciar permissões
     */
    public function permissions()
    {
        $permissions = Permission::with('roles')->get();
        
        // Organizar permissões por categorias
        $categorias = [
            'pessoas' => [
                'title' => '👥 Gestão de Pessoas',
                'description' => 'Permissões para gerenciar membros, usuários e ministérios',
                'permissions' => $permissions->filter(function($p) {
                    return str_contains($p->name, 'people.') || 
                           str_contains($p->name, 'members.') || 
                           str_contains($p->name, 'users.') ||
                           str_contains($p->name, 'ministries.') ||
                           str_contains($p->name, 'departments.');
                })
            ],
            'financeiro' => [
                'title' => '💰 Gestão Financeira',
                'description' => 'Permissões para gerenciar transações, campanhas e relatórios financeiros',
                'permissions' => $permissions->filter(function($p) {
                    return str_contains($p->name, 'finance.') || 
                           str_contains($p->name, 'transactions.') || 
                           str_contains($p->name, 'campaigns.');
                })
            ],
            'ebd' => [
                'title' => '📚 Escola Bíblica Dominical',
                'description' => 'Permissões para gerenciar turmas, professores, alunos e lições',
                'permissions' => $permissions->filter(function($p) {
                    return str_contains($p->name, 'ebd.');
                })
            ],
            'devocionais' => [
                'title' => '📖 Devocionais',
                'description' => 'Permissões para gerenciar devocionais diários',
                'permissions' => $permissions->filter(function($p) {
                    return str_contains($p->name, 'devotionals.');
                })
            ],
            'conselho' => [
                'title' => '🏛️ Conselho da Igreja',
                'description' => 'Permissões para gerenciar reuniões e votações do conselho',
                'permissions' => $permissions->filter(function($p) {
                    return str_contains($p->name, 'council.');
                })
            ],
            'intercessao' => [
                'title' => '🙏 Pedidos de Oração',
                'description' => 'Permissões para gerenciar pedidos de oração e intercessões',
                'permissions' => $permissions->filter(function($p) {
                    return str_contains($p->name, 'intercessor.');
                })
            ],
            'notificacoes' => [
                'title' => '🔔 Notificações',
                'description' => 'Permissões para gerenciar notificações do sistema',
                'permissions' => $permissions->filter(function($p) {
                    return str_contains($p->name, 'notifications.');
                })
            ],
            'sistema' => [
                'title' => '⚙️ Sistema',
                'description' => 'Permissões para configurações, logs e backup do sistema',
                'permissions' => $permissions->filter(function($p) {
                    return str_contains($p->name, 'system.') || 
                           str_contains($p->name, 'logs.') || 
                           str_contains($p->name, 'settings.');
                })
            ],
            'relatorios' => [
                'title' => '📊 Relatórios',
                'description' => 'Permissões para visualizar e exportar relatórios',
                'permissions' => $permissions->filter(function($p) {
                    return str_contains($p->name, 'reports.');
                })
            ]
        ];
        
        return view('admin.permissions.permissions', compact('permissions', 'categorias'));
    }

    /**
     * Criar permissão
     */
    public function createPermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'description' => 'nullable|string|max:500',
            'guard_name' => 'required|string|max:255'
        ]);

        Permission::create([
            'name' => $request->name,
            'description' => $request->description,
            'guard_name' => $request->guard_name
        ]);

        return redirect()->route('admin.permissions.permissions')
                        ->with('success', 'Permissão criada com sucesso!');
    }

    /**
     * Editar permissão
     */
    public function editPermission(Permission $permission)
    {
        $roles = Role::all();
        return view('admin.permissions.edit-permission', compact('permission', 'roles'));
    }

    /**
     * Atualizar permissão
     */
    public function updatePermission(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string|max:500',
            'guard_name' => 'required|string|max:255'
        ]);

        $permission->update([
            'name' => $request->name,
            'description' => $request->description,
            'guard_name' => $request->guard_name
        ]);

        return redirect()->route('admin.permissions.permissions')
                        ->with('success', 'Permissão atualizada com sucesso!');
    }

    /**
     * Excluir permissão
     */
    public function deletePermission(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return back()->with('error', 'Não é possível excluir uma permissão que está sendo usada por roles.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.permissions')
                        ->with('success', 'Permissão excluída com sucesso!');
    }

    /**
     * Gerenciar usuários
     */
    public function users(Request $request)
    {
        $query = User::with(['roles', 'permissions']);

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->paginate(20);
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.permissions.users', compact('users', 'roles', 'permissions'));
    }

    /**
     * Editar permissões do usuário
     */
    public function editUser(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        
        return view('admin.permissions.edit-user', compact('user', 'roles', 'permissions'));
    }

    /**
     * Atualizar permissões do usuário
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array',
            'permissions' => 'array'
        ]);

        // Sincronizar roles
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        // Sincronizar permissões diretas
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.permissions.users')
                        ->with('success', 'Permissões do usuário atualizadas com sucesso!');
    }

    /**
     * Bulk assign roles
     */
    public function bulkAssignRoles(Request $request)
    {
        $request->validate([
            'users' => 'required|array',
            'role' => 'required|exists:roles,id'
        ]);

        $role = Role::find($request->role);
        $users = User::whereIn('id', $request->users)->get();

        foreach ($users as $user) {
            $user->assignRole($role);
        }

        return back()->with('success', 'Role atribuída com sucesso aos usuários selecionados!');
    }

    /**
     * Bulk assign permissions
     */
    public function bulkAssignPermissions(Request $request)
    {
        $request->validate([
            'users' => 'required|array',
            'permissions' => 'required|array'
        ]);

        $users = User::whereIn('id', $request->users)->get();

        foreach ($users as $user) {
            $user->givePermissionTo($request->permissions);
        }

        return back()->with('success', 'Permissões atribuídas com sucesso aos usuários selecionados!');
    }

    /**
     * Relatórios de permissões
     */
    public function reports()
    {
        $estatisticas = [
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'total_users' => User::count(),
            'users_with_roles' => User::whereHas('roles')->count(),
            'users_with_permissions' => User::whereHas('permissions')->count(),
        ];

        $rolesComUsuarios = Role::withCount('users')->get();
        $permissionsComRoles = Permission::withCount('roles')->get();
        $usuariosComRoles = User::with('roles')->whereHas('roles')->get();

        return view('admin.permissions.reports', compact('estatisticas', 'rolesComUsuarios', 'permissionsComRoles', 'usuariosComRoles'));
    }

    /**
     * Verificar permissões do usuário
     */
    public function checkUserPermissions(User $user)
    {
        $user->load(['roles', 'permissions']);
        
        $permissions = Permission::all();
        $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();
        
        $permissionsStatus = [];
        foreach ($permissions as $permission) {
            $permissionsStatus[$permission->name] = [
                'has_permission' => in_array($permission->name, $userPermissions),
                'via_role' => $user->hasPermissionTo($permission->name),
                'direct' => $user->hasDirectPermission($permission->name)
            ];
        }

        return view('admin.permissions.check-user', compact('user', 'permissionsStatus'));
    }

    /**
     * Resetar permissões do usuário
     */
    public function resetUserPermissions(User $user)
    {
        $user->syncRoles([]);
        $user->syncPermissions([]);

        return back()->with('success', 'Permissões do usuário resetadas com sucesso!');
    }

    /**
     * Importar permissões padrão
     */
    public function importDefaultPermissions()
    {
        $defaultPermissions = [
            // Sistema
            'system.access' => 'Acesso ao sistema',
            'system.settings' => 'Configurações do sistema',
            'system.logs' => 'Visualizar logs',
            'system.backup' => 'Backup do sistema',
            
            // Pessoas
            'people.access' => 'Acesso à gestão de pessoas',
            'people.members' => 'Gerenciar membros',
            'people.users' => 'Gerenciar usuários',
            'people.ministries' => 'Gerenciar ministérios',
            'people.departments' => 'Gerenciar departamentos',
            
            // Financeiro
            'finance.access' => 'Acesso à gestão financeira',
            'finance.transactions' => 'Gerenciar transações',
            'finance.campaigns' => 'Gerenciar campanhas',
            'finance.reports' => 'Relatórios financeiros',
            
            // EBD
            'ebd.access' => 'Acesso à EBD',
            'ebd.turmas' => 'Gerenciar turmas EBD',
            'ebd.professores' => 'Gerenciar professores EBD',
            'ebd.alunos' => 'Gerenciar alunos EBD',
            'ebd.licoes' => 'Gerenciar lições EBD',
            'ebd.aulas' => 'Gerenciar aulas EBD',
            'ebd.avaliacoes' => 'Gerenciar avaliações EBD',
            'ebd.relatorios' => 'Relatórios EBD',
            
            // Devocionais
            'devotionals.access' => 'Acesso aos devocionais',
            'devotionals.create' => 'Criar devocionais',
            'devotionals.edit' => 'Editar devocionais',
            'devotionals.delete' => 'Excluir devocionais',
            
            // Conselho
            'council.access' => 'Acesso ao conselho',
            'council.meetings' => 'Gerenciar reuniões',
            'council.voting' => 'Gerenciar votações',
            'council.agenda' => 'Gerenciar agenda',
            
            // Intercessores
            'intercessor.access' => 'Acesso aos intercessores',
            'intercessor.dashboard' => 'Dashboard de intercessor',
            'intercessor.view_pedidos' => 'Visualizar pedidos',
            'intercessor.registrar_intercessao' => 'Registrar intercessão',
            'intercessor.atualizar_status' => 'Atualizar status',
            'intercessor.view_relatorios' => 'Visualizar relatórios',
            
            // Notificações
            'notifications.access' => 'Acesso às notificações',
            'notifications.create' => 'Criar notificações',
            'notifications.edit' => 'Editar notificações',
            'notifications.delete' => 'Excluir notificações',
            
            // Configurações
            'settings.access' => 'Acesso às configurações',
            'settings.edit' => 'Editar configurações',
            
            // Logs
            'logs.access' => 'Acesso aos logs',
            'logs.edit' => 'Editar logs',
        ];

        foreach ($defaultPermissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name],
                [
                    'name' => $name,
                    'description' => $description,
                    'guard_name' => 'web'
                ]
            );
        }

        return back()->with('success', 'Permissões padrão importadas com sucesso!');
    }
} 