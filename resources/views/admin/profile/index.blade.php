@extends('layouts.admin')

@section('title', 'Meu Perfil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Meu Perfil</h1>
        <p class="text-gray-600 mt-2">Gerencie suas informações pessoais e configurações da conta</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações do Perfil -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Informações Pessoais</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="flex-shrink-0">
                            @if($user->foto && Storage::disk('public')->exists($user->foto))
                                <img class="h-20 w-20 rounded-full object-cover" 
                                     src="{{ Storage::url($user->foto) }}?v={{ md5($user->foto . $user->updated_at) }}" 
                                     alt="Foto do perfil">
                            @elseif($membro && $membro->foto && Storage::disk('public')->exists($membro->foto))
                                <img class="h-20 w-20 rounded-full object-cover" 
                                     src="{{ Storage::url($membro->foto) }}?v={{ md5($membro->foto . $membro->updated_at) }}" 
                                     alt="Foto do perfil">
                            @else
                                <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-blue-600">
                                        {{ $user->iniciais }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            @if($membro)
                                <p class="text-sm text-gray-500">{{ $membro->telefone ?? 'Telefone não informado' }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nome Completo</label>
                            <p class="text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>
                        @if($membro)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                                <p class="text-gray-900">{{ $membro->telefone ?? 'Não informado' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Endereço</label>
                                <p class="text-gray-900">{{ $membro->endereco ?? 'Não informado' }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('admin.profile.edit') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-edit mr-2"></i>
                            Editar Perfil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informações da Conta -->
            <div class="bg-white rounded-lg shadow-md mt-6">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Informações da Conta</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Data de Cadastro</label>
                            <p class="text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Último Acesso</label>
                            <p class="text-gray-900">{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'Nunca' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status da Conta</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Ativo
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Funções</label>
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $role->display_name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Ações Rápidas -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Ações Rápidas</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.profile.edit') }}" 
                       class="flex items-center p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition duration-200">
                        <i class="fas fa-user-edit mr-3 text-blue-600"></i>
                        <span>Editar Perfil</span>
                    </a>
                    <button onclick="openPasswordModal()" 
                            class="w-full flex items-center p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition duration-200">
                        <i class="fas fa-key mr-3 text-yellow-600"></i>
                        <span>Alterar Senha</span>
                    </button>
                    @if($membro && $membro->foto)
                        <form action="{{ route('admin.profile.delete-photo') }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Tem certeza que deseja remover a foto?')"
                                    class="w-full flex items-center p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition duration-200">
                                <i class="fas fa-trash mr-3 text-red-600"></i>
                                <span>Remover Foto</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Estatísticas</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Dias na Igreja</span>
                        <span class="text-sm font-medium text-gray-900">
                            @if($membro && $membro->data_ingresso)
                                @php
                                    $dataIngresso = \Carbon\Carbon::parse($membro->data_ingresso);
                                    $dias = (int) $dataIngresso->diffInDays(now());
                                    if ($dias == 0) {
                                        $horas = (int) $dataIngresso->diffInHours(now());
                                        if ($horas == 0) {
                                            $minutos = (int) $dataIngresso->diffInMinutes(now());
                                            echo $minutos . ' minutos';
                                        } else {
                                            echo $horas . ' horas';
                                        }
                                    } else {
                                        echo $dias . ' dias';
                                    }
                                @endphp
                            @elseif($membro && $membro->data_membro)
                                @php
                                    $dataMembro = \Carbon\Carbon::parse($membro->data_membro);
                                    $dias = (int) $dataMembro->diffInDays(now());
                                    if ($dias == 0) {
                                        $horas = (int) $dataMembro->diffInHours(now());
                                        if ($horas == 0) {
                                            $minutos = (int) $dataMembro->diffInMinutes(now());
                                            echo $minutos . ' minutos';
                                        } else {
                                            echo $horas . ' horas';
                                        }
                                    } else {
                                        echo $dias . ' dias';
                                    }
                                @endphp
                            @else
                                @php
                                    $dias = (int) $user->created_at->diffInDays(now());
                                    if ($dias == 0) {
                                        $horas = (int) $user->created_at->diffInHours(now());
                                        if ($horas == 0) {
                                            $minutos = (int) $user->created_at->diffInMinutes(now());
                                            echo $minutos . ' minutos';
                                        } else {
                                            echo $horas . ' horas';
                                        }
                                    } else {
                                        echo $dias . ' dias';
                                    }
                                @endphp
                            @endif
                        </span>
                    </div>
                    @if($membro)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Ministérios</span>
                            <span class="text-sm font-medium text-gray-900">
                                @php
                                    $cargosAtivos = $membro->cargos()->wherePivot('ativo', true)->get();
                                    $ministeriosUnicos = $cargosAtivos->map(function($cargo) {
                                        return $cargo->departamento && $cargo->departamento->ministerio 
                                            ? $cargo->departamento->ministerio 
                                            : null;
                                    })->filter()->unique('id');
                                @endphp
                                {{ $ministeriosUnicos->count() }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Cargos</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ $membro->cargos()->wherePivot('ativo', true)->count() }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Alterar Senha -->
<div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Alterar Senha</h3>
            </div>
            <form action="{{ route('admin.profile.change-password') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Senha Atual
                        </label>
                        <input type="password" id="current_password" name="current_password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Nova Senha
                        </label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmar Nova Senha
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closePasswordModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Alterar Senha
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openPasswordModal() {
    document.getElementById('passwordModal').classList.remove('hidden');
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.add('hidden');
}

// Fechar modal ao clicar fora
document.getElementById('passwordModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePasswordModal();
    }
});
</script>
@endsection 