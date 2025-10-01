@extends('layouts.admin')

@section('title', 'Grupo: ' . $grupo->nome)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <div class="w-6 h-6 rounded-full mr-3" style="background-color: {{ $grupo->cor }}"></div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $grupo->nome }}</h1>
            @if(!$grupo->ativo)
                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <i class="fas fa-times-circle mr-1"></i>Inativo
                </span>
            @endif
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.ebd.grupos-estudo.edit', $grupo) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.ebd.grupos-estudo.relatorio', $grupo) }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-chart-bar mr-2"></i>Relatório
            </a>
            <a href="{{ route('admin.ebd.grupos-estudo.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações do Grupo -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Informações do Grupo</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Turma</label>
                        <p class="text-sm text-gray-900">{{ $grupo->turma->nome }}</p>
                        <p class="text-xs text-gray-500">{{ $grupo->turma->faixa_etaria }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Capacidade</label>
                        <p class="text-sm text-gray-900">{{ $grupo->membrosAtivos->count() }}/{{ $grupo->capacidade_maxima }} membros</p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="bg-blue-600 h-2 rounded-full" 
                                 style="width: {{ $grupo->capacidade_maxima > 0 ? ($grupo->membrosAtivos->count() / $grupo->capacidade_maxima) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Líder</label>
                        @if($grupo->lider)
                            <p class="text-sm text-gray-900">{{ $grupo->lider->nome }}</p>
                            @if($grupo->lider->email)
                                <p class="text-xs text-gray-500">{{ $grupo->lider->email }}</p>
                            @endif
                        @else
                            <p class="text-sm text-gray-500">Sem líder definido</p>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Criado em</label>
                        <p class="text-sm text-gray-900">{{ $grupo->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                @if($grupo->descricao)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ $grupo->descricao }}</p>
                    </div>
                @endif
            </div>

            <!-- Membros do Grupo -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-900">Membros ({{ $grupo->membrosAtivos->count() }})</h2>
                    @if($grupo->ativo && $grupo->membrosAtivos->count() < $grupo->capacidade_maxima)
                        <a href="{{ route('admin.ebd.grupos-estudo.edit', $grupo) }}#membros" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                            <i class="fas fa-plus mr-1"></i>Adicionar
                        </a>
                    @endif
                </div>
                
                @if($grupo->membrosAtivos->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($grupo->membrosAtivos as $membro)
                            <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                                <div class="flex items-center">
                                    @if($membro->aluno->id == $grupo->lider_id)
                                        <i class="fas fa-crown text-yellow-500 mr-2" title="Líder do Grupo"></i>
                                    @else
                                        <i class="fas fa-user text-gray-400 mr-2"></i>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $membro->aluno->nome }}</p>
                                        <p class="text-xs text-gray-500">Membro desde {{ $membro->data_entrada->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    @if($membro->aluno->email)
                                        <a href="mailto:{{ $membro->aluno->email }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Enviar email">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                    @endif
                                    @if($membro->aluno->telefone)
                                        <a href="tel:{{ $membro->aluno->telefone }}" 
                                           class="text-green-600 hover:text-green-900" title="Ligar">
                                            <i class="fas fa-phone"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">Nenhum membro no grupo ainda.</p>
                        <a href="{{ route('admin.ebd.grupos-estudo.edit', $grupo) }}" 
                           class="mt-2 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Adicionar Membros
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar com Estatísticas e Ações -->
        <div class="lg:col-span-1">
            <!-- Estatísticas Rápidas -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Estatísticas</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total de Membros</span>
                        <span class="text-lg font-bold text-blue-600">{{ $grupo->membrosAtivos->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Avaliações Realizadas</span>
                        <span class="text-lg font-bold text-green-600">{{ $grupo->avaliacoes->where('status', 'concluida')->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Avaliações Pendentes</span>
                        <span class="text-lg font-bold text-yellow-600">{{ $grupo->avaliacoes->where('status', 'pendente')->count() }}</span>
                    </div>
                    
                    @if($grupo->avaliacoes->where('status', 'concluida')->count() > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Média Geral</span>
                            <span class="text-lg font-bold text-purple-600">
                                {{ number_format($grupo->avaliacoes->where('status', 'concluida')->avg('percentual'), 1) }}%
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Avaliações Recentes -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Avaliações Recentes</h3>
                
                @if($grupo->avaliacoes->count() > 0)
                    <div class="space-y-3">
                        @foreach($grupo->avaliacoes->take(5) as $avaliacao)
                            <div class="flex items-center justify-between p-2 border rounded">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ Str::limit($avaliacao->avaliacao->titulo, 20) }}</p>
                                    <p class="text-xs text-gray-500">{{ $avaliacao->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div class="text-right">
                                    @if($avaliacao->status == 'concluida')
                                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">{{ $avaliacao->percentual }}%</span>
                                    @else
                                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">{{ ucfirst($avaliacao->status) }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($grupo->avaliacoes->count() > 5)
                        <div class="mt-3 text-center">
                            <a href="{{ route('admin.ebd.grupos-estudo.relatorio', $grupo) }}" 
                               class="text-blue-600 hover:text-blue-900 text-sm">
                                Ver todas as avaliações
                            </a>
                        </div>
                    @endif
                @else
                    <p class="text-gray-500 text-sm">Nenhuma avaliação realizada ainda.</p>
                @endif
            </div>

            <!-- Ações Rápidas -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Ações Rápidas</h3>
                
                <div class="space-y-2">
                    <a href="{{ route('admin.ebd.grupos-estudo.edit', $grupo) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-center block">
                        <i class="fas fa-edit mr-2"></i>Editar Grupo
                    </a>
                    
                    <form action="{{ route('admin.ebd.grupos-estudo.toggle-status', $grupo) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full {{ $grupo->ativo ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-bold py-2 px-4 rounded-lg">
                            <i class="fas fa-{{ $grupo->ativo ? 'pause' : 'play' }} mr-2"></i>
                            {{ $grupo->ativo ? 'Desativar' : 'Ativar' }} Grupo
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.ebd.grupos-estudo.relatorio', $grupo) }}" 
                       class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg text-center block">
                        <i class="fas fa-chart-bar mr-2"></i>Ver Relatório
                    </a>
                    
                    <form action="{{ route('admin.ebd.grupos-estudo.destroy', $grupo) }}" 
                          method="POST" 
                          onsubmit="return confirm('Tem certeza que deseja excluir este grupo? Esta ação não pode ser desfeita.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                            <i class="fas fa-trash mr-2"></i>Excluir Grupo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection