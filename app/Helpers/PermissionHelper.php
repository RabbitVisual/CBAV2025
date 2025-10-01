<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\{User, Role, Permission};

class PermissionHelper
{
    /**
     * Verificar se é membro (todo usuário autenticado é membro por padrão)
     */
    public static function isMembro()
    {
        $user = Auth::user();
        // Todo usuário autenticado é considerado membro por padrão
        return $user !== null;
    }

    /**
     * Verificar se tem acesso administrativo
     */
    public static function hasAdminAccess()
    {
        $user = Auth::user();
        if (!$user) return false;

        // Super admin
        if ($user->hasPermissionTo('admin master')) {
            return true;
        }

        // Verificar permissões de área
        $adminPermissions = [
            'people.access',
            'finance.access', 
            'system.access',
            'devotionals.access',
            'council.access',
            'ebd.access',
            'intercessor.access',
            'eventos.access',
            'chat.access'
        ];

        return $user->hasAnyPermission($adminPermissions);
    }

    /**
     * Verificar acesso ao dashboard admin
     */
    public static function canAccessAdminDashboard()
    {
        return self::hasAdminAccess();
    }

    /**
     * Verificar acesso à gestão de pessoas
     */
    public static function canAccessPeople()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('people.access');
    }

    /**
     * Verificar acesso à gestão financeira
     */
    public static function canAccessFinance()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('finance.access');
    }

    /**
     * Verificar acesso à gestão do sistema
     */
    public static function canAccessSystem()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('system.access');
    }

    /**
     * Verificar acesso às permissões
     */
    public static function canAccessPermissions()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('system.access');
    }

    /**
     * Verificar acesso aos devocionais
     */
    public static function canAccessDevotionals()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('devotionals.access');
    }

    /**
     * Verificar acesso ao conselho
     */
    public static function canAccessCouncil()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('council.access');
    }

    /**
     * Verificar acesso aos intercessores
     */
    public static function canAccessIntercessor()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('intercessor.access');
    }

    /**
     * Verificar acesso aos eventos
     */
    public static function canAccessEventos()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('eventos.access');
    }

    /**
     * Verificar acesso ao chat
     */
    public static function canAccessChat()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('chat.access');
    }

    /**
     * Verificar acesso para criar eventos
     */
    public static function canCreateEventos()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('eventos.create');
    }

    /**
     * Verificar acesso para editar eventos
     */
    public static function canEditEventos()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('eventos.edit');
    }

    /**
     * Verificar acesso para excluir eventos
     */
    public static function canDeleteEventos()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('eventos.delete');
    }

    /**
     * Verificar acesso à EBD
     */
    public static function canAccessEbd()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('ebd.access');
    }

    /**
     * Verificar acesso às turmas EBD
     */
    public static function canAccessEbdTurmas()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('ebd.turmas.access');
    }

    /**
     * Verificar acesso aos professores EBD
     */
    public static function canAccessEbdProfessores()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('ebd.professores.access');
    }

    /**
     * Verificar acesso aos alunos EBD
     */
    public static function canAccessEbdAlunos()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('ebd.alunos.access');
    }

    /**
     * Verificar acesso às lições EBD
     */
    public static function canAccessEbdLicoes()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('ebd.licoes.access');
    }

    /**
     * Verificar acesso às aulas EBD
     */
    public static function canAccessEbdAulas()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('ebd.aulas.access');
    }

    /**
     * Verificar acesso às avaliações EBD
     */
    public static function canAccessEbdAvaliacoes()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('ebd.avaliacoes.access');
    }

    /**
     * Verificar acesso aos relatórios EBD
     */
    public static function canAccessEbdRelatorios()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('ebd.relatorios.access');
    }

    /**
     * Verificar acesso ao quiz bíblico EBD
     */
    public static function canAccessEbdQuiz()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('ebd.access');
    }

    /**
     * Verificar permissões específicas de membros
     */
    public static function canAccessMembers()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('members.access');
    }

    public static function canCreateMembers()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('members.create');
    }

    public static function canEditMembers()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('members.edit');
    }

    public static function canDeleteMembers()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('members.delete');
    }

    /**
     * Verificar permissões específicas de usuários
     */
    public static function canAccessUsers()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('users.access');
    }

    public static function canCreateUsers()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('users.create');
    }

    public static function canEditUsers()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('users.edit');
    }

    public static function canDeleteUsers()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('users.delete');
    }

    /**
     * Verificar permissões específicas de ministérios
     */
    public static function canAccessMinistries()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('ministries.access');
    }

    public static function canCreateMinistries()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('ministries.create');
    }

    public static function canEditMinistries()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('ministries.edit');
    }

    public static function canDeleteMinistries()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('ministries.delete');
    }

    /**
     * Verificar permissões específicas de departamentos
     */
    public static function canAccessDepartments()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('departments.access');
    }

    public static function canCreateDepartments()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('departments.create');
    }

    public static function canEditDepartments()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('departments.edit');
    }

    public static function canDeleteDepartments()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('departments.delete');
    }

    /**
     * Verificar permissões específicas de transações
     */
    public static function canAccessTransactions()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('transactions.access');
    }

    public static function canCreateTransactions()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('transactions.create');
    }

    public static function canEditTransactions()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('transactions.edit');
    }

    public static function canDeleteTransactions()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('transactions.delete');
    }

    /**
     * Verificar permissões específicas de campanhas
     */
    public static function canAccessCampaigns()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('campaigns.access');
    }

    public static function canCreateCampaigns()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('campaigns.create');
    }

    public static function canEditCampaigns()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('campaigns.edit');
    }

    public static function canDeleteCampaigns()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('campaigns.delete');
    }

    /**
     * Verificar permissões específicas de relatórios
     */
    public static function canAccessReports()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('reports.access');
    }

    public static function canExportReports()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('reports.export');
    }

    /**
     * Verificar permissões específicas de configurações
     */
    public static function canAccessSettings()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('settings.access');
    }

    public static function canEditSettings()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('settings.edit');
    }

    /**
     * Verificar permissões específicas de notificações
     */
    public static function canAccessNotifications()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('notifications.access');
    }

    public static function canCreateNotifications()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('notifications.create');
    }

    public static function canEditNotifications()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('notifications.edit');
    }

    /**
     * Verificar permissões específicas de logs
     */
    public static function canAccessLogs()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('logs.access');
    }

    public static function canEditLogs()
    {
        $user = Auth::user();
        return $user && $user->hasPermissionTo('logs.edit');
    }

    /**
     * Obter caminho de redirecionamento padrão
     */
    public static function getDefaultRedirectPath()
    {
        $user = Auth::user();
        if (!$user) return '/login';

        if (self::hasAdminAccess()) {
            return '/admin';
        }

        return '/member';
    }

    /**
     * Obter seções disponíveis para o usuário
     */
    public static function getAvailableSections()
    {
        $user = Auth::user();
        if (!$user) return [];

        $sections = [];

        // Área de membros - sempre disponível
        $sections['member'] = [
            'name' => 'Área do Membro',
            'icon' => 'fas fa-user',
            'color' => 'blue',
            'items' => [
                'dashboard' => [
                    'name' => 'Dashboard',
                    'route' => 'member.dashboard',
                    'icon' => 'fas fa-tachometer-alt'
                ],
                'profile' => [
                    'name' => 'Meu Perfil',
                    'route' => 'member.profile.index',
                    'icon' => 'fas fa-user-edit'
                ],
                'donations' => [
                    'name' => 'Minhas Doações',
                    'route' => 'member.donations.index',
                    'icon' => 'fas fa-hand-holding-heart'
                ],
                'ministries' => [
                    'name' => 'Ministérios',
                    'route' => 'member.ministries.index',
                    'icon' => 'fas fa-church'
                ],
                'bible' => [
                    'name' => 'Bíblia',
                    'route' => 'member.bible.index',
                    'icon' => 'fas fa-book-bible'
                ]
            ]
        ];

        // Área administrativa - apenas se tiver acesso
        if (self::hasAdminAccess()) {
            $sections['admin'] = [
                'name' => 'Administração',
                'icon' => 'fas fa-cogs',
                'color' => 'purple',
                'items' => [
                    'dashboard' => [
                        'name' => 'Dashboard',
                        'route' => 'admin.dashboard',
                        'icon' => 'fas fa-tachometer-alt'
                    ]
                ]
            ];

            // Gestão de Pessoas
            if (self::canAccessPeople()) {
                $sections['admin']['items']['people'] = [
                    'name' => 'Gestão de Pessoas',
                    'route' => 'admin.people.index',
                    'icon' => 'fas fa-users',
                    'subitems' => [
                        'members' => [
                            'name' => 'Membros',
                            'route' => 'admin.people.members.index',
                            'icon' => 'fas fa-user-friends'
                        ],
                        'users' => [
                            'name' => 'Usuários',
                            'route' => 'admin.people.users.index',
                            'icon' => 'fas fa-user-cog'
                        ],
                        'ministries' => [
                            'name' => 'Ministérios',
                            'route' => 'admin.people.ministries.index',
                            'icon' => 'fas fa-church'
                        ],
                        'departments' => [
                            'name' => 'Departamentos',
                            'route' => 'admin.people.departments.index',
                            'icon' => 'fas fa-sitemap'
                        ],
                        'birthdays' => [
                            'name' => 'Aniversariantes',
                            'route' => 'admin.people.birthdays.index',
                            'icon' => 'fas fa-birthday-cake'
                        ]
                    ]
                ];
            }

            // Gestão Financeira
            if (self::canAccessFinance()) {
                $sections['admin']['items']['finance'] = [
                    'name' => 'Gestão Financeira',
                    'route' => 'admin.finance.index',
                    'icon' => 'fas fa-dollar-sign',
                    'subitems' => [
                        'transactions' => [
                            'name' => 'Transações',
                            'route' => 'admin.finance.transactions.index',
                            'icon' => 'fas fa-exchange-alt'
                        ],
                        'campaigns' => [
                            'name' => 'Campanhas',
                            'route' => 'admin.finance.campaigns.index',
                            'icon' => 'fas fa-bullhorn'
                        ],
                        'reports' => [
                            'name' => 'Relatórios',
                            'route' => 'admin.finance.reports.index',
                            'icon' => 'fas fa-chart-bar'
                        ],
                        'settings' => [
                            'name' => 'Configurações',
                            'route' => 'admin.finance.settings.index',
                            'icon' => 'fas fa-cog'
                        ]
                    ]
                ];
            }

            // Gestão do Sistema
            if (self::canAccessSystem()) {
                $sections['admin']['items']['system'] = [
                    'name' => 'Gestão do Sistema',
                    'route' => 'admin.system.index',
                    'icon' => 'fas fa-server',
                    'subitems' => [
                        'settings' => [
                            'name' => 'Configurações',
                            'route' => 'admin.system.settings.index',
                            'icon' => 'fas fa-cogs'
                        ],
                        'notifications' => [
                            'name' => 'Notificações',
                            'route' => 'admin.system.notifications.index',
                            'icon' => 'fas fa-bell'
                        ],
                        'logs' => [
                            'name' => 'Logs',
                            'route' => 'admin.system.logs.index',
                            'icon' => 'fas fa-file-alt'
                        ],
                        'maintenance' => [
                            'name' => 'Manutenção',
                            'route' => 'admin.system.maintenance.index',
                            'icon' => 'fas fa-tools'
                        ]
                    ]
                ];
            }

            // Devocionais
            if (self::canAccessDevotionals()) {
                $sections['admin']['items']['devotionals'] = [
                    'name' => 'Devocionais',
                    'route' => 'admin.devotionals.index',
                    'icon' => 'fas fa-book-open'
                ];
            }

            // Conselho
            if (self::canAccessCouncil()) {
                $sections['admin']['items']['council'] = [
                    'name' => 'Conselho',
                    'route' => 'admin.council.index',
                    'icon' => 'fas fa-gavel'
                ];
            }
        }

        return $sections;
    }

    /**
     * Verificar se uma seção está disponível
     */
    public static function isSectionAvailable($section)
    {
        $sections = self::getAvailableSections();
        return isset($sections[$section]);
    }

    /**
     * Verificar se um item está disponível
     */
    public static function isItemAvailable($section, $item = null)
    {
        $sections = self::getAvailableSections();
        
        if (!isset($sections[$section])) {
            return false;
        }

        if ($item === null) {
            return true;
        }

        return isset($sections[$section]['items'][$item]);
    }

    /**
     * Obter roles do usuário
     */
    public static function getUserRoles($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return collect();
        }

        return $user->roles;
    }

    /**
     * Verificar se tem roles ministeriais
     */
    public static function hasMinisterialRoles($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return false;
        }

        $ministerialRoles = ['Pastor', 'Líder', 'Coordenador'];
        return $user->hasAnyRole($ministerialRoles);
    }

    /**
     * Verificar se tem roles de sistema
     */
    public static function hasSystemRoles($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return false;
        }

        $systemRoles = ['Admin', 'Super Admin'];
        return $user->hasAnyRole($systemRoles);
    }

    /**
     * Obter role principal do usuário
     */
    public static function getPrimaryRole($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return null;
        }

        return $user->roles->first();
    }

    /**
     * Obter descrição amigável de uma permissão
     */
    public static function getPermissionDescription($permissionName)
    {
        $descriptions = [
            // Pessoas
            'people.access' => 'Acesso geral à área de pessoas',
            'people.members' => 'Visualizar lista de membros',
            'people.users' => 'Gerenciar usuários do sistema',
            'people.ministries' => 'Gerenciar ministérios',
            'people.departments' => 'Gerenciar departamentos',
            
            'members.access' => 'Acesso à área de membros',
            'members.create' => 'Cadastrar novos membros',
            'members.edit' => 'Editar dados de membros',
            'members.delete' => 'Excluir membros',
            
            'users.access' => 'Acesso à área de usuários',
            'users.create' => 'Criar novos usuários',
            'users.edit' => 'Editar usuários',
            'users.delete' => 'Excluir usuários',
            
            'ministries.access' => 'Acesso à área de ministérios',
            'ministries.create' => 'Criar novos ministérios',
            'ministries.edit' => 'Editar ministérios',
            'ministries.delete' => 'Excluir ministérios',
            
            'departments.access' => 'Acesso à área de departamentos',
            'departments.create' => 'Criar novos departamentos',
            'departments.edit' => 'Editar departamentos',
            'departments.delete' => 'Excluir departamentos',
            
            // Financeiro
            'finance.access' => 'Acesso à área financeira',
            'finance.transactions' => 'Gerenciar transações',
            'finance.campaigns' => 'Gerenciar campanhas',
            'finance.reports' => 'Visualizar relatórios financeiros',
            
            'transactions.access' => 'Acesso às transações',
            'transactions.create' => 'Registrar transações',
            'transactions.edit' => 'Editar transações',
            'transactions.delete' => 'Excluir transações',
            
            'campaigns.access' => 'Acesso às campanhas',
            'campaigns.create' => 'Criar campanhas',
            'campaigns.edit' => 'Editar campanhas',
            'campaigns.delete' => 'Excluir campanhas',
            
            // EBD
            'ebd.access' => 'Acesso à área da EBD',
            'ebd.turmas' => 'Gerenciar turmas',
            'ebd.professores' => 'Gerenciar professores',
            'ebd.alunos' => 'Gerenciar alunos',
            'ebd.licoes' => 'Gerenciar lições',
            'ebd.aulas' => 'Gerenciar aulas',
            'ebd.avaliacoes' => 'Gerenciar avaliações',
            'ebd.relatorios' => 'Visualizar relatórios da EBD',
            
            'ebd.professores.access' => 'Acesso aos professores',
            'ebd.alunos.access' => 'Acesso aos alunos',
            'ebd.licoes.access' => 'Acesso às lições',
            'ebd.aulas.access' => 'Acesso às aulas',
            'ebd.avaliacoes.access' => 'Acesso às avaliações',
            'ebd.relatorios.access' => 'Acesso aos relatórios',
            
            // Devocionais
            'devotionals.access' => 'Acesso aos devocionais',
            'devotionals.create' => 'Criar devocionais',
            'devotionals.edit' => 'Editar devocionais',
            'devotionals.delete' => 'Excluir devocionais',
            
            // Conselho
            'council.access' => 'Acesso ao conselho',
            'council.meetings' => 'Gerenciar reuniões',
            'council.voting' => 'Participar de votações',
            'council.agenda' => 'Gerenciar agenda',
            
            // Intercessão
            'intercessor.access' => 'Acesso à área de intercessão',
            'intercessor.dashboard' => 'Visualizar dashboard de intercessor',
            'intercessor.view_pedidos' => 'Visualizar pedidos de oração',
            'intercessor.registrar_intercessao' => 'Registrar intercessões',
            'intercessor.atualizar_status' => 'Atualizar status dos pedidos',
            'intercessor.view_relatorios' => 'Visualizar relatórios',
            
            // Notificações
            'notifications.access' => 'Acesso às notificações',
            'notifications.create' => 'Criar notificações',
            'notifications.edit' => 'Editar notificações',
            'notifications.delete' => 'Excluir notificações',
            
            // Sistema
            'system.access' => 'Acesso ao sistema',
            'system.settings' => 'Configurações do sistema',
            'system.logs' => 'Visualizar logs',
            'system.backup' => 'Realizar backup',
            
            'settings.access' => 'Acesso às configurações',
            'settings.edit' => 'Editar configurações',
            
            'logs.access' => 'Acesso aos logs',
            'logs.edit' => 'Editar logs',
            
            // Relatórios
            'reports.access' => 'Acesso aos relatórios',
            'reports.export' => 'Exportar relatórios',
        ];
        
        return $descriptions[$permissionName] ?? 'Permissão do sistema';
    }
    
    /**
     * Obter categoria de uma permissão
     */
    public static function getPermissionCategory($permissionName)
    {
        if (str_contains($permissionName, 'people.') || str_contains($permissionName, 'members.') || 
            str_contains($permissionName, 'users.') || str_contains($permissionName, 'ministries.') || 
            str_contains($permissionName, 'departments.')) {
            return 'pessoas';
        }
        
        if (str_contains($permissionName, 'finance.') || str_contains($permissionName, 'transactions.') || 
            str_contains($permissionName, 'campaigns.')) {
            return 'financeiro';
        }
        
        if (str_contains($permissionName, 'ebd.')) {
            return 'ebd';
        }
        
        if (str_contains($permissionName, 'devotionals.')) {
            return 'devocionais';
        }
        
        if (str_contains($permissionName, 'council.')) {
            return 'conselho';
        }
        
        if (str_contains($permissionName, 'intercessor.')) {
            return 'intercessao';
        }
        
        if (str_contains($permissionName, 'notifications.')) {
            return 'notificacoes';
        }
        
        if (str_contains($permissionName, 'system.') || str_contains($permissionName, 'settings.') || 
            str_contains($permissionName, 'logs.')) {
            return 'sistema';
        }
        
        if (str_contains($permissionName, 'reports.')) {
            return 'relatorios';
        }
        
        return 'outros';
    }
    
    /**
     * Obter ícone de uma categoria
     */
    public static function getCategoryIcon($category)
    {
        $icons = [
            'pessoas' => '👥',
            'financeiro' => '💰',
            'ebd' => '📚',
            'devocionais' => '📖',
            'conselho' => '🏛️',
            'intercessao' => '🙏',
            'notificacoes' => '🔔',
            'sistema' => '⚙️',
            'relatorios' => '📊',
            'outros' => '🔧'
        ];
        
        return $icons[$category] ?? '🔧';
    }
    
    /**
     * Obter cor de uma categoria
     */
    public static function getCategoryColor($category)
    {
        $colors = [
            'pessoas' => 'blue',
            'financeiro' => 'green',
            'ebd' => 'purple',
            'devocionais' => 'indigo',
            'conselho' => 'yellow',
            'intercessao' => 'pink',
            'notificacoes' => 'orange',
            'sistema' => 'gray',
            'relatorios' => 'teal',
            'outros' => 'gray'
        ];
        
        return $colors[$category] ?? 'gray';
    }
    
    /**
     * Obter nome de exibição amigável de uma permissão
     */
    public static function getPermissionDisplayName($permissionName)
    {
        $displayNames = [
            // Pessoas
            'people.access' => 'Acesso a Pessoas',
            'people.members' => 'Visualizar Membros',
            'people.users' => 'Gerenciar Usuários',
            'people.ministries' => 'Gerenciar Ministérios',
            'people.departments' => 'Gerenciar Departamentos',
            
            'members.access' => 'Acesso a Membros',
            'members.create' => 'Cadastrar Membros',
            'members.edit' => 'Editar Membros',
            'members.delete' => 'Excluir Membros',
            
            'users.access' => 'Acesso a Usuários',
            'users.create' => 'Criar Usuários',
            'users.edit' => 'Editar Usuários',
            'users.delete' => 'Excluir Usuários',
            
            'ministries.access' => 'Acesso a Ministérios',
            'ministries.create' => 'Criar Ministérios',
            'ministries.edit' => 'Editar Ministérios',
            'ministries.delete' => 'Excluir Ministérios',
            
            'departments.access' => 'Acesso a Departamentos',
            'departments.create' => 'Criar Departamentos',
            'departments.edit' => 'Editar Departamentos',
            'departments.delete' => 'Excluir Departamentos',
            
            // Financeiro
            'finance.access' => 'Acesso Financeiro',
            'finance.transactions' => 'Gerenciar Transações',
            'finance.campaigns' => 'Gerenciar Campanhas',
            'finance.reports' => 'Relatórios Financeiros',
            
            'transactions.access' => 'Acesso a Transações',
            'transactions.create' => 'Registrar Transações',
            'transactions.edit' => 'Editar Transações',
            'transactions.delete' => 'Excluir Transações',
            
            'campaigns.access' => 'Acesso a Campanhas',
            'campaigns.create' => 'Criar Campanhas',
            'campaigns.edit' => 'Editar Campanhas',
            'campaigns.delete' => 'Excluir Campanhas',
            
            // EBD
            'ebd.access' => 'Acesso à EBD',
            'ebd.turmas' => 'Gerenciar Turmas',
            'ebd.professores' => 'Gerenciar Professores',
            'ebd.alunos' => 'Gerenciar Alunos',
            'ebd.licoes' => 'Gerenciar Lições',
            'ebd.aulas' => 'Gerenciar Aulas',
            'ebd.avaliacoes' => 'Gerenciar Avaliações',
            'ebd.relatorios' => 'Relatórios da EBD',
            
            'ebd.professores.access' => 'Acesso a Professores',
            'ebd.alunos.access' => 'Acesso a Alunos',
            'ebd.licoes.access' => 'Acesso a Lições',
            'ebd.aulas.access' => 'Acesso a Aulas',
            'ebd.avaliacoes.access' => 'Acesso a Avaliações',
            'ebd.relatorios.access' => 'Acesso a Relatórios',
            
            // Devocionais
            'devotionals.access' => 'Acesso a Devocionais',
            'devotionals.create' => 'Criar Devocionais',
            'devotionals.edit' => 'Editar Devocionais',
            'devotionals.delete' => 'Excluir Devocionais',
            
            // Conselho
            'council.access' => 'Acesso ao Conselho',
            'council.meetings' => 'Gerenciar Reuniões',
            'council.voting' => 'Participar de Votações',
            'council.agenda' => 'Gerenciar Agenda',
            
            // Intercessão
            'intercessor.access' => 'Acesso à Intercessão',
            'intercessor.dashboard' => 'Dashboard de Intercessor',
            'intercessor.view_pedidos' => 'Visualizar Pedidos',
            'intercessor.registrar_intercessao' => 'Registrar Intercessões',
            'intercessor.atualizar_status' => 'Atualizar Status',
            'intercessor.view_relatorios' => 'Visualizar Relatórios',
            
            // Notificações
            'notifications.access' => 'Acesso a Notificações',
            'notifications.create' => 'Criar Notificações',
            'notifications.edit' => 'Editar Notificações',
            'notifications.delete' => 'Excluir Notificações',
            
            // Sistema
            'system.access' => 'Acesso ao Sistema',
            'system.settings' => 'Configurações do Sistema',
            'system.logs' => 'Visualizar Logs',
            'system.backup' => 'Realizar Backup',
            
            'settings.access' => 'Acesso às Configurações',
            'settings.edit' => 'Editar Configurações',
            
            'logs.access' => 'Acesso aos Logs',
            'logs.edit' => 'Editar Logs',
            
            // Relatórios
            'reports.access' => 'Acesso a Relatórios',
            'reports.export' => 'Exportar Relatórios',
        ];
        
        return $displayNames[$permissionName] ?? $permissionName;
    }
} 