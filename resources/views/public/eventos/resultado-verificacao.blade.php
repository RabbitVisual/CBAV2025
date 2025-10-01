@extends('layouts.public')

@section('title', 'Resultado da Verificação')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Cabeçalho -->
        <div class="text-center mb-8">
            @if($inscricao)
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Inscrição Encontrada!</h1>
                <p class="text-gray-600 mt-2">Sua inscrição foi localizada com sucesso</p>
            @else
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Inscrição Não Encontrada</h1>
                <p class="text-gray-600 mt-2">Não foi encontrada inscrição para este e-mail</p>
            @endif
        </div>

        @if($inscricao)
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
                            <h3 class="font-medium text-gray-900 mb-2">Participante</h3>
                            <p class="text-gray-600">{{ $inscricao->nome }}</p>
                        </div>

                        <div>
                            <h3 class="font-medium text-gray-900 mb-2">E-mail</h3>
                            <p class="text-gray-600">{{ $inscricao->email }}</p>
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

                        @if($inscricao->presenca !== null)
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Presença</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $inscricao->presenca ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $inscricao->presenca ? 'Presente' : 'Ausente' }}
                                </span>
                            </div>
                        @endif

                        @if($inscricao->certificado_emitido)
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Certificado</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-certificate mr-1"></i>Disponível
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informações do Evento -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Evento</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Data</h4>
                            <p class="text-gray-600">{{ $evento->data_inicio->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Hora</h4>
                            <p class="text-gray-600">
                                {{ $evento->hora_inicio ? $evento->hora_inicio->format('H:i') : 'Não informado' }}
                            </p>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Local</h4>
                            <p class="text-gray-600">{{ $evento->local ?: 'Não informado' }}</p>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Valor</h4>
                            <p class="text-gray-600">{{ $evento->valor_formatado }}</p>
                        </div>
                    </div>
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

                        @if($evento->certificado_disponivel && $inscricao->certificado_emitido)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-purple-100">
                                        <span class="text-purple-600 text-sm font-medium">{{ $inscricao->status === 'pendente' ? (($evento->gratuito ? '3' : '4')) : '2' }}</span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Baixar Certificado</p>
                                    <p class="text-sm text-gray-600">Seu certificado de participação está disponível.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('public.eventos.show', $evento) }}" 
                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors text-center">
                    <i class="fas fa-eye mr-2"></i>Ver Detalhes do Evento
                </a>
                
                @if($inscricao->certificado_emitido)
                    <a href="#" 
                       class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors text-center">
                        <i class="fas fa-download mr-2"></i>Baixar Certificado
                    </a>
                @endif
            </div>

        @else
            <!-- Inscrição não encontrada -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6 text-center">
                    <i class="fas fa-user-times text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Inscrição Não Encontrada</h3>
                    <p class="text-gray-600 mb-6">
                        Não foi encontrada inscrição para o e-mail <strong>{{ request('email') }}</strong> neste evento.
                    </p>
                    
                    <div class="space-y-3">
                        <p class="text-sm text-gray-500">Possíveis motivos:</p>
                        <ul class="text-sm text-gray-500 space-y-1">
                            <li>• O e-mail digitado está incorreto</li>
                            <li>• Você ainda não se inscreveu neste evento</li>
                            <li>• A inscrição foi cancelada</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('public.eventos.inscrever', $evento) }}" 
                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors text-center">
                    <i class="fas fa-ticket-alt mr-2"></i>Fazer Inscrição
                </a>
                
                <a href="{{ route('public.eventos.verificar-inscricao', $evento) }}" 
                   class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors text-center">
                    <i class="fas fa-search mr-2"></i>Verificar Novamente
                </a>
            </div>
        @endif

        <!-- Call to Action -->
        <div class="mt-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-8 text-center text-white">
            <h3 class="text-2xl font-bold mb-4">Quer fazer parte da nossa comunidade?</h3>
            <p class="text-blue-100 mb-6">Crie uma conta e tenha acesso a todos os eventos e recursos da igreja.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" 
                   class="bg-white text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                    <i class="fas fa-home mr-2"></i>Voltar ao Início
                </a>
                <a href="{{ route('home') }}" 
                   class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-medium hover:bg-white hover:text-blue-600 transition-colors">
                    <i class="fas fa-info-circle mr-2"></i>Saiba Mais
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 