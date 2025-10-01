@extends('layouts.member')

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
            <a href="{{ route('member.eventos.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Conteúdo Principal -->
            <div class="lg:col-span-2">
                <!-- Imagem do Evento -->
                @if($evento->imagem_url)
                    <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
                        <img src="{{ $evento->imagem_url }}" alt="{{ $evento->titulo }}" 
                             class="w-full h-64 object-cover">
                    </div>
                @endif

                <!-- Informações do Evento -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações do Evento</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Data e Hora</h3>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ $evento->data_inicio->format('d/m/Y') }}
                                    @if($evento->data_fim && $evento->data_fim != $evento->data_inicio)
                                        - {{ $evento->data_fim->format('d/m/Y') }}
                                    @endif
                                </div>
                                @if($evento->hora_inicio)
                                    <div class="flex items-center text-gray-600 mt-1">
                                        <i class="fas fa-clock mr-2"></i>
                                        {{ $evento->hora_inicio->format('H:i') }}
                                        @if($evento->hora_fim)
                                            - {{ $evento->hora_fim->format('H:i') }}
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Local</h3>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    {{ $evento->local ?: 'Não informado' }}
                                </div>
                                @if($evento->endereco)
                                    <div class="text-gray-600 text-sm mt-1">{{ $evento->endereco }}</div>
                                @endif
                            </div>

                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Tipo de Evento</h3>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-tag mr-2"></i>
                                    {{ $evento->tipo_evento_formatado }}
                                </div>
                            </div>

                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Valor</h3>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-dollar-sign mr-2"></i>
                                    {{ $evento->valor_formatado }}
                                </div>
                            </div>

                            @if($evento->vagas_totais)
                                <div>
                                    <h3 class="font-medium text-gray-900 mb-2">Vagas</h3>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-users mr-2"></i>
                                        {{ $evento->vagas_disponiveis }} disponíveis de {{ $evento->vagas_totais }}
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $evento->percentual_ocupacao }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $evento->percentual_ocupacao }}% ocupado</p>
                                </div>
                            @endif

                            @if($evento->organizador)
                                <div>
                                    <h3 class="font-medium text-gray-900 mb-2">Organizador</h3>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-user mr-2"></i>
                                        {{ $evento->organizador->name }}
                                    </div>
                                </div>
                            @endif

                            @if($evento->ministerio)
                                <div>
                                    <h3 class="font-medium text-gray-900 mb-2">Ministério</h3>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-church mr-2"></i>
                                        {{ $evento->ministerio->nome }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($evento->descricao)
                            <div class="mt-6">
                                <h3 class="font-medium text-gray-900 mb-2">Descrição</h3>
                                <div class="text-gray-600 prose max-w-none">
                                    {!! nl2br(e($evento->descricao)) !!}
                                </div>
                            </div>
                        @endif

                        @if($evento->regulamento)
                            <div class="mt-6">
                                <h3 class="font-medium text-gray-900 mb-2">Regulamento</h3>
                                <div class="text-gray-600 prose max-w-none">
                                    {!! nl2br(e($evento->regulamento)) !!}
                                </div>
                            </div>
                        @endif

                        @if($evento->informacoes_adicionais)
                            <div class="mt-6">
                                <h3 class="font-medium text-gray-900 mb-2">Informações Adicionais</h3>
                                <div class="text-gray-600 prose max-w-none">
                                    {!! nl2br(e($evento->informacoes_adicionais)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Status da Inscrição -->
                @if($inscricaoUsuario)
                    <div class="bg-white rounded-lg shadow mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Sua Inscrição</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Status</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $inscricaoUsuario->status === 'confirmada' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $inscricaoUsuario->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $inscricaoUsuario->status === 'cancelada' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ $inscricaoUsuario->status_formatado }}
                                    </span>
                                </div>

                                @if($inscricaoUsuario->data_inscricao)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Data da Inscrição</span>
                                        <span class="text-sm text-gray-900">{{ $inscricaoUsuario->data_inscricao->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif

                                @if($inscricaoUsuario->presenca !== null)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Presença</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $inscricaoUsuario->presenca ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $inscricaoUsuario->presenca ? 'Presente' : 'Ausente' }}
                                        </span>
                                    </div>
                                @endif

                                @if($inscricaoUsuario->certificado_emitido)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Certificado</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Emitido
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4 space-y-2">
                                @if($inscricaoUsuario->status === 'pendente')
                                    <form method="POST" action="{{ route('member.eventos.cancelar-inscricao', $evento) }}" 
                                          onsubmit="return confirm('Tem certeza que deseja cancelar sua inscrição?')">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            <i class="fas fa-times mr-2"></i>Cancelar Inscrição
                                        </button>
                                    </form>
                                @endif

                                @if($inscricaoUsuario->certificado_emitido)
                                    <a href="{{ route('member.eventos.download-certificado', $evento) }}" 
                                       class="block w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors text-center">
                                        <i class="fas fa-download mr-2"></i>Baixar Certificado
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Inscrição -->
                @if($evento->podeInscricaoUsuario(auth()->user()))
                    <div class="bg-white rounded-lg shadow mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Fazer Inscrição</h3>
                            
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Evento</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $evento->gratuito ? 'Gratuito' : 'Pago' }}</span>
                                </div>

                                @if(!$evento->gratuito)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Valor</span>
                                        <span class="text-sm font-medium text-gray-900">R$ {{ number_format($evento->valor_inscricao, 2, ',', '.') }}</span>
                                    </div>
                                @endif

                                @if($evento->vagas_totais)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Vagas Disponíveis</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $evento->vagas_disponiveis }}</span>
                                    </div>
                                @endif

                                @if($evento->inscricao_ate)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Inscrições até</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $evento->inscricao_ate->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('member.eventos.inscrever', $evento) }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-colors text-center">
                                <i class="fas fa-ticket-alt mr-2"></i>Fazer Inscrição
                            </a>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Inscrições</h3>
                            
                            <div class="text-center">
                                @if($evento->esta_cheio)
                                    <i class="fas fa-times-circle text-red-500 text-4xl mb-3"></i>
                                    <p class="text-gray-600 mb-2">Evento Lotado</p>
                                    <p class="text-sm text-gray-500">Não há mais vagas disponíveis para este evento.</p>
                                @elseif(!$evento->inscricao_aberta)
                                    <i class="fas fa-clock text-yellow-500 text-4xl mb-3"></i>
                                    <p class="text-gray-600 mb-2">Inscrições Encerradas</p>
                                    <p class="text-sm text-gray-500">O período de inscrições já foi encerrado.</p>
                                @else
                                    <i class="fas fa-info-circle text-blue-500 text-4xl mb-3"></i>
                                    <p class="text-gray-600 mb-2">Inscrições Indisponíveis</p>
                                    <p class="text-sm text-gray-500">Este evento não está disponível para inscrições no momento.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Informações Importantes -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Importantes</h3>
                        
                        <div class="space-y-3 text-sm text-gray-600">
                            @if($evento->dias_restantes !== null && $evento->dias_restantes > 0)
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-day mr-2 text-blue-500"></i>
                                    <span>{{ $evento->dias_restantes }} dias para o evento</span>
                                </div>
                            @endif

                            @if($evento->inscricao_obrigatoria)
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i>
                                    <span>Inscrição obrigatória</span>
                                </div>
                            @endif

                            @if($evento->destaque)
                                <div class="flex items-center">
                                    <i class="fas fa-star mr-2 text-yellow-500"></i>
                                    <span>Evento em destaque</span>
                                </div>
                            @endif

                            @if($evento->tipo_publico === 'membros')
                                <div class="flex items-center">
                                    <i class="fas fa-users mr-2 text-blue-500"></i>
                                    <span>Evento exclusivo para membros</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 