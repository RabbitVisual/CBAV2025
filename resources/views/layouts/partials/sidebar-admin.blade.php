@php
    use App\Helpers\PermissionHelper;

    // Determinar seção ativa baseada na URL atual
    $currentPath = request()->path();
    $currentSection = '';
    $currentSubsection = '';

    // Mapear URLs para seções de forma mais precisa
    if ($currentPath === 'admin/dashboard' || $currentPath === 'admin') {
        $currentSection = 'dashboard';
    } elseif (str_starts_with($currentPath, 'admin/people')) {
        $currentSection = 'people';
        if (str_contains($currentPath, 'members')) {
            $currentSubsection = 'members';
        } elseif (str_contains($currentPath, 'users')) {
            $currentSubsection = 'users';
        } elseif (str_contains($currentPath, 'ministries')) {
            $currentSubsection = 'ministries';
        } elseif (str_contains($currentPath, 'departments')) {
            $currentSubsection = 'departments';
        } elseif (str_contains($currentPath, 'cargos')) {
            $currentSubsection = 'cargos';
        } elseif (str_contains($currentPath, 'birthdays')) {
            $currentSubsection = 'birthdays';
        }
    } elseif (str_starts_with($currentPath, 'admin/ceps')) {
        $currentSection = 'people';
        $currentSubsection = 'ceps';
    } elseif (str_starts_with($currentPath, 'admin/ebd')) {
        $currentSection = 'ebd';
        if (str_contains($currentPath, 'turmas')) {
            $currentSubsection = 'turmas';
        } elseif (str_contains($currentPath, 'professores')) {
            $currentSubsection = 'professores';
        } elseif (str_contains($currentPath, 'alunos')) {
            $currentSubsection = 'alunos';
        } elseif (str_contains($currentPath, 'licoes')) {
            $currentSubsection = 'licoes';
        } elseif (str_contains($currentPath, 'aulas')) {
            $currentSubsection = 'aulas';
        } elseif (str_contains($currentPath, 'avaliacoes')) {
            $currentSubsection = 'avaliacoes';
        } elseif (str_contains($currentPath, 'questoes')) {
            $currentSubsection = 'questoes';
        } elseif (str_contains($currentPath, 'certificados')) {
            $currentSubsection = 'certificados';
        } elseif (str_contains($currentPath, 'relatorios')) {
            $currentSubsection = 'relatorios';
        }
    } elseif (str_starts_with($currentPath, 'admin/quiz')) {
        $currentSection = 'quiz';
        if (str_contains($currentPath, 'statistics')) {
            $currentSubsection = 'quiz-stats';
        } elseif (str_contains($currentPath, 'settings')) {
            $currentSubsection = 'quiz-settings';
        }
    } elseif (str_starts_with($currentPath, 'admin/intercessor')) {
        $currentSection = 'intercessor';
        if (str_contains($currentPath, 'pedidos')) {
            $currentSubsection = 'pedidos';
        }
    } elseif (str_starts_with($currentPath, 'admin/eventos')) {
        $currentSection = 'eventos';
        if (str_contains($currentPath, 'create')) {
            $currentSubsection = 'create';
        }
    } elseif (str_starts_with($currentPath, 'admin/financeiro')) {
        $currentSection = 'financeiro';
        if (str_contains($currentPath, 'transacoes')) {
            $currentSubsection = 'transacoes';
        } elseif (str_contains($currentPath, 'campanhas')) {
            $currentSubsection = 'campanhas';
        } elseif (str_contains($currentPath, 'relatorios')) {
            $currentSubsection = 'relatorios';
        } elseif (str_contains($currentPath, 'documentos-baixa')) {
            $currentSubsection = 'documentos-baixa';
        } elseif (str_contains($currentPath, 'declaracao-anual')) {
            $currentSubsection = 'declaracao-anual';
        }
    } elseif (str_starts_with($currentPath, 'admin/sistema')) {
        $currentSection = 'sistema';
        if (str_contains($currentPath, 'configuracoes')) {
            $currentSubsection = 'configuracoes';
        } elseif (str_contains($currentPath, 'config-home')) {
            $currentSubsection = 'config-home';
        } elseif (str_contains($currentPath, 'chat')) {
            $currentSubsection = 'chat';
        } elseif (str_contains($currentPath, 'notificacoes')) {
            $currentSubsection = 'notificacoes';
        } elseif (str_contains($currentPath, 'logs')) {
            $currentSubsection = 'logs';
        } elseif (str_contains($currentPath, 'permissoes')) {
            $currentSubsection = 'permissoes';
        } elseif (str_contains($currentPath, 'devocionais')) {
            $currentSubsection = 'devocionais';
        }
    } elseif (str_starts_with($currentPath, 'admin/conselho')) {
        $currentSection = 'conselho';
        if (str_contains($currentPath, 'reunioes')) {
            $currentSubsection = 'reunioes';
        } elseif (str_contains($currentPath, 'votacoes')) {
            $currentSubsection = 'votacoes';
        } elseif (str_contains($currentPath, 'templates')) {
            $currentSubsection = 'templates';
        } elseif (str_contains($currentPath, 'configuracoes')) {
            $currentSubsection = 'configuracoes';
        } elseif (str_contains($currentPath, 'nova-reuniao')) {
            $currentSubsection = 'nova-reuniao';
        } elseif (str_contains($currentPath, 'area-membros')) {
            $currentSubsection = 'area-membros';
        }
    }
@endphp

<!-- Sidebar Modular -->
<div x-data="{
    expandedSections: {
        dashboard: {{ $currentSection === 'dashboard' ? 'true' : 'false' }},
        people: {{ $currentSection === 'people' ? 'true' : 'false' }},
        ebd: {{ $currentSection === 'ebd' ? 'true' : 'false' }},
        quiz: {{ $currentSection === 'quiz' ? 'true' : 'false' }},
        intercessor: {{ $currentSection === 'intercessor' ? 'true' : 'false' }},
        eventos: {{ $currentSection === 'eventos' ? 'true' : 'false' }},
        financeiro: {{ $currentSection === 'financeiro' ? 'true' : 'false' }},
        sistema: {{ $currentSection === 'sistema' ? 'true' : 'false' }},
        conselho: {{ $currentSection === 'conselho' ? 'true' : 'false' }}
    },
    currentSection: '{{ $currentSection }}',
    currentSubsection: '{{ $currentSubsection }}',
    
    // Função para salvar estado no localStorage
    saveExpandedState() {
        localStorage.setItem('sidebarExpandedSections', JSON.stringify(this.expandedSections));
    },
    
    // Função para carregar estado do localStorage
    loadExpandedState() {
        const saved = localStorage.getItem('sidebarExpandedSections');
        if (saved) {
            try {
                const savedState = JSON.parse(saved);
                Object.assign(this.expandedSections, savedState);
            } catch (e) {
                console.log('Erro ao carregar estado do sidebar:', e);
            }
        }
    },
    
    // Função para alternar seção
    toggleSection(section) {
        this.expandedSections[section] = !this.expandedSections[section];
        this.saveExpandedState();
    }
}" 
x-init="
    // Carregar estado salvo
    loadExpandedState();
    
    // Garantir que a seção atual esteja sempre expandida
    if (currentSection) {
        expandedSections[currentSection] = true;
        saveExpandedState();
    }
" 
class="space-y-2 transition-all duration-300 ease-in-out"

    <!-- Dashboard Principal -->
    <x-admin.sidebar.dashboard :currentSection="$currentSection" x-bind:sidebarCollapsed="sidebarCollapsed" />

    <!-- Gestão de Pessoas -->
    <x-admin.sidebar.people :currentSection="$currentSection" :currentSubsection="$currentSubsection" x-bind:sidebarCollapsed="sidebarCollapsed" />

    <!-- Escola Bíblica -->
    <x-admin.sidebar.ebd :currentSection="$currentSection" :currentSubsection="$currentSubsection" x-bind:sidebarCollapsed="sidebarCollapsed" />

    <!-- Quiz Bíblico -->
    <x-admin.sidebar.quiz :currentSection="$currentSection" :currentSubsection="$currentSubsection" x-bind:sidebarCollapsed="sidebarCollapsed" />

    <!-- Intercessor -->
    <x-admin.sidebar.intercessor :currentSection="$currentSection" :currentSubsection="$currentSubsection" x-bind:sidebarCollapsed="sidebarCollapsed" />

    <!-- Eventos -->
    <x-admin.sidebar.events :currentSection="$currentSection" :currentSubsection="$currentSubsection" x-bind:sidebarCollapsed="sidebarCollapsed" />

    <!-- Gestão Financeira -->
    <x-admin.sidebar.financial :currentSection="$currentSection" :currentSubsection="$currentSubsection" x-bind:sidebarCollapsed="sidebarCollapsed" />

    <!-- Gestão do Sistema -->
    <x-admin.sidebar.system :currentSection="$currentSection" :currentSubsection="$currentSubsection" x-bind:sidebarCollapsed="sidebarCollapsed" />

    <!-- Conselho da Igreja -->
    <x-admin.sidebar.council :currentSection="$currentSection" :currentSubsection="$currentSubsection" x-bind:sidebarCollapsed="sidebarCollapsed" />

    <!-- Área de Membros (Acessível a todos os usuários) -->
    @if (Auth::check())
        <div class="sidebar-section">
            <a href="{{ route('member.dashboard') }}"
                class="sidebar-item {{ $currentSection === 'area-membros' ? 'active' : '' }}">
                <i class="sidebar-icon fas fa-users"></i>
                <span class="sidebar-text">Área de Membros</span>
                @if ($currentSection === 'area-membros')
                    <div class="sidebar-indicator"></div>
                @endif
            </a>
        </div>
    @endif

    <!-- Meu Perfil -->
    @if (Auth::check())
        <div class="sidebar-section">
            <a href="{{ route('admin.profile.index') }}"
                class="sidebar-item {{ $currentSection === 'perfil' ? 'active' : '' }}">
                <i class="sidebar-icon fas fa-user-circle"></i>
                <span class="sidebar-text">Meu Perfil</span>
                @if ($currentSection === 'perfil')
                    <div class="sidebar-indicator"></div>
                @endif
            </a>
        </div>
    @endif

</div>
