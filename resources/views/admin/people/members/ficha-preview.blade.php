@extends('layouts.admin')

@section('title', __('Ficha de Membro') . ' - ' . $membro->nome)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Cabeçalho -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ __('Ficha de Membro') }}</h1>
                <p class="text-gray-600 mt-2">{{ __('Ficha profissional com design moderno e bem estruturado') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.people.members.ficha', $membro) }}"
                   class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    {{ __('Baixar PDF') }}
                </a>
                <a href="{{ route('admin.people.members.ficha.print', $membro) }}"
                   class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors flex items-center">
                    <i class="fas fa-print mr-2"></i>
                    {{ __('Imprimir') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Informações do Membro -->
    <div class="bg-white rounded-lg shadow-lg border-2 border-gray-200 mb-8">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center space-x-4">
                @if($membro->foto)
                    <img src="{{ asset('storage/' . $membro->foto) }}" 
                         alt="Foto de {{ $membro->nome }}" 
                         class="w-20 h-20 object-cover rounded-lg border-2 border-gray-200">
                @else
                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user text-2xl text-gray-400"></i>
                    </div>
                @endif
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $membro->nome }}</h2>
                    <p class="text-gray-600">
                        @php
                            $cargoAtivo = $membro->cargos()->wherePivot('ativo', true)->first();
                        @endphp
                        @if($cargoAtivo)
                            {{ $cargoAtivo->nome }}
                            @if($cargoAtivo->departamento && $cargoAtivo->departamento->ministerio)
                                - {{ $cargoAtivo->departamento->ministerio->nome }}
                            @endif
                        @else
                            {{ __('Sem cargo atribuído') }}
                        @endif
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ __('Membro desde') }}: {{ $membro->data_ingresso ? $membro->data_ingresso->format('d/m/Y') : __('Não informado') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview da Ficha -->
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg border-2 border-gray-200 print:shadow-none print:border-0">
            <!-- Título da Seção -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('Preview da Ficha Profissional') }}</h3>
                <p class="text-sm text-gray-600">{{ __('Design moderno com margens adequadas e informações bem organizadas') }}</p>
            </div>

            <!-- Preview da Ficha -->
            <div class="p-8">
                <div class="bg-white border-2 border-gray-300 rounded-lg overflow-hidden shadow-lg" style="max-width: 800px; margin: 0 auto;">
                    <!-- Cabeçalho da Ficha -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6 text-center">
                        <h1 class="text-2xl font-bold mb-2">{{ \App\Models\Configuracao::get('igreja_nome', 'CONGREGAÇÃO BATISTA AVENIDA') }}</h1>
                        <p class="text-sm mb-1">{{ \App\Models\Configuracao::get('igreja_endereco', 'Rua da Avenida, 123 - Centro') }}</p>
                        <p class="text-sm mb-1">{{ \App\Models\Configuracao::get('igreja_cidade', 'São Paulo - SP') }}</p>
                        <p class="text-sm mb-3">{{ \App\Models\Configuracao::get('igreja_telefone', '(11) 99999-9999') }} | {{ \App\Models\Configuracao::get('igreja_email', 'contato@cbav.com') }}</p>
                        <div class="text-lg font-bold uppercase">Ficha de Cadastro de Membro</div>
                    </div>

                    <!-- Conteúdo da Ficha -->
                    <div class="p-6">
                        <!-- Informações Principais -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- Foto e Número -->
                            <div class="text-center">
                                <div class="w-32 h-40 mx-auto mb-3 border-2 border-blue-600 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center">
                                    @if($membro->foto)
                                        <img src="{{ asset('storage/' . $membro->foto) }}" 
                                             alt="Foto de {{ $membro->nome }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="text-gray-500 text-center text-sm font-bold">
                                            FOTO<br>NÃO<br>DISPONÍVEL
                                        </div>
                                    @endif
                                </div>
                                <div class="text-lg font-bold text-blue-600 mb-2">Membro Nº {{ str_pad($membro->id, 4, '0', STR_PAD_LEFT) }}</div>
                                <div class="inline-block px-3 py-1 rounded-full text-xs font-bold text-white {{ $membro->ativo ? 'bg-green-500' : 'bg-gray-500' }}">
                                    {{ $membro->ativo ? 'ATIVO' : 'INATIVO' }}
                                </div>
                            </div>

                            <!-- Informações Pessoais -->
                            <div class="md:col-span-2">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">{{ __('Nome Completo') }}</label>
                                        <div class="p-2 bg-gray-50 border border-gray-200 rounded text-sm">{{ $membro->nome }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">{{ __('Data de Nascimento') }}</label>
                                        <div class="p-2 bg-gray-50 border border-gray-200 rounded text-sm">{{ $membro->data_nascimento ? $membro->data_nascimento->format('d/m/Y') : __('Não informado') }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">{{ __('Data de Batismo') }}</label>
                                        <div class="p-2 bg-gray-50 border border-gray-200 rounded text-sm">{{ $membro->data_batismo ? $membro->data_batismo->format('d/m/Y') : __('Não informado') }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">{{ __('Data de Ingresso') }}</label>
                                        <div class="p-2 bg-gray-50 border border-gray-200 rounded text-sm">{{ $membro->data_ingresso ? $membro->data_ingresso->format('d/m/Y') : __('Não informado') }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">{{ __('Profissão') }}</label>
                                        <div class="p-2 bg-gray-50 border border-gray-200 rounded text-sm">{{ $membro->profissao ?: __('Não informado') }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">{{ __('Telefone') }}</label>
                                        <div class="p-2 bg-gray-50 border border-gray-200 rounded text-sm">{{ $membro->telefone ?: __('Não informado') }}</div>
                                    </div>
                                    
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">{{ __('Email') }}</label>
                                        <div class="p-2 bg-gray-50 border border-gray-200 rounded text-sm">{{ $membro->email ?: __('Não informado') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seção de Endereço -->
                        <div class="mb-6 border-2 border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h3 class="text-lg font-bold text-blue-600 uppercase">{{ __('Endereço') }}</h3>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">{{ __('Endereço Completo') }}</label>
                                        <div class="p-2 bg-gray-50 border border-gray-200 rounded text-sm">{{ $membro->endereco ?: __('Não informado') }}, {{ $membro->bairro ?: __('Não informado') }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">{{ __('Cidade') }}</label>
                                        <div class="p-2 bg-gray-50 border border-gray-200 rounded text-sm">{{ $membro->cidade ?: __('Não informado') }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">{{ __('Estado') }}</label>
                                        <div class="p-2 bg-gray-50 border border-gray-200 rounded text-sm">{{ $membro->estado ?: __('Não informado') }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">{{ __('CEP') }}</label>
                                        <div class="p-2 bg-gray-50 border border-gray-200 rounded text-sm">{{ $membro->cep ?: __('Não informado') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seção de Informações Eclesiásticas -->
                        <div class="mb-6 border-2 border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h3 class="text-lg font-bold text-blue-600 uppercase">{{ __('Informações Eclesiásticas') }}</h3>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">{{ __('Cargo Atual') }}</label>
                                        <div class="p-2 bg-gray-50 border border-gray-200 rounded text-sm">
                                            @if($cargoAtivo)
                                                {{ $cargoAtivo->nome }}
                                            @else
                                                {{ __('Sem cargo') }}
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">{{ __('Departamento') }}</label>
                                        <div class="p-2 bg-gray-50 border border-gray-200 rounded text-sm">
                                            @if($cargoAtivo && $cargoAtivo->departamento)
                                                {{ $cargoAtivo->departamento->nome }}
                                            @else
                                                {{ __('Sem departamento') }}
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">{{ __('Ministério') }}</label>
                                        <div class="p-2 bg-gray-50 border border-gray-200 rounded text-sm">
                                            @if($cargoAtivo && $cargoAtivo->departamento && $cargoAtivo->departamento->ministerio)
                                                {{ $cargoAtivo->departamento->ministerio->nome }}
                                            @else
                                                {{ __('Sem ministério') }}
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">{{ __('Data de Validade') }}</label>
                                        <div class="p-2 bg-gray-50 border border-gray-200 rounded text-sm">{{ \Carbon\Carbon::now()->addYears(2)->format('m/Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Área de Assinatura -->
                        <div class="text-center mt-8">
                            <div class="w-48 h-px bg-gray-300 mx-auto mb-2"></div>
                            <div class="text-sm text-gray-600 uppercase font-bold">{{ __('Assinatura do Pastor Responsável') }}</div>
                        </div>
                    </div>

                    <!-- Rodapé -->
                    <div class="bg-gray-50 px-6 py-4 text-center border-t border-gray-200">
                        <p class="text-sm text-gray-600"><strong>{{ \App\Models\Configuracao::get('igreja_nome', 'CONGREGAÇÃO BATISTA AVENIDA') }}</strong></p>
                        <p class="text-sm text-gray-600">{{ \App\Models\Configuracao::get('igreja_endereco', 'Rua da Avenida, 123 - Centro') }}, {{ \App\Models\Configuracao::get('igreja_cidade', 'São Paulo - SP') }}</p>
                        <p class="text-sm text-gray-600">{{ __('Telefone') }}: {{ \App\Models\Configuracao::get('igreja_telefone', '(11) 99999-9999') }} | {{ __('Email') }}: {{ \App\Models\Configuracao::get('igreja_email', 'contato@cbav.com') }}</p>
                        <p class="text-sm text-gray-600">{{ __('Documento gerado em') }}: {{ now()->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instruções -->
    <div class="mt-8 bg-blue-50 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-blue-900 mb-3">{{ __('Instruções de Impressão') }}</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
            <div>
                <h5 class="font-semibold mb-2">{{ __('Para PDF:') }}</h5>
                <ul class="list-disc list-inside space-y-1">
                    <li>{{ __('Clique em "Baixar PDF" para gerar o arquivo') }}</li>
                    <li>{{ __('O PDF será gerado em tamanho A4') }}</li>
                    <li>{{ __('Margens adequadas para impressão') }}</li>
                    <li>{{ __('Design profissional e bem estruturado') }}</li>
                </ul>
            </div>
            <div>
                <h5 class="font-semibold mb-2">{{ __('Para Impressão:') }}</h5>
                <ul class="list-disc list-inside space-y-1">
                    <li>{{ __('Clique em "Imprimir" para abrir a página de impressão') }}</li>
                    <li>{{ __('Configure para tamanho A4') }}</li>
                    <li>{{ __('Desative cabeçalhos e rodapés') }}</li>
                    <li>{{ __('Imprima em papel de qualidade') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection 