@php
use App\Helpers\PermissionHelper;
@endphp

@if(PermissionHelper::hasAdminAccess())
    <div class="bg-white rounded-lg shadow-md p-6 mb-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-cog mr-2 text-blue-600"></i>
            Acesso Administrativo
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @if(PermissionHelper::canAccessAdminDashboard())
                <a href="{{ route('dashboard') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-tachometer-alt text-xl mr-3"></i>
                    <div>
                        <div class="font-semibold">Dashboard Admin</div>
                        <div class="text-sm opacity-90">Painel Principal</div>
                    </div>
                </a>
            @endif

            @if(PermissionHelper::canAccessPeople())
                <a href="{{ route('admin.people.index') }}" class="flex items-center p-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-users text-xl mr-3"></i>
                    <div>
                        <div class="font-semibold">Gestão de Pessoas</div>
                        <div class="text-sm opacity-90">Membros e Usuários</div>
                    </div>
                </a>
            @endif

            @if(PermissionHelper::canAccessFinance())
                <a href="{{ route('admin.finance.index') }}" class="flex items-center p-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-chart-line text-xl mr-3"></i>
                    <div>
                        <div class="font-semibold">Gestão Financeira</div>
                        <div class="text-sm opacity-90">Transações e Campanhas</div>
                    </div>
                </a>
            @endif

            @if(PermissionHelper::canAccessSystem())
                <a href="{{ route('admin.system.index') }}" class="flex items-center p-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-cogs text-xl mr-3"></i>
                    <div>
                        <div class="font-semibold">Gestão do Sistema</div>
                        <div class="text-sm opacity-90">Configurações</div>
                    </div>
                </a>
            @endif

            @if(PermissionHelper::canAccessDevocionais())
                <a href="{{ route('admin.devocionais.index') }}" class="flex items-center p-4 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-book-open text-xl mr-3"></i>
                    <div>
                        <div class="font-semibold">Devocionais</div>
                        <div class="text-sm opacity-90">Gerenciar Devocionais</div>
                    </div>
                </a>
            @endif

            @if(PermissionHelper::canAccessNotifications())
                <a href="{{ route('admin.system.notifications.index') }}" class="flex items-center p-4 bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-lg hover:from-pink-600 hover:to-pink-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-bell text-xl mr-3"></i>
                    <div>
                        <div class="font-semibold">Notificações</div>
                        <div class="text-sm opacity-90">Gerenciar Notificações</div>
                    </div>
                </a>
            @endif
        </div>

        @php $userRoles = PermissionHelper::getUserRoles(); @endphp
        @if(!empty($userRoles['sistema']) || !empty($userRoles['ministeriais']))
            <div class="mt-6 pt-4 border-t border-gray-200">
                <h4 class="text-sm font-medium text-gray-600 mb-3">Seus Cargos:</h4>
                <div class="flex flex-wrap gap-2">
                    @if(!empty($userRoles['sistema']))
                        @foreach($userRoles['sistema'] as $role)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-shield-alt mr-1"></i>
                                {{ $role }}
                            </span>
                        @endforeach
                    @endif
                    @if(!empty($userRoles['ministeriais']))
                        @foreach($userRoles['ministeriais'] as $cargo)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-user-tie mr-1"></i>
                                {{ $cargo['nome'] }} - {{ $cargo['ministerio'] }}
                            </span>
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
    </div>
@endif 