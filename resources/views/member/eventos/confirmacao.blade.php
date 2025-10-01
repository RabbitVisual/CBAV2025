@extends('layouts.member')

@section('title', 'Inscrição Confirmada')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Cabeçalho -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <i class="fas fa-check text-green-600 text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Inscrição Confirmada!</h1>
            <p class="text-gray-600 mt-2">Sua inscrição foi processada com sucesso</p>
        </div>

        <!-- Detalhes da Inscrição -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalhes da Inscrição</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Evento</h3>
                        <p class="text-gray-600">{{ $evento->titulo }}</p>
                    </div>

                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Data</h3>
                        <p class="text-gray-600">{{ $evento->data_inicio->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Hora</h3>
                        <p class="text-gray-600">
                            {{ $evento->hora_inicio ? $evento->hora_inicio->format('H:i') : 'Não informado' }}
                        </p>
                    </div>

                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Local</h3>
                        <p class="text-gray-600">{{ $evento->local ?: 'Não informado' }}</p>
                    </div>

                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Participante</h3>
                        <p class="text-gray-600">{{ $inscricao->nome }}</p>
                    </div>

                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Status</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $inscricao->status === 'confirmada' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $inscricao->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $inscricao->status === 'cancelada' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ $inscricao->status_formatado }}
                        </span>
                    </div>

                    @if($inscricao->data_inscricao)
                        <div>
                            <h3 class="font-medium text-gray-900 mb-2">Data da Inscrição</h3>
                            <p class="text-gray-600">{{ $inscricao->data_inscricao->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif

                    @if(!$evento->gratuito)
                        <div>
                            <h3 class="font-medium text-gray-900 mb-2">Valor</h3>
                            <p class="text-gray-600">R$ {{ number_format($evento->valor_inscricao, 2, ',', '.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informações Importantes -->
        <div class="bg-blue-50 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-blue-900 mb-4">Informações Importantes</h3>
            
            <div class="space-y-3 text-blue-800">
                @if($inscricao->status === 'pendente')
                    <div class="flex items-start">
                        <i class="fas fa-clock mr-2 mt-0.5"></i>
                        <span>Sua inscrição está pendente de confirmação. Você receberá uma notificação quando for confirmada.</span>
                    </div>
                @endif

                @if(!$evento->gratuito && $inscricao->status === 'pendente')
                    <div class="flex items-start">
                        <i class="fas fa-credit-card mr-2 mt-0.5"></i>
                        <span>Para eventos pagos, o pagamento deve ser realizado para confirmar a inscrição.</span>
                    </div>
                @endif

                <div class="flex items-start">
                    <i class="fas fa-envelope mr-2 mt-0.5"></i>
                    <span>Você receberá uma confirmação por e-mail em breve.</span>
                </div>

                @if($evento->inscricao_obrigatoria)
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle mr-2 mt-0.5"></i>
                        <span>Este evento requer inscrição obrigatória. Chegue com antecedência.</span>
                    </div>
                @endif

                @if($evento->local)
                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt mr-2 mt-0.5"></i>
                        <span>Local: {{ $evento->local }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Próximos Passos -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Próximos Passos</h3>
                
                <div class="space-y-4">
                    @if($inscricao->status === 'pendente')
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100">
                                    <span class="text-yellow-600 text-sm font-medium">1</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Aguardar Confirmação</p>
                                <p class="text-sm text-gray-600">Sua inscrição será analisada e confirmada em breve.</p>
                            </div>
                        </div>
                    @endif

                    @if(!$evento->gratuito && $inscricao->status === 'pendente')
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                    <span class="text-blue-600 text-sm font-medium">{{ $inscricao->status === 'pendente' ? '2' : '1' }}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Realizar Pagamento</p>
                                <p class="text-sm text-gray-600">Complete o pagamento para confirmar sua participação.</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                                <span class="text-green-600 text-sm font-medium">{{ $inscricao->status === 'pendente' ? (($evento->gratuito ? '2' : '3')) : '1' }}</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Participar do Evento</p>
                            <p class="text-sm text-gray-600">Chegue no local e horário marcados para participar.</p>
                        </div>
                    </div>

                    @if($evento->certificado_disponivel)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-purple-100">
                                    <span class="text-purple-600 text-sm font-medium">{{ $inscricao->status === 'pendente' ? (($evento->gratuito ? '3' : '4')) : '2' }}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Receber Certificado</p>
                                <p class="text-sm text-gray-600">Após o evento, você poderá baixar seu certificado de participação.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ações -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('member.eventos.show', $evento) }}" 
               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors text-center">
                <i class="fas fa-eye mr-2"></i>Ver Detalhes do Evento
            </a>
            
            <a href="{{ route('member.eventos.minhas-inscricoes') }}" 
               class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors text-center">
                <i class="fas fa-ticket-alt mr-2"></i>Minhas Inscrições
            </a>
            
            <a href="{{ route('member.eventos.index') }}" 
               class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors text-center">
                <i class="fas fa-calendar mr-2"></i>Ver Mais Eventos
            </a>
        </div>

        <!-- Informações de Contato -->
        @if($evento->organizador || $evento->ministerio)
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Precisa de Ajuda?</h3>
                
                <div class="space-y-3">
                    @if($evento->organizador)
                        <div class="flex items-center">
                            <i class="fas fa-user text-gray-400 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Organizador</p>
                                <p class="text-sm text-gray-600">{{ $evento->organizador->name }}</p>
                            </div>
                        </div>
                    @endif

                    @if($evento->ministerio)
                        <div class="flex items-center">
                            <i class="fas fa-church text-gray-400 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Ministério</p>
                                <p class="text-sm text-gray-600">{{ $evento->ministerio->nome }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-gray-400 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Dúvidas</p>
                            <p class="text-sm text-gray-600">Entre em contato com a administração da igreja</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 