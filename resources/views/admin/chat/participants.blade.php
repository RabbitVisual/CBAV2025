@extends('layouts.admin')

@section('page-content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-users-cog text-green-600 mr-3"></i>
                    Gerenciar Participantes
                </h1>
                <p class="text-gray-600 mt-2">Sala: {{ $room->nome }}</p>
            </div>
            <a href="{{ route('admin.chat.manage') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Lista de Participantes -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-users text-blue-600 mr-2"></i>
                    Participantes da Sala
                </h2>
                
                @if($participants->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Usuário
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Último Acesso
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($participants as $participant)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                                    <i class="fas fa-user text-gray-600"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $participant->user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $participant->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($participant->tipo === 'admin') bg-red-100 text-red-800
                                                @elseif($participant->tipo === 'moderador') bg-yellow-100 text-yellow-800
                                                @else bg-green-100 text-green-800
                                                @endif">
                                                {{ ucfirst($participant->tipo) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($participant->ativo) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                                {{ $participant->ativo ? 'Ativo' : 'Inativo' }}
                                            </span>
                                            @if($participant->isMuted())
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 ml-2">
                                                    Mutado
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $participant->ultimo_acesso ? $participant->ultimo_acesso->diffForHumans() : 'Nunca' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                @if($participant->ativo)
                                                    <form action="{{ route('admin.chat.participants.toggle-mute', [$room->id, $participant->id]) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-orange-600 hover:text-orange-900"
                                                                title="{{ $participant->isMuted() ? 'Desmutar' : 'Mutar' }}">
                                                            <i class="fas {{ $participant->isMuted() ? 'fa-volume-up' : 'fa-volume-mute' }}"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('admin.chat.participants.remove', [$room->id, $participant->id]) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900"
                                                                onclick="return confirm('Tem certeza que deseja remover este participante?')"
                                                                title="Remover">
                                                            <i class="fas fa-user-minus"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400">Removido</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">Nenhum participante nesta sala.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Adicionar Participante -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-user-plus text-green-600 mr-2"></i>
                    Adicionar Participante
                </h2>
                
                <form action="{{ route('admin.chat.participants.add', $room->id) }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Usuário *
                            </label>
                            <select id="user_id" 
                                    name="user_id" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="">Selecione um usuário</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Participante *
                            </label>
                            <select id="tipo" 
                                    name="tipo" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="participante">Participante</option>
                                <option value="moderador">Moderador</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                        
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-user-plus mr-2"></i>
                            Adicionar Participante
                        </button>
                    </div>
                </form>
            </div>

            <!-- Estatísticas da Sala -->
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
                    Estatísticas da Sala
                </h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total de Participantes:</span>
                        <span class="font-semibold">{{ $participants->where('ativo', true)->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Administradores:</span>
                        <span class="font-semibold">{{ $participants->where('ativo', true)->where('tipo', 'admin')->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Moderadores:</span>
                        <span class="font-semibold">{{ $participants->where('ativo', true)->where('tipo', 'moderador')->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Participantes:</span>
                        <span class="font-semibold">{{ $participants->where('ativo', true)->where('tipo', 'participante')->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Mutados:</span>
                        <span class="font-semibold">{{ $participants->where('ativo', true)->filter(function($p) { return $p->isMuted(); })->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Informações da Sala -->
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Informações da Sala
                </h2>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-600">Nome:</span>
                        <p class="font-medium">{{ $room->nome }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-600">Tipo:</span>
                        <p class="font-medium">{{ ucfirst($room->tipo) }}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($room->ativo) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                            {{ $room->ativo ? 'Ativa' : 'Inativa' }}
                        </span>
                    </div>
                    
                    @if($room->max_participantes)
                        <div>
                            <span class="text-sm text-gray-600">Limite de Participantes:</span>
                            <p class="font-medium">{{ $room->max_participantes }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <span class="text-sm text-gray-600">Criada em:</span>
                        <p class="font-medium">{{ $room->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 