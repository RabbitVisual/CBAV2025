@extends('layouts.admin')

@section('title', $evento->titulo)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $evento->titulo }}</h1>
                <p class="text-gray-600 mt-2">{{ $evento->descricao_curta }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.eventos.edit', $evento) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Editar
                </a>
                <a href="{{ route('admin.eventos.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Voltar
                </a>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total de Inscrições</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_inscricoes'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Confirmadas</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['inscricoes_confirmadas'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pendentes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['inscricoes_pendentes'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-dollar-sign text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Arrecadado</p>
                        <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($estatisticas['valor_total_arrecadado'], 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informações do Evento -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Evento</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Data e Hora</h4>
                                <p class="text-gray-600">
                                    {{ $evento->data_inicio->format('d/m/Y') }}
                                    @if($evento->data_fim && $evento->data_fim != $evento->data_inicio)
                                        - {{ $evento->data_fim->format('d/m/Y') }}
                                    @endif
                                </p>
                                @if($evento->hora_inicio)
                                    <p class="text-gray-600">
                                        {{ $evento->hora_inicio->format('H:i') }}
                                        @if($evento->hora_fim)
                                            - {{ $evento->hora_fim->format('H:i') }}
                                        @endif
                                    </p>
                                @endif
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Local</h4>
                                <p class="text-gray-600">{{ $evento->local ?: 'Não informado' }}</p>
                                @if($evento->endereco)
                                    <p class="text-gray-600 text-sm">{{ $evento->endereco }}</p>
                                @endif
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Status</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $evento->status === 'ativo' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $evento->status === 'rascunho' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $evento->status === 'cancelado' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $evento->status === 'finalizado' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $evento->status_formatado }}
                                </span>
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Público Alvo</h4>
                                <p class="text-gray-600">{{ $evento->tipo_publico_formatado }}</p>
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Tipo de Evento</h4>
                                <p class="text-gray-600">{{ $evento->tipo_evento_formatado }}</p>
                            </div>

                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Valor</h4>
                                <p class="text-gray-600">{{ $evento->valor_formatado }}</p>
                            </div>

                            @if($evento->vagas_totais)
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Vagas</h4>
                                    <p class="text-gray-600">
                                        {{ $evento->vagas_disponiveis }} disponíveis de {{ $evento->vagas_totais }}
                                    </p>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $evento->percentual_ocupacao }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $evento->percentual_ocupacao }}% ocupado</p>
                                </div>
                            @endif

                            @if($evento->organizador)
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Organizador</h4>
                                    <p class="text-gray-600">{{ $evento->organizador->name }}</p>
                                </div>
                            @endif

                            @if($evento->ministerio)
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Ministério</h4>
                                    <p class="text-gray-600">{{ $evento->ministerio->nome }}</p>
                                </div>
                            @endif
                        </div>

                        @if($evento->descricao)
                            <div class="mt-6">
                                <h4 class="font-medium text-gray-900 mb-2">Descrição</h4>
                                <div class="text-gray-600 prose max-w-none">
                                    {!! nl2br(e($evento->descricao)) !!}
                                </div>
                            </div>
                        @endif

                        @if($evento->regulamento)
                            <div class="mt-6">
                                <h4 class="font-medium text-gray-900 mb-2">Regulamento</h4>
                                <div class="text-gray-600 prose max-w-none">
                                    {!! nl2br(e($evento->regulamento)) !!}
                                </div>
                            </div>
                        @endif

                        @if($evento->informacoes_adicionais)
                            <div class="mt-6">
                                <h4 class="font-medium text-gray-900 mb-2">Informações Adicionais</h4>
                                <div class="text-gray-600 prose max-w-none">
                                    {!! nl2br(e($evento->informacoes_adicionais)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Ações Rápidas -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ações Rápidas</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <a href="{{ route('admin.eventos.inscricoes', $evento) }}" 
                               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-users text-blue-600 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900">Gerenciar Inscrições</h4>
                                    <p class="text-sm text-gray-600">{{ $estatisticas['total_inscricoes'] }} inscritos</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.eventos.pagamentos', $evento) }}" 
                               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-credit-card text-green-600 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900">Pagamentos</h4>
                                    <p class="text-sm text-gray-600">{{ $estatisticas['pagamentos_aprovados'] }} aprovados</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.eventos.inscricoes.exportar-presenca', $evento) }}" 
                               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-download text-purple-600 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900">Exportar Lista</h4>
                                    <p class="text-sm text-gray-600">CSV com inscritos</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Imagem do Evento -->
                @if($evento->imagem_url)
                    <div class="bg-white rounded-lg shadow mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Imagem</h3>
                            <img src="{{ $evento->imagem_url }}" alt="{{ $evento->titulo }}" 
                                 class="w-full h-48 object-cover rounded-lg">
                        </div>
                    </div>
                @endif

                <!-- Status do Evento -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Inscrições Abertas</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $evento->inscricao_aberta ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $evento->inscricao_aberta ? 'Sim' : 'Não' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Evento Cheio</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $evento->esta_cheio ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $evento->esta_cheio ? 'Sim' : 'Não' }}
                                </span>
                            </div>

                            @if($evento->dias_restantes !== null)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Dias Restantes</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $evento->dias_restantes }}</span>
                                </div>
                            @endif

                            @if($evento->inscricao_ate)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Inscrições até</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $evento->inscricao_ate->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ações</h3>
                        
                        <div class="space-y-3">
                            <form method="POST" action="{{ route('admin.eventos.toggle-status', $evento) }}" class="inline">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                                    <i class="fas fa-toggle-on mr-2"></i>
                                    {{ $evento->status === 'ativo' ? 'Desativar' : 'Ativar' }} Evento
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.eventos.toggle-destaque', $evento) }}" class="inline">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                                    <i class="fas fa-star mr-2"></i>
                                    {{ $evento->destaque ? 'Remover' : 'Adicionar' }} Destaque
                                </button>
                            </form>

                            <a href="{{ route('admin.eventos.edit', $evento) }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                                <i class="fas fa-edit mr-2"></i>Editar Evento
                            </a>

                            <form method="POST" action="{{ route('admin.eventos.destroy', $evento) }}" 
                                  onsubmit="return confirm('Tem certeza que deseja excluir este evento?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md transition-colors">
                                    <i class="fas fa-trash mr-2"></i>Excluir Evento
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 