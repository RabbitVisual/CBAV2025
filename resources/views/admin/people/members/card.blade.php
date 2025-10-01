@extends('layouts.admin')

@section('title', 'Cartão do Membro')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Cartão do Membro</h1>
                <p class="text-gray-600 mt-2">Cartão de identificação de {{ $membro->nome }}</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="window.print()" 
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <i class="fas fa-print mr-2"></i>
                    Imprimir
                </button>
                <a href="{{ route('admin.people.members.show', $membro->id) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Cartão do Membro -->
    <div class="max-w-2xl mx-auto">
        <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/20 dark:border-gray-700/30 print:shadow-none print:border-0 overflow-hidden">
            <!-- Cabeçalho do Cartão -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold">CARTÃO DE MEMBRO</h2>
                        <p class="text-blue-100 text-sm">Igreja Batista</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-blue-100">Nº do Membro</div>
                        <div class="text-xl font-bold">{{ str_pad($membro->id, 4, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
            </div>

            <!-- Conteúdo do Cartão -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Foto e Informações Principais -->
                    <div class="md:col-span-1">
                        <div class="text-center mb-4">
                            @if($membro->foto)
                                <img src="{{ asset('storage/' . $membro->foto) }}" 
                                     alt="Foto de {{ $membro->nome }}" 
                                     class="w-32 h-32 object-cover rounded-2xl mx-auto border-4 border-white dark:border-gray-600 shadow-lg">
                            @else
                                <div class="w-32 h-32 bg-gradient-to-br from-gray-400 to-gray-500 dark:from-gray-600 dark:to-gray-700 rounded-2xl mx-auto border-4 border-white dark:border-gray-600 shadow-lg flex items-center justify-center">
                                    <i class="fas fa-user text-4xl text-white"></i>
                                </div>
                            @endif
                        </div>

                        <div class="text-center mb-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $membro->nome }}</h3>
                            <p class="text-gray-600 text-sm">
                                @php
                                    $cargoAtivo = $membro->cargos()->wherePivot('ativo', true)->first();
                                @endphp
                                @if($cargoAtivo)
                                    {{ $cargoAtivo->nome }}
                                    @if($cargoAtivo->departamento && $cargoAtivo->departamento->ministerio)
                                        - {{ $cargoAtivo->departamento->ministerio->nome }}
                                    @endif
                                @else
                                    Sem cargo atribuído
                                @endif
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="text-center mb-4">
                            @if($membro->ativo)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    MEMBRO ATIVO
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-pause-circle mr-1"></i>
                                    MEMBRO INATIVO
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Informações Detalhadas -->
                    <div class="md:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Informações Pessoais -->
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Data de Nascimento</label>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $membro->data_nascimento ? $membro->data_nascimento->format('d/m/Y') : 'Não informado' }}
                                        @if($membro->data_nascimento)
                                            <span class="text-gray-500">({{ $membro->data_nascimento->age }} anos)</span>
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Sexo</label>
                                    <p class="text-sm font-medium text-gray-900">
                                        @if($membro->sexo == 'M')
                                            Masculino
                                        @elseif($membro->sexo == 'F')
                                            Feminino
                                        @else
                                            Não informado
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Estado Civil</label>
                                    <p class="text-sm font-medium text-gray-900">
                                        @switch($membro->estado_civil)
                                            @case('solteiro')
                                                Solteiro(a)
                                                @break
                                            @case('casado')
                                                Casado(a)
                                                @break
                                            @case('divorciado')
                                                Divorciado(a)
                                                @break
                                            @case('viuvo')
                                                Viúvo(a)
                                                @break
                                            @default
                                                Não informado
                                        @endswitch
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Profissão</label>
                                    <p class="text-sm font-medium text-gray-900">{{ $membro->profissao ?: 'Não informado' }}</p>
                                </div>
                            </div>

                            <!-- Informações Eclesiásticas -->
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Data do Batismo</label>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $membro->data_batismo ? $membro->data_batismo->format('d/m/Y') : 'Não informado' }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Data de Entrada</label>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $membro->data_entrada ? $membro->data_entrada->format('d/m/Y') : 'Não informado' }}
                                        @if($membro->data_entrada)
                                            <span class="text-gray-500">({{ $membro->data_entrada->diffInYears(now()) }} anos)</span>
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Departamento</label>
                                    <p class="text-sm font-medium text-gray-900">
                                        @php
                                            $cargoAtivo = $membro->cargos()->wherePivot('ativo', true)->first();
                                            $departamentoAtual = $cargoAtivo && $cargoAtivo->departamento ? $cargoAtivo->departamento : null;
                                        @endphp
                                        {{ $departamentoAtual ? $departamentoAtual->nome : 'Não atribuído' }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Contato</label>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $membro->telefone ?: 'Não informado' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Endereço -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Endereço</label>
                            <p class="text-sm text-gray-900">
                                @if($membro->endereco)
                                    {{ $membro->endereco }}
                                    @if($membro->bairro)
                                        , {{ $membro->bairro }}
                                    @endif
                                    @if($membro->cidade)
                                        , {{ $membro->cidade }}
                                    @endif
                                    @if($membro->estado)
                                        - {{ $membro->estado }}
                                    @endif
                                    @if($membro->cep)
                                        , CEP: {{ $membro->cep }}
                                    @endif
                                @else
                                    Não informado
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rodapé do Cartão -->
            <div class="bg-gray-50 p-4 rounded-b-lg border-t border-gray-200">
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <div>
                        <p class="font-medium">Emitido em: {{ now()->format('d/m/Y H:i') }}</p>
                        <p>Este cartão é de uso interno da igreja</p>
                    </div>
                    <div class="text-right">
                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-qrcode text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-xs mt-1">Código: {{ str_pad($membro->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações Adicionais para Impressão -->
        <div class="mt-8 print:mt-4">
            <div class="bg-white rounded-lg shadow-lg p-6 print:shadow-none">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações Adicionais</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Escolaridade</h4>
                        <p class="text-sm text-gray-600">
                            @switch($membro->escolaridade)
                                @case('fundamental_incompleto')
                                    Fundamental Incompleto
                                    @break
                                @case('fundamental_completo')
                                    Fundamental Completo
                                    @break
                                @case('medio_incompleto')
                                    Médio Incompleto
                                    @break
                                @case('medio_completo')
                                    Médio Completo
                                    @break
                                @case('superior_incompleto')
                                    Superior Incompleto
                                    @break
                                @case('superior_completo')
                                    Superior Completo
                                    @break
                                @case('pos_graduacao')
                                    Pós-Graduação
                                    @break
                                @case('mestrado')
                                    Mestrado
                                    @break
                                @case('doutorado')
                                    Doutorado
                                    @break
                                @default
                                    Não informado
                            @endswitch
                        </p>
                    </div>

                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">E-mail</h4>
                        <p class="text-sm text-gray-600">{{ $membro->email ?: 'Não informado' }}</p>
                    </div>

                    @if($membro->observacoes)
                        <div class="md:col-span-2">
                            <h4 class="font-medium text-gray-900 mb-2">Observações</h4>
                            <p class="text-sm text-gray-600">{{ $membro->observacoes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body {
        background: white !important;
    }
    
    .container {
        max-width: none !important;
        padding: 0 !important;
    }
    
    .bg-white {
        background: white !important;
    }
    
    .shadow-lg, .shadow-md {
        box-shadow: none !important;
    }
    
    .border {
        border: 1px solid #000 !important;
    }
    
    .text-gray-600 {
        color: #000 !important;
    }
    
    .text-gray-900 {
        color: #000 !important;
    }
    
    .text-gray-500 {
        color: #000 !important;
    }
    
    .bg-gradient-to-r {
        background: #000 !important;
        color: white !important;
    }
    
    .bg-gray-50 {
        background: #f9f9f9 !important;
    }
    
    .border-gray-200 {
        border-color: #000 !important;
    }
    
    .border-t {
        border-top: 1px solid #000 !important;
    }
    
    .rounded-lg {
        border-radius: 0 !important;
    }
    
    .rounded-t-lg {
        border-radius: 0 !important;
    }
    
    .rounded-b-lg {
        border-radius: 0 !important;
    }
    
    /* Esconder elementos desnecessários na impressão */
    .print\\:hidden {
        display: none !important;
    }
    
    /* Mostrar elementos específicos para impressão */
    .print\\:block {
        display: block !important;
    }
    
    /* Quebra de página */
    .page-break {
        page-break-before: always;
    }
}
</style>

@push('scripts')
<script>
// Função para imprimir
function imprimirCartao() {
    window.print();
}

// Adicionar evento de tecla para impressão (Ctrl+P)
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        window.print();
    }
});
</script>
@endpush
@endsection