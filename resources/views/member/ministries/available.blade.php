@extends('layouts.member')

@section('title', 'Ministérios Disponíveis - Área do Membro')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-search text-purple-600 mr-3"></i>
            Ministérios Disponíveis
        </h1>
        <p class="text-gray-600">Explore os ministérios disponíveis para participação e faça sua solicitação.</p>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-search text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Disponíveis</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalDisponiveis }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Ministérios</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalMinisterios }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Participando</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $participandoCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ministérios Disponíveis -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-list text-purple-600 mr-2"></i>
                Ministérios Disponíveis para Participação
            </h3>
        </div>
        <div class="p-6">
            @if($ministeriosDisponiveis->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($ministeriosDisponiveis as $ministerio)
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hands-helping text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900">{{ $ministerio->nome }}</h4>
                                <p class="text-sm text-gray-600">{{ $ministerio->departamentos->count() }} departamento(s)</p>
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-4">{{ Str::limit($ministerio->descricao, 120) }}</p>
                        
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-users mr-1"></i>{{ $ministerio->total_membros }} membros
                                </span>
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-briefcase mr-1"></i>{{ $ministerio->total_cargos }} cargos
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('member.ministries.request.form', $ministerio->id) }}" 
                               class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium transition-colors">
                                <i class="fas fa-plus mr-2"></i>Solicitar Participação
                            </a>
                            
                            <a href="{{ route('member.ministries.show', $ministerio->id) }}" 
                               class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                Ver Detalhes <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum ministério disponível</h3>
                    <p class="text-gray-600 mb-6">Você já participa de todos os ministérios disponíveis ou tem solicitações pendentes.</p>
                    <a href="{{ route('member.ministries.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar aos Ministérios
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="mt-8 flex justify-center">
        <div class="flex space-x-4">
            <a href="{{ route('member.ministries.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-list mr-2"></i>
                Todos os Ministérios
            </a>
            <a href="{{ route('member.ministries.participations') }}" 
               class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-user-check mr-2"></i>
                Minhas Participações
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Adicionar animações suaves aos cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.hover\\:shadow-lg');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endpush