<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membro;
use App\Models\User;
use App\Models\Ministerio;
use App\Models\Departamento;
use App\Models\Cargo;
use App\Models\SolicitacaoMinisterio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Exports\MembrosExport;
use App\Exports\UsuariosExport;
use App\Exports\MinisteriosExport;
use App\Exports\AniversariantesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\PdfService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;


class PeopleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:people.access');
    }

    /**
     * Dashboard da gestão de pessoas
     */
    public function index()
    {
        $estatisticas = [
            'total_membros' => Membro::count(),
            'total_usuarios' => User::count(),
            'total_ministerios' => Ministerio::count(),
            'total_departamentos' => Departamento::count(),
            'membros_ativos' => Membro::where('ativo', true)->count(),
            'usuarios_ativos' => User::where('ativo', true)->count(),
        ];

        $membrosRecentes = Membro::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $ministeriosComEstatisticas = Ministerio::with('departamentos')
            ->get()
            ->map(function ($ministerio) {
                $membrosCount = Membro::join('membro_cargo', 'membros.id', '=', 'membro_cargo.membro_id')
                    ->join('cargos', 'membro_cargo.cargo_id', '=', 'cargos.id')
                    ->join('departamentos', 'cargos.departamento_id', '=', 'departamentos.id')
                    ->where('departamentos.ministerio_id', $ministerio->id)
                    ->where('membro_cargo.ativo', true)
                    ->where('membros.ativo', true)
                    ->count();
                
                $ministerio->membros_count = $membrosCount;
                return $ministerio;
            })
            ->sortByDesc('membros_count')
            ->take(5);

        return view('admin.people.dashboard', compact('estatisticas', 'membrosRecentes', 'ministeriosComEstatisticas'));
    }

    /**
     * Lista de membros
     */
    public function members(Request $request)
    {
        $query = Membro::with(['cargos.departamento.ministerio', 'cargos.departamento', 'cargos']);

        // Filtros
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('telefone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'ativo') {
                $query->where('ativo', true);
            } elseif ($request->status === 'inativo') {
                $query->where('ativo', false);
            }
        }

        if ($request->filled('ministerio')) {
            $query->whereHas('cargos.departamento.ministerio', function($q) use ($request) {
                $q->where('ministerios.id', $request->ministerio);
            });
        }



        $membros = $query->paginate(15);

        // Estatísticas
        $totalMembros = Membro::count();
        $membrosAtivos = Membro::where('ativo', true)->count();
        $membrosInativos = Membro::where('ativo', false)->count();
        $aniversariantes = Membro::where('ativo', true)
            ->whereMonth('data_nascimento', now()->month)
            ->count();
        return view('admin.people.members.index', compact(
            'membros', 
            'totalMembros', 
            'membrosAtivos', 
            'membrosInativos',
            'aniversariantes'
        ));
    }

    /**
     * Criar novo membro
     */
    public function createMember()
    {
        $ministerios = Ministerio::all();
        $departamentos = Departamento::all();
        $cargos = Cargo::all();
        
        // Buscar usuários que ainda não têm membro associado
        $users = User::whereDoesntHave('membro')->where('ativo', true)->get();

        return view('admin.people.members.create', compact('ministerios', 'departamentos', 'cargos', 'users'));
    }

    /**
     * Salvar novo membro
     */
    public function storeMember(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:membros,user_id',
            'data_nascimento' => 'nullable|date',
            'sexo' => 'nullable|string|in:M,F',
            'estado_civil' => 'nullable|string|in:solteiro,casado,divorciado,viuvo',
            'endereco' => 'nullable|string|max:500',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
            'data_batismo' => 'nullable|date',
            'data_ingresso' => 'nullable|date',
            'profissao' => 'nullable|string|max:100',
            'escolaridade' => 'nullable|string|max:50',
            'observacoes' => 'nullable|string',
            'ativo' => 'boolean',
            'receber_notificacoes' => 'boolean',
            'receber_newsletter' => 'boolean',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ministerio_id' => 'nullable|exists:ministerios,id',
            'cargo_id' => 'nullable|exists:cargos,id',
            'departamento_id' => 'nullable|exists:departamentos,id',
        ]);

        // Preparar dados para criação
        $dadosValidos = [
            'user_id' => $request->user_id,
            'data_nascimento' => $request->data_nascimento,
            'sexo' => $request->sexo,
            'estado_civil' => $request->estado_civil,
            'endereco' => $request->endereco,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
            'cep' => $request->cep,
            'data_batismo' => $request->data_batismo,
            'data_ingresso' => $request->data_ingresso,
            'profissao' => $request->profissao,
            'escolaridade' => $request->escolaridade,
            'observacoes' => $request->observacoes,
            'ativo' => $request->has('ativo'),
            'receber_notificacoes' => $request->has('receber_notificacoes'),
            'receber_newsletter' => $request->has('receber_newsletter'),
        ];

        // Processar upload de foto se fornecido
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            
            // Validar tipo de arquivo
            $extensao = strtolower($foto->getClientOriginalExtension());
            if (!in_array($extensao, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return redirect()->back()
                    ->with('error', 'Formato de imagem não suportado. Use JPG, PNG, GIF ou WebP.')
                    ->withInput();
            }
            
            // Gerar nome único para o arquivo
            $nomeArquivo = 'membro_' . time() . '_' . uniqid() . '.' . $extensao;
            $caminho = $foto->storeAs('membros/fotos', $nomeArquivo, 'public');
            $dadosValidos['foto'] = $caminho;
        }

        $membro = Membro::create($dadosValidos);

        // Processar relacionamentos se fornecidos
        if ($request->filled('cargo_id')) {
            $membro->cargos()->attach($request->cargo_id, [
                'data_inicio' => now(),
                'ativo' => true
            ]);
        }

        return redirect()->route('admin.people.members.index')
            ->with('success', 'Membro criado com sucesso!');
    }

    /**
     * Editar membro
     */
    public function editMember(Membro $membro)
    {
        $ministerios = Ministerio::all();
        $departamentos = Departamento::all();
        $cargos = Cargo::all();

        // Carregar relacionamentos do membro
        $membro->load(['cargos.departamento.ministerio']);

        return view('admin.people.members.edit', compact('membro', 'ministerios', 'departamentos', 'cargos'));
    }

    /**
     * Atualizar membro
     */
    public function updateMember(Request $request, Membro $membro)
    {
        $request->validate([
            'data_nascimento' => 'nullable|date',
            'sexo' => 'nullable|string|in:M,F',
            'estado_civil' => 'nullable|string|in:solteiro,casado,divorciado,viuvo',
            'endereco' => 'nullable|string|max:500',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
            'data_batismo' => 'nullable|date',
            'data_ingresso' => 'nullable|date',
            'profissao' => 'nullable|string|max:100',
            'escolaridade' => 'nullable|string|max:50',
            'observacoes' => 'nullable|string',
            'ativo' => 'boolean',
            'receber_notificacoes' => 'boolean',
            'receber_newsletter' => 'boolean',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ministerio_id' => 'nullable|exists:ministerios,id',
            'cargo_id' => 'nullable|exists:cargos,id',
            'departamento_id' => 'nullable|exists:departamentos,id',
        ]);

        // Preparar dados para atualização
        $dadosValidos = [
            'data_nascimento' => $request->data_nascimento,
            'sexo' => $request->sexo,
            'estado_civil' => $request->estado_civil,
            'endereco' => $request->endereco,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
            'cep' => $request->cep,
            'data_batismo' => $request->data_batismo,
            'data_ingresso' => $request->data_ingresso,
            'profissao' => $request->profissao,
            'escolaridade' => $request->escolaridade,
            'observacoes' => $request->observacoes,
            'ativo' => $request->has('ativo'),
            'receber_notificacoes' => $request->has('receber_notificacoes'),
            'receber_newsletter' => $request->has('receber_newsletter'),
        ];

        // Processar upload de foto se fornecido
        if ($request->hasFile('foto')) {
            // Excluir foto antiga se existir
            if ($membro->foto && Storage::disk('public')->exists($membro->foto)) {
                Storage::disk('public')->delete($membro->foto);
            }
            
            $foto = $request->file('foto');
            
            // Validar tipo de arquivo
            $extensao = strtolower($foto->getClientOriginalExtension());
            if (!in_array($extensao, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return redirect()->back()
                    ->with('error', 'Formato de imagem não suportado. Use JPG, PNG, GIF ou WebP.')
                    ->withInput();
            }
            
            // Gerar nome único para o arquivo
            $nomeArquivo = 'membro_' . $membro->id . '_' . time() . '_' . uniqid() . '.' . $extensao;
            $caminho = $foto->storeAs('membros/fotos', $nomeArquivo, 'public');
            $dadosValidos['foto'] = $caminho;
        }

        $membro->update($dadosValidos);

        // Processar relacionamentos se fornecidos
        if ($request->filled('cargo_id')) {
            // Remover cargos atuais
            $membro->cargos()->detach();
            
            // Adicionar novo cargo
            $membro->cargos()->attach($request->cargo_id, [
                'data_inicio' => now(),
                'ativo' => true
            ]);
        }

        return redirect()->route('admin.people.members.index')
            ->with('success', 'Membro atualizado com sucesso!');
    }



    /**
     * Lista de usuários
     */
    public function users(Request $request)
    {
        $query = User::with(['roles', 'permissions']);

        // Filtros
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
        $usuarios = $users; // Alias para compatibilidade com a view
        $roles = Role::all();

        return view('admin.people.users.index', compact('users', 'usuarios', 'roles'));
    }

    /**
     * Criar novo usuário
     */
    public function createUser()
    {
        // Carregar todos os dados necessários para a view
        $roles = Role::all();
        $permissions = Permission::all();
        $membros = Membro::where('ativo', true)
            ->orderBy('nome')
            ->get();
        $ministerios = Ministerio::where('ativo', true)
            ->orderBy('nome')
            ->get();
        $departamentos = Departamento::where('ativo', true)
            ->orderBy('nome')
            ->get();
        $cargos = Cargo::with('departamento')
            ->orderBy('nome')
            ->get();

        return view('admin.people.users.create', compact('roles', 'permissions', 'membros', 'ministerios', 'departamentos', 'cargos'));
    }

    /**
     * Salvar novo usuário
     */
    public function storeUser(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->is_admin,
            'ativo' => $request->ativo ?? true,
            'email_verified_at' => $request->email_verified ? now() : null,
        ]);

        // Associar a um membro existente se selecionado
        if ($request->filled('membro_id')) {
            $membro = Membro::find($request->membro_id);
            if ($membro) {
                $membro->update(['user_id' => $user->id]);
            }
        }
        // Caso contrário, um novo membro será criado automaticamente pelo Observer

        // Atribuir roles se fornecidos
        if ($request->filled('roles')) {
            $user->assignRole($request->roles);
        }

        // Atribuir permissões se fornecidas
        if ($request->filled('permissions')) {
            $user->givePermissionTo($request->permissions);
        }

        // Se for admin, dar role de admin automaticamente
        if ($request->is_admin) {
            $user->assignRole('admin');
        } else {
            $user->assignRole('member');
        }

        // Enviar email de boas-vindas se solicitado
        if ($request->send_welcome_email) {
            // Aqui você pode implementar o envio do email
            // Mail::to($user->email)->send(new WelcomeUserMail($user, $request->password));
        }

        return redirect()->route('admin.people.users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Editar usuário
     */
    public function editUser(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.people.users.edit', compact('user', 'roles', 'permissions'));
    }

    /**
     * Atualizar usuário
     */
    public function updateUser(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'ativo' => $request->ativo ?? true,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($request->filled('roles')) {
            // Converter IDs para nomes de roles
            $roleIds = $request->roles;
            $roles = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
            $user->syncRoles($roles);
        }

        if ($request->filled('permissions')) {
            // Converter IDs para nomes de permissions
            $permissionIds = $request->permissions;
            $permissions = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();
            $user->syncPermissions($permissions);
        }

        return redirect()->route('admin.people.users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Lista de ministérios
     */
    public function ministries(Request $request)
    {
        $query = Ministerio::with(['departamentos'])
            ->withCount(['departamentos']);

        // Filtros
        if ($request->filled('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'ativo') {
                $query->where('ativo', true);
            } elseif ($request->status === 'inativo') {
                $query->where('ativo', false);
            }
        }

        $ministerios = $query->paginate(15);
        
        // Adicionar contagem de membros para cada minist\u00e9rio
        $ministerios->getCollection()->transform(function ($ministerio) {
            $ministerio->membros_count = $ministerio->getMembrosCountAttribute();
            return $ministerio;
        });

        // Estatísticas
        $totalMembros = Membro::count();
        $membrosAtivos = Membro::where('ativo', true)->count();
        $totalMinisterios = Ministerio::count();
        $ministeriosAtivos = Ministerio::where('ativo', true)->count();
        $totalDepartamentos = Departamento::count();
        $departamentosAtivos = Departamento::where('ativo', true)->count();

        return view('admin.people.ministries.index', compact(
            'ministerios', 
            'totalMembros', 
            'membrosAtivos', 
            'totalMinisterios', 
            'ministeriosAtivos', 
            'totalDepartamentos', 
            'departamentosAtivos'
        ));
    }

    /**
     * Criar novo ministério
     */
    public function createMinistry()
    {
        $membros = Membro::where('ativo', true)->get();

        return view('admin.people.ministries.create', compact('membros'));
    }

    /**
     * Salvar novo ministério
     */
    public function storeMinistry(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'cor' => 'nullable|string|max:7',
            'responsavel_id' => 'nullable|exists:users,id',
            'data_fundacao' => 'nullable|date',
            'reuniao_semanal' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        Ministerio::create($request->all());

        return redirect()->route('admin.people.ministries.index')
            ->with('success', 'Ministério criado com sucesso!');
    }

    /**
     * Editar ministério
     */
    public function editMinistry(Ministerio $ministerio)
    {
        // Carregar o ministério com todos os relacionamentos necessários
        $ministerio->load([
            'departamentos' => function($query) {
                $query->withCount(['membros', 'cargos'])->orderBy('nome');
            },
            'responsavel.membro',
            'departamentos.membros',
            'departamentos.cargos'
        ]);

        // Carregar dados para os selects
        $membros = Membro::where('ativo', true)
            ->with(['user', 'cargos.departamento'])
            ->orderBy('nome')
            ->get();

        // Calcular estatísticas
        $ministerio->departamentos_count = $ministerio->departamentos->count();
        $ministerio->membros_count = $ministerio->departamentos->sum('membros_count');
        $ministerio->cargos_count = $ministerio->departamentos->sum('cargos_count');

        return view('admin.people.ministries.edit', compact('ministerio', 'membros'));
    }

    /**
     * Atualizar ministério
     */
    public function updateMinistry(Request $request, Ministerio $ministerio)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'cor' => 'nullable|string|max:7',
            'responsavel_id' => 'nullable|exists:users,id',
            'data_fundacao' => 'nullable|date',
            'reuniao_semanal' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        $ministerio->update($request->all());

        return redirect()->route('admin.people.ministries.index')
            ->with('success', 'Ministério atualizado com sucesso!');
    }

    /**
     * Lista de departamentos
     */
    public function departments(Request $request)
    {
        $query = Departamento::with(['ministerio', 'cargos', 'responsavel'])
            ->withCount(['membros', 'cargos']);

        // Filtros
        if ($request->filled('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('ministerio')) {
            $query->where('ministerio_id', $request->ministerio);
        }

        if ($request->filled('status')) {
            if ($request->status === 'ativo') {
                $query->where('ativo', true);
            } elseif ($request->status === 'inativo') {
                $query->where('ativo', false);
            }
        }

        $departamentos = $query->paginate(15);
        $ministerios = Ministerio::where('ativo', true)->orderBy('nome')->get();

        // Estatísticas
        $totalMembros = Membro::count();
        $membrosAtivos = Membro::where('ativo', true)->count();
        $totalMinisterios = Ministerio::count();
        $ministeriosAtivos = Ministerio::where('ativo', true)->count();
        $totalDepartamentos = Departamento::count();
        $departamentosAtivos = Departamento::where('ativo', true)->count();
        $totalCargos = \App\Models\Cargo::count();
        $cargosAtivos = \App\Models\Cargo::where('ativo', true)->count();

        return view('admin.people.departments.index', compact(
            'departamentos', 
            'ministerios', 
            'totalMembros', 
            'membrosAtivos', 
            'totalMinisterios', 
            'ministeriosAtivos', 
            'totalDepartamentos', 
            'departamentosAtivos',
            'totalCargos',
            'cargosAtivos'
        ));
    }

    /**
     * Criar novo departamento
     */
    public function createDepartment()
    {
        $ministerios = Ministerio::where('ativo', true)
            ->withCount('departamentos')
            ->orderBy('nome')
            ->get();
            
        $membros = Membro::where('ativo', true)
            ->with(['cargos.departamento.ministerio'])
            ->orderBy('nome')
            ->get();

        return view('admin.people.departments.create', compact('ministerios', 'membros'));
    }

    /**
     * Salvar novo departamento
     */
    public function storeDepartment(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ministerio_id' => 'required|exists:ministerios,id',
            'responsavel_id' => 'nullable|exists:membros,id',
            'ativo' => 'boolean',
        ]);

        Departamento::create($request->all());

        return redirect()->route('admin.people.departments.index')
            ->with('success', 'Departamento criado com sucesso!');
    }

    /**
     * Aniversariantes
     */
    public function birthdays(Request $request)
    {
        try {
            $mes = $request->get('mes', now()->month);
            $ano = $request->get('ano', now()->year);
            $ministerio = $request->get('ministerio');

            // Inicializar todas as variáveis com valores seguros
            $aniversariantes = collect();
            $aniversariantesMes = collect();
            $aniversariantesHoje = collect();
            $aniversariantesSemana = collect();
            $totalMembrosAtivos = 0;
            $ministerios = collect();

            // Consulta principal
            $query = Membro::where('ativo', true)->whereMonth('data_nascimento', $mes);
            
            // Só filtrar por ano se for especificamente solicitado e diferente do atual
            if ($request->has('ano') && $ano != now()->year) {
                $query->whereYear('data_nascimento', $ano);
            }

            // Filtro por ministério
            $ministerio_id = $request->get('ministerio_id') ?: $ministerio;
            if ($ministerio_id) {
                $query->whereHas('cargos.departamento.ministerio', function($q) use ($ministerio_id) {
                    $q->where('ministerios.id', $ministerio_id);
                });
            }

            $aniversariantes = $query->orderByRaw('DAY(data_nascimento) ASC')->paginate(15);
            
            // Ministérios
            $ministerios = Ministerio::all() ?? collect();

            // Estatísticas
            $aniversariantesMes = Membro::where('ativo', true)
                ->whereMonth('data_nascimento', now()->month)
                ->get() ?? collect();
                
            $aniversariantesHoje = Membro::where('ativo', true)
                ->whereMonth('data_nascimento', now()->month)
                ->whereDay('data_nascimento', now()->day)
                ->get() ?? collect();
                
            $diaAtual = now()->day;
            $aniversariantesSemana = Membro::where('ativo', true)
                ->whereMonth('data_nascimento', now()->month)
                ->whereRaw('DAY(data_nascimento) BETWEEN ? AND ?', [
                    max(1, $diaAtual - 3),
                    min(31, $diaAtual + 3)
                ])
                ->get() ?? collect();
                
            $totalMembrosAtivos = Membro::where('ativo', true)->count() ?? 0;

            // Array de meses para o filtro
            $meses = [
                1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
            ];

            return view('admin.people.birthdays.index', compact(
                'aniversariantes', 'ministerios', 'mes', 'ano', 
                'aniversariantesMes', 'aniversariantesHoje', 'aniversariantesSemana', 
                'totalMembrosAtivos', 'meses'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Erro no birthdays controller: ' . $e->getMessage());
            
            // Retornar valores padrão em caso de erro
            return view('admin.people.birthdays.index', [
                'aniversariantes' => collect(),
                'ministerios' => collect(),
                'mes' => now()->month,
                'ano' => now()->year,
                'aniversariantesMes' => collect(),
                'aniversariantesHoje' => collect(),
                'aniversariantesSemana' => collect(),
                'totalMembrosAtivos' => 0,
                'meses' => [
                    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                ]
            ]);
        }
    }

    /**
     * Exportar dados
     */
    public function export(Request $request)
    {
        $tipo = $request->get('tipo', 'membros');

        switch ($tipo) {
            case 'membros':
                return Excel::download(new MembrosExport(), 'membros.xlsx');
            case 'usuarios':
                return Excel::download(new UsuariosExport(), 'usuarios.xlsx');
            case 'ministerios':
                return Excel::download(new MinisteriosExport(), 'ministerios.xlsx');
            case 'aniversariantes':
                $mes = $request->get('mes', now()->month);
                $ano = $request->get('ano', now()->year);
                return Excel::download(new AniversariantesExport($mes, $ano), 'aniversariantes.xlsx');
            default:
                return redirect()->back()->with('error', 'Tipo de exportação inválido');
        }
    }

    /**
     * Visualizar membro
     */
    public function showMember(Membro $membro)
    {
        // Carregar apenas relacionamentos básicos para evitar problemas de conexão
        $membro->load(['user']);
        
        // Verificar se o perfil é público
        $user = $membro->user;
        $perfilPublico = $user && $user->public_profile;
        
        // Se não for público e não for admin, negar acesso
        if (!$perfilPublico && !auth()->user()->hasRole(['Super Admin', 'Admin', 'Pastor'])) {
            abort(403, 'Este perfil não está disponível para visualização pública.');
        }
        
        return view('admin.people.members.show', compact('membro', 'perfilPublico'));
    }

    /**
     * Excluir membro
     */
    public function deleteMember(Membro $membro)
    {
        $membro->delete();

        return redirect()->route('admin.people.members.index')
            ->with('success', 'Membro excluído com sucesso!');
    }

    /**
     * Visualizar usuário
     */
    public function showUser(User $user)
    {
        return view('admin.people.users.show', compact('user'));
    }

    /**
     * Excluir usuário
     */
    public function deleteUser(User $user)
    {
        $user->delete();

        return redirect()->route('admin.people.users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }

    /**
     * Visualizar ministério
     */
    public function showMinistry(Ministerio $ministerio)
    {
        return view('admin.people.ministries.show', compact('ministerio'));
    }

    /**
     * Excluir ministério
     */
    public function deleteMinistry(Ministerio $ministerio)
    {
        $ministerio->delete();

        return redirect()->route('admin.people.ministries.index')
            ->with('success', 'Ministério excluído com sucesso!');
    }

    /**
     * Editar departamento
     */
    public function editDepartment(Departamento $departamento)
    {
        // Carregar o departamento com todos os relacionamentos necessários
        $departamento->load([
            'ministerio',
            'responsavel',
            'cargos' => function($query) {
                $query->withCount('membros')->orderBy('nome');
            },
            'cargos.membros'
        ]);
        
        // Dados para os selects
        $ministerios = Ministerio::where('ativo', true)
            ->withCount('departamentos')
            ->orderBy('nome')
            ->get();
            
        $membros = Membro::where('ativo', true)
            ->with(['cargos.departamento.ministerio'])
            ->orderBy('nome')
            ->get();

        // Calcular estatísticas
        $departamento->membros_count = $departamento->cargos->sum('membros_count');
        $departamento->cargos_count = $departamento->cargos->count();

        return view('admin.people.departments.edit', compact('departamento', 'ministerios', 'membros'));
    }

    /**
     * Atualizar departamento
     */
    public function updateDepartment(Request $request, Departamento $departamento)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ministerio_id' => 'required|exists:ministerios,id',
            'responsavel_id' => 'nullable|exists:membros,id',
            'observacoes' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        $departamento->update($request->all());

        return redirect()->route('admin.people.departments.index')
            ->with('success', 'Departamento atualizado com sucesso!');
    }

    /**
     * Excluir departamento
     */
    public function deleteDepartment(Departamento $departamento)
    {
        $departamento->delete();

        return redirect()->route('admin.people.departments.index')
            ->with('success', 'Departamento excluído com sucesso!');
    }

    /**
     * Próximos aniversariantes
     */
    public function upcomingBirthdays()
    {
        $proximosAniversariantes = Membro::where('ativo', true)
            ->whereRaw('DATE_FORMAT(data_nascimento, "%m-%d") >= ?', [now()->format('m-d')])
            ->whereRaw('DATE_FORMAT(data_nascimento, "%m-%d") <= ?', [now()->addDays(30)->format('m-d')])
            ->orderByRaw('DATE_FORMAT(data_nascimento, "%m-%d")')
            ->paginate(15);

        return view('admin.people.birthdays.upcoming', compact('proximosAniversariantes'));
    }

    /**
     * Exportar aniversariantes
     */
    public function exportBirthdays(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $ano = $request->get('ano', now()->year);

        return Excel::download(new AniversariantesExport($mes, $ano), "aniversariantes_{$mes}_{$ano}.xlsx");
    }

    /**
     * Exportar próximos aniversariantes
     */
    public function exportUpcomingBirthdays()
    {
        $proximosAniversariantes = Membro::where('ativo', true)
            ->whereRaw('DATE_FORMAT(data_nascimento, "%m-%d") >= ?', [now()->format('m-d')])
            ->whereRaw('DATE_FORMAT(data_nascimento, "%m-%d") <= ?', [now()->addDays(30)->format('m-d')])
            ->orderByRaw('DATE_FORMAT(data_nascimento, "%m-%d")')
            ->get();

        return Excel::download(new AniversariantesExport(now()->month, now()->year, $proximosAniversariantes), 'proximos_aniversariantes.xlsx');
    }

    /**
     * Relatórios
     */
    public function reports()
    {
        $estatisticas = [
            'total_membros' => Membro::count(),
            'membros_ativos' => Membro::where('ativo', true)->count(),
            'membros_inativos' => Membro::where('ativo', false)->count(),
            'total_ministerios' => Ministerio::count(),
            'ministerios_ativos' => Ministerio::where('ativo', true)->count(),
            'total_departamentos' => Departamento::count(),
            'departamentos_ativos' => Departamento::where('ativo', true)->count(),
        ];

        return view('admin.people.reports.index', compact('estatisticas'));
    }

    /**
     * Exportar relatórios
     */
    public function exportReports(Request $request)
    {
        $tipo = $request->get('tipo', 'membros');

        switch ($tipo) {
            case 'membros':
                return Excel::download(new MembrosExport(), 'relatorio_membros.xlsx');
            case 'ministerios':
                return Excel::download(new MinisteriosExport(), 'relatorio_ministerios.xlsx');
            case 'aniversariantes':
                $mes = $request->get('mes', now()->month);
                $ano = $request->get('ano', now()->year);
                return Excel::download(new AniversariantesExport($mes, $ano), 'relatorio_aniversariantes.xlsx');
            default:
                return redirect()->back()->with('error', 'Tipo de relatório inválido');
        }
    }

    /**
     * Ficha de membro
     */
    public function memberCard(Membro $membro)
    {
        // Usar DomPDF diretamente no Windows para evitar problemas com Snappy
        $configuracoes = [
            'nome' => \App\Models\Configuracao::get('igreja_nome', 'CONGREGAÇÃO BATISTA AVENIDA'),
            'endereco' => \App\Models\Configuracao::get('igreja_endereco', 'Rua da Avenida, 123 - Centro'),
            'cidade' => \App\Models\Configuracao::get('igreja_cidade', 'São Paulo - SP'),
            'telefone' => \App\Models\Configuracao::get('igreja_telefone', '(11) 99999-9999'),
            'email' => \App\Models\Configuracao::get('igreja_email', 'contato@cbav.com'),
            'site' => \App\Models\Configuracao::get('igreja_site', 'www.cbav.com'),
        ];
        
        $cargoAtivo = $membro->cargos()->wherePivot('ativo', true)->first();
        $departamento = $cargoAtivo ? $cargoAtivo->departamento : null;
        $ministerio = $departamento ? $departamento->ministerio : null;
        
        $dadosMembro = [
            'id' => $membro->id,
            'nome' => $membro->nome,
            'numeroMembro' => str_pad($membro->id, 4, '0', STR_PAD_LEFT),
            'ativo' => $membro->ativo,
            'dataNascimento' => $membro->data_nascimento ? $membro->data_nascimento->format('d/m/Y') : 'Não informado',
            'dataBatismo' => $membro->data_batismo ? $membro->data_batismo->format('d/m/Y') : 'Não informado',
            'dataMembro' => $membro->data_ingresso ? $membro->data_ingresso->format('d/m/Y') : 'Não informado',
            'profissao' => $membro->profissao ?: 'Não informado',
            'endereco' => $membro->endereco ?: 'Não informado',
            'bairro' => $membro->bairro ?: 'Não informado',
            'cidade' => $membro->cidade ?: 'Não informado',
            'estado' => $membro->estado ?: 'Não informado',
            'telefone' => $membro->telefone ?: 'Não informado',
            'email' => $membro->email ?: 'Não informado',
            'cargo' => $cargoAtivo ? $cargoAtivo->nome : 'Sem cargo',
            'departamento' => $departamento ? $departamento->nome : 'Sem departamento',
            'ministerio' => $ministerio ? $ministerio->nome : 'Sem ministério',
            'cep' => $membro->cep ?: 'Não informado',
            'dataValidade' => now()->addYears(2)->format('m/Y'),
            'fotoPath' => $membro->foto ? storage_path('app/public/' . $membro->foto) : null,
        ];
        
        $fotoBase64 = null;
        if ($dadosMembro['fotoPath'] && file_exists($dadosMembro['fotoPath'])) {
            $fotoBase64 = base64_encode(file_get_contents($dadosMembro['fotoPath']));
        }
        
        $html = $this->gerarTemplateHTML($configuracoes, $dadosMembro, $fotoBase64);
        
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->setOptions(new \Dompdf\Options([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial'
        ]));
        $dompdf->render();
        
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="ficha-membro-' . $membro->id . '.pdf"'
        ]);
    }

    /**
     * Preview da ficha de membro
     */
    public function memberCardPreview(Membro $membro)
    {
        return view('admin.people.members.ficha-preview', compact('membro'));
    }

    /**
     * Página de impressão da ficha de membro
     */
    public function memberCardPrint(Membro $membro)
    {
        return view('admin.people.members.ficha-print', compact('membro'));
    }

    /**
     * Gerar template HTML para a ficha
     */
    private function gerarTemplateHTML($configuracoes, $dadosMembro, $fotoBase64)
    {
        return '
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Ficha de Membro - ' . $dadosMembro['nome'] . '</title>
            <style>
                @page {
                    margin: 0;
                    size: A4;
                }
                
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background: white;
                    font-size: 10px;
                    line-height: 1.3;
                    color: #333;
                }
                
                .page-container {
                    width: 210mm;
                    height: 297mm;
                    margin: 0;
                    padding: 5mm 8mm;
                    background: white;
                    box-sizing: border-box;
                }
                
                /* Cabeçalho */
                .header {
                    background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #1e40af 100%);
                    color: white;
                    padding: 10px;
                    text-align: center;
                    border-radius: 4px 4px 0 0;
                    margin-bottom: 10px;
                }
                
                .header h1 {
                    font-size: 16px;
                    font-weight: bold;
                    margin-bottom: 2px;
                    text-transform: uppercase;
                    letter-spacing: 0.3px;
                }
                
                .header p {
                    font-size: 10px;
                    margin: 1px 0;
                    opacity: 0.9;
                }
                
                .header .subtitle {
                    font-size: 11px;
                    font-weight: bold;
                    margin-top: 5px;
                    text-transform: uppercase;
                }
                
                /* Conteúdo Principal */
                .content {
                    padding: 0;
                }
                
                /* Seção de Informações Pessoais */
                .section {
                    margin-bottom: 10px;
                    border: 1px solid #e5e7eb;
                    border-radius: 3px;
                    overflow: hidden;
                }
                
                .section-header {
                    background: #f8fafc;
                    padding: 8px 12px;
                    border-bottom: 1px solid #e5e7eb;
                }
                
                .section-header h2 {
                    font-size: 12px;
                    font-weight: bold;
                    color: #1e40af;
                    text-transform: uppercase;
                    letter-spacing: 0.3px;
                    margin: 0;
                }
                
                .section-content {
                    padding: 8px;
                }
                
                /* Grid de Informações */
                .info-grid {
                    display: table;
                    width: 100%;
                    border-collapse: collapse;
                }
                
                .info-grid-row {
                    display: table-row;
                }
                
                .info-grid-cell {
                    display: table-cell;
                    width: 50%;
                    padding-right: 3px;
                    vertical-align: top;
                }
                
                .info-item {
                    margin-bottom: 6px;
                }
                
                .info-label {
                    font-size: 9px;
                    font-weight: bold;
                    color: #6b7280;
                    text-transform: uppercase;
                    margin-bottom: 2px;
                    letter-spacing: 0.2px;
                }
                
                .info-value {
                    font-size: 9px;
                    color: #1f2937;
                    font-weight: 500;
                    padding: 3px 4px;
                    background: #f9fafb;
                    border: 1px solid #e5e7eb;
                    border-radius: 2px;
                    min-height: 14px;
                    word-wrap: break-word;
                    overflow-wrap: break-word;
                }
                
                /* Foto e Informações Principais */
                .main-info {
                    display: table;
                    width: 100%;
                    margin-bottom: 12px;
                }
                
                .photo-section {
                    display: table-cell;
                    width: 120px;
                    vertical-align: top;
                    padding-right: 10px;
                    text-align: center;
                }
                
                .personal-info {
                    display: table-cell;
                    width: calc(100% - 120px);
                    vertical-align: top;
                }
                
                .personal-info-grid {
                    display: table;
                    width: 100%;
                    border-collapse: collapse;
                }
                
                .personal-info-row {
                    display: table-row;
                }
                
                .personal-info-cell {
                    display: table-cell;
                    width: 50%;
                    padding-right: 3px;
                    vertical-align: top;
                }
                
                .photo-container {
                    width: 100px;
                    height: 120px;
                    margin: 0 auto 6px auto;
                    border: 2px solid #1e40af;
                    border-radius: 3px;
                    overflow: hidden;
                    background: #f8fafc;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                
                .photo {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
                
                .photo-placeholder {
                    width: 100%;
                    height: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 10px;
                    font-weight: bold;
                    color: #6b7280;
                    text-align: center;
                    line-height: 1.2;
                }
                
                .member-number {
                    font-size: 12px;
                    font-weight: bold;
                    color: #1e40af;
                    margin-bottom: 6px;
                }
                
                .member-status {
                    display: inline-block;
                    padding: 3px 8px;
                    border-radius: 12px;
                    font-size: 9px;
                    font-weight: bold;
                    text-transform: uppercase;
                    letter-spacing: 0.3px;
                }
                
                .status-ativo {
                    background: #10b981;
                    color: white;
                }
                
                .status-inativo {
                    background: #6b7280;
                    color: white;
                }
                
                /* Rodapé */
                .footer {
                    background: #f8fafc;
                    padding: 8px;
                    text-align: center;
                    border-top: 1px solid #e5e7eb;
                    margin-top: 10px;
                    position: absolute;
                    bottom: 5mm;
                    left: 5mm;
                    right: 5mm;
                }
                
                .footer p {
                    font-size: 9px;
                    color: #6b7280;
                    margin: 2px 0;
                }
                
                .signature-area {
                    margin-top: 15px;
                    text-align: center;
                }
                
                .signature-line {
                    width: 120px;
                    height: 1px;
                    background: #d1d5db;
                    margin: 0 auto 5px auto;
                }
                
                .signature-text {
                    font-size: 9px;
                    color: #6b7280;
                    text-transform: uppercase;
                    font-weight: bold;
                }
                
                /* Responsividade */
                @media print {
                    body {
                        padding: 0;
                    }
                    
                    .page-container {
                        box-shadow: none;
                        border-radius: 0;
                    }
                    
                    .header {
                        border-radius: 0;
                    }
                }
            </style>
        </head>
        <body>
            <div class="page-container">
                <!-- Cabeçalho -->
                <div class="header">
                    <h1>' . $configuracoes['nome'] . '</h1>
                    <p>' . $configuracoes['endereco'] . '</p>
                    <p>' . $configuracoes['cidade'] . '</p>
                    <p>' . $configuracoes['telefone'] . ' | ' . $configuracoes['email'] . '</p>
                    <div class="subtitle">Ficha de Cadastro de Membro</div>
                </div>

                <!-- Conteúdo Principal -->
                <div class="content">
                    <!-- Informações Principais -->
                    <div class="main-info">
                        <!-- Foto e Número -->
                        <div class="photo-section">
                            <div class="photo-container">
                                ' . ($fotoBase64 ? '<img src="data:image/jpeg;base64,' . $fotoBase64 . '" alt="Foto de ' . $dadosMembro['nome'] . '" class="photo">' : '<div class="photo-placeholder">FOTO<br>NÃO<br>DISPONÍVEL</div>') . '
                            </div>
                            <div class="member-number">Membro Nº ' . $dadosMembro['numeroMembro'] . '</div>
                            <div class="member-status ' . ($dadosMembro['ativo'] ? 'status-ativo' : 'status-inativo') . '">
                                ' . ($dadosMembro['ativo'] ? 'ATIVO' : 'INATIVO') . '
                            </div>
                        </div>

                        <!-- Informações Pessoais -->
                        <div class="personal-info">
                            <div class="personal-info-grid">
                                <div class="personal-info-row">
                                    <div class="personal-info-cell">
                                        <div class="info-item">
                                            <div class="info-label">Nome Completo</div>
                                            <div class="info-value">' . $dadosMembro['nome'] . '</div>
                                        </div>
                                    </div>
                                    <div class="personal-info-cell">
                                        <div class="info-item">
                                            <div class="info-label">Data de Nascimento</div>
                                            <div class="info-value">' . $dadosMembro['dataNascimento'] . '</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="personal-info-row">
                                    <div class="personal-info-cell">
                                        <div class="info-item">
                                            <div class="info-label">Data de Batismo</div>
                                            <div class="info-value">' . $dadosMembro['dataBatismo'] . '</div>
                                        </div>
                                    </div>
                                    <div class="personal-info-cell">
                                        <div class="info-item">
                                            <div class="info-label">Data de Ingresso</div>
                                            <div class="info-value">' . $dadosMembro['dataMembro'] . '</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="personal-info-row">
                                    <div class="personal-info-cell">
                                        <div class="info-item">
                                            <div class="info-label">Profissão</div>
                                            <div class="info-value">' . $dadosMembro['profissao'] . '</div>
                                        </div>
                                    </div>
                                    <div class="personal-info-cell">
                                        <div class="info-item">
                                            <div class="info-label">Telefone</div>
                                            <div class="info-value">' . $dadosMembro['telefone'] . '</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="personal-info-row">
                                    <div class="personal-info-cell" style="width: 100%;">
                                        <div class="info-item">
                                            <div class="info-label">Email</div>
                                            <div class="info-value">' . $dadosMembro['email'] . '</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Endereço -->
                    <div class="section">
                        <div class="section-header">
                            <h2>Endereço</h2>
                        </div>
                        <div class="section-content">
                            <div class="info-grid">
                                <div class="info-grid-row">
                                    <div class="info-grid-cell" style="width: 100%;">
                                        <div class="info-item">
                                            <div class="info-label">Endereço Completo</div>
                                            <div class="info-value">' . $dadosMembro['endereco'] . ', ' . $dadosMembro['bairro'] . '</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="info-grid-row">
                                    <div class="info-grid-cell">
                                        <div class="info-item">
                                            <div class="info-label">Cidade</div>
                                            <div class="info-value">' . $dadosMembro['cidade'] . '</div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-grid-cell">
                                        <div class="info-item">
                                            <div class="info-label">Estado</div>
                                            <div class="info-value">' . $dadosMembro['estado'] . '</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="info-grid-row">
                                    <div class="info-grid-cell">
                                        <div class="info-item">
                                            <div class="info-label">CEP</div>
                                            <div class="info-value">' . $dadosMembro['cep'] . '</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações da Igreja -->
                    <div class="section">
                        <div class="section-header">
                            <h2>Informações Eclesiásticas</h2>
                        </div>
                        <div class="section-content">
                            <div class="info-grid">
                                <div class="info-grid-row">
                                    <div class="info-grid-cell">
                                        <div class="info-item">
                                            <div class="info-label">Cargo Atual</div>
                                            <div class="info-value">' . $dadosMembro['cargo'] . '</div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-grid-cell">
                                        <div class="info-item">
                                            <div class="info-label">Departamento</div>
                                            <div class="info-value">' . $dadosMembro['departamento'] . '</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="info-grid-row">
                                    <div class="info-grid-cell">
                                        <div class="info-item">
                                            <div class="info-label">Ministério</div>
                                            <div class="info-value">' . $dadosMembro['ministerio'] . '</div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-grid-cell">
                                        <div class="info-item">
                                            <div class="info-label">Data de Validade</div>
                                            <div class="info-value">' . $dadosMembro['dataValidade'] . '</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Área de Assinatura -->
                    <div class="signature-area">
                        <div class="signature-line"></div>
                        <div class="signature-text">Assinatura do Pastor Responsável</div>
                    </div>
                </div>

                <!-- Rodapé -->
                <div class="footer">
                    <p><strong>' . $configuracoes['nome'] . '</strong></p>
                    <p>' . $configuracoes['endereco'] . ', ' . $configuracoes['cidade'] . '</p>
                    <p>Telefone: ' . $configuracoes['telefone'] . ' | Email: ' . $configuracoes['email'] . '</p>
                    <p>Documento gerado em: ' . now()->format('d/m/Y H:i:s') . '</p>
                </div>
            </div>
        </body>
        </html>';
    }



    /**
     * Ações em lote para usuários
     */
    public function bulkActionUsers(Request $request)
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

        return redirect()->route('admin.people.users.index')
            ->with('success', $message);
    }

    /**
     * Visualizar departamento
     */
    public function showDepartment(Departamento $departamento)
    {
        return view('admin.people.departments.show', compact('departamento'));
    }

    /**
     * Exportar departamento
     */
    public function exportDepartment(Departamento $departamento)
    {
        // Implementar exportação específica do departamento
        return redirect()->back()->with('info', 'Exportação do departamento em desenvolvimento');
    }

    /**
     * Exportar ministério
     */
    public function exportMinistry(Ministerio $ministerio)
    {
        // Implementar exportação específica do ministério
        return redirect()->back()->with('info', 'Exportação do ministério em desenvolvimento');
    }

    /**
     * Relatórios de membros
     */
    public function reportsMembers(Request $request)
    {
        // Implementar relatório específico de membros
        return redirect()->back()->with('info', 'Relatório de membros em desenvolvimento');
    }

    /**
     * Relatórios de ministérios
     */
    public function reportsMinistries(Request $request)
    {
        // Implementar relatório específico de ministérios
        return redirect()->back()->with('info', 'Relatório de ministérios em desenvolvimento');
    }

    /**
     * Relatórios de aniversariantes
     */
    public function reportsBirthdays(Request $request)
    {
        // Implementar relatório específico de aniversariantes
        return redirect()->back()->with('info', 'Relatório de aniversariantes em desenvolvimento');
    }

    /**
     * Relatórios de estatísticas
     */
    public function reportsStatistics(Request $request)
    {
        // Implementar relatório de estatísticas
        return redirect()->back()->with('info', 'Relatório de estatísticas em desenvolvimento');
    }

    /**
     * Relatórios rápidos
     */
    public function reportsQuick($tipo)
    {
        // Implementar relatórios rápidos
        return redirect()->back()->with('info', 'Relatório rápido em desenvolvimento');
    }

    /**
     * Relatório completo
     */
    public function reportsComplete()
    {
        // Implementar relatório completo
        return redirect()->back()->with('info', 'Relatório completo em desenvolvimento');
    }

    /**
     * Listar cargos
     */
    public function cargos()
    {
        $cargos = Cargo::with('departamento')->get();
        $departamentos = Departamento::all();
        
        return view('admin.people.cargos.index', compact('cargos', 'departamentos'));
    }

    /**
     * Criar cargo
     */
    public function createCargo(Request $request)
    {
        $departamentoId = $request->get('departamento_id');
        $departamentos = Departamento::all();
        
        return view('admin.people.cargos.create', compact('departamentos', 'departamentoId'));
    }

    /**
     * Salvar cargo
     */
    public function storeCargo(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'departamento_id' => 'required|exists:departamentos,id',
            'ativo' => 'boolean',
        ]);

        Cargo::create($request->all());

        return redirect()->back()->with('success', 'Cargo criado com sucesso!');
    }

    /**
     * Editar cargo
     */
    public function editCargo(Cargo $cargo)
    {
        $departamentos = Departamento::all();
        
        return view('admin.people.cargos.edit', compact('cargo', 'departamentos'));
    }

    /**
     * Atualizar cargo
     */
    public function updateCargo(Request $request, Cargo $cargo)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'departamento_id' => 'required|exists:departamentos,id',
            'ativo' => 'boolean',
        ]);

        $cargo->update($request->all());

        return redirect()->back()->with('success', 'Cargo atualizado com sucesso!');
    }

    /**
     * Excluir cargo
     */
    public function deleteCargo(Cargo $cargo)
    {
        $cargo->delete();

        return redirect()->back()->with('success', 'Cargo excluído com sucesso!');
    }

    /**
     * Página de importação de membros
     */
    public function importMembers()
    {
        return view('admin.people.members.import');
    }

    /**
     * Processar importação de membros
     */
    public function processImportMembers(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            // Aqui você implementaria a lógica de importação
            // Por exemplo, usando Laravel Excel
            
            return redirect()->route('admin.people.members.index')
                ->with('success', 'Membros importados com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao importar membros: ' . $e->getMessage());
        }
    }

    /**
     * Exportar usuários
     */
    public function exportUsers(Request $request)
    {
        try {
            return Excel::download(new UsuariosExport(), 'usuarios.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao exportar usuários: ' . $e->getMessage());
        }
    }

    /**
     * Exportar ministérios
     */
    public function exportMinistries(Request $request)
    {
        try {
            return Excel::download(new MinisteriosExport(), 'ministerios.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao exportar ministérios: ' . $e->getMessage());
        }
    }

    /**
     * Exportar membros
     */
    public function exportMembers(Request $request)
    {
        try {
            return Excel::download(new MembrosExport(), 'membros.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao exportar membros: ' . $e->getMessage());
        }
    }

    /**
     * Exportar todos os relatórios
     */
    public function exportAllReports(Request $request)
    {
        try {
            $formato = $request->get('formato', 'excel');
            
            if ($formato === 'excel') {
                // Aqui você pode criar uma exportação consolidada ou redirecionar para o método export com tipo específico
                return $this->exportReports($request);
            } else {
                return redirect()->back()
                    ->with('error', 'Formato não suportado');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao exportar relatórios: ' . $e->getMessage());
        }
    }

    /**
     * Ativar usuário
     */
    public function activateUser(User $user)
    {
        try {
            $user->update(['ativo' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'Usuário ativado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao ativar usuário: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desativar usuário
     */
    public function deactivateUser(User $user)
    {
        try {
            $user->update(['ativo' => false]);
            
            return response()->json([
                'success' => true,
                'message' => 'Usuário desativado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao desativar usuário: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar dados de CEP na base de dados local
     */
    public function buscarCep($cep)
    {
        try {
            // Limpar CEP (remover caracteres especiais)
            $cepLimpo = preg_replace('/[^0-9]/', '', $cep);
            
            if (strlen($cepLimpo) !== 8) {
                return response()->json([
                    'success' => false,
                    'message' => 'CEP deve conter 8 dígitos'
                ], 400);
            }
            
            // Buscar CEP na base de dados local
            $cepData = \App\Models\Cep::where('cep_inicial', '<=', $cepLimpo)
                ->where('cep_final', '>=', $cepLimpo)
                ->first();
            
            if ($cepData) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'cep' => $cep,
                        'logradouro' => $cepData->faixa_de_cep ?? '',
                        'bairro' => '',
                        'localidade' => $cepData->localidade,
                        'uf' => $cepData->uf,
                        'cidade' => $cepData->localidade,
                        'estado' => $cepData->uf,
                        'ibge' => $cepData->cod_ibge ?? '',
                        'latitude' => $cepData->latitude,
                        'longitude' => $cepData->longitude
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'CEP não encontrado na base de dados local'
                ], 404);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar CEP: ' . $e->getMessage()
            ], 500);
        }
    }
}