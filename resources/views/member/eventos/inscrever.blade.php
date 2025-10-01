@extends('layouts.member')

@section('title', 'Inscrição - ' . $evento->titulo)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Inscrição no Evento</h1>
                <p class="text-gray-600 mt-2">{{ $evento->titulo }}</p>
            </div>
            <a href="{{ route('member.eventos.show', $evento) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulário de Inscrição -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Dados da Inscrição</h2>

                        <form method="POST" action="{{ route('member.eventos.processar-inscricao', $evento) }}">
                            @csrf

                            <!-- Informações do Evento -->
                            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                                <h3 class="font-medium text-blue-900 mb-3">Informações do Evento</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-blue-900">Data:</span>
                                        <span class="text-blue-700">{{ $evento->data_inicio->format('d/m/Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-blue-900">Hora:</span>
                                        <span class="text-blue-700">
                                            {{ $evento->hora_inicio ? $evento->hora_inicio->format('H:i') : 'Não informado' }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-blue-900">Local:</span>
                                        <span class="text-blue-700">{{ $evento->local ?: 'Não informado' }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-blue-900">Valor:</span>
                                        <span class="text-blue-700">{{ $evento->valor_formatado }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($dadosMembro)
                            <!-- Dados Preenchidos Automaticamente -->
                            <div class="bg-green-50 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                    <h3 class="font-medium text-green-900">Dados Preenchidos Automaticamente</h3>
                                </div>
                                <p class="text-sm text-green-700 mt-2">
                                    Seus dados foram preenchidos automaticamente com base no seu cadastro de membro. 
                                    Você pode editar qualquer campo se necessário.
                                </p>
                            </div>
                            @endif

                            <!-- Dados Pessoais -->
                            <div class="mb-6">
                                <h3 class="font-medium text-gray-900 mb-4">Dados Pessoais</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome Completo *</label>
                                        <input type="text" id="nome" name="nome" value="{{ old('nome', $dadosMembro->nome ?? auth()->user()->name) }}" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('nome')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mail *</label>
                                        <input type="email" id="email" name="email" value="{{ old('email', $dadosMembro->email ?? auth()->user()->email) }}" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('email')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">Telefone *</label>
                                        <input type="text" id="telefone" name="telefone" value="{{ old('telefone', $dadosMembro->telefone ?? auth()->user()->telefone) }}" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="(11) 99999-9999">
                                        @error('telefone')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="cpf" class="block text-sm font-medium text-gray-700 mb-2">CPF *</label>
                                        <input type="text" id="cpf" name="cpf" value="{{ old('cpf', $dadosMembro->cpf ?? auth()->user()->cpf) }}" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="000.000.000-00">
                                        @error('cpf')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="data_nascimento" class="block text-sm font-medium text-gray-700 mb-2">Data de Nascimento *</label>
                                        <input type="date" id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento', $dadosMembro->data_nascimento ? $dadosMembro->data_nascimento->format('Y-m-d') : (auth()->user()->data_nascimento ? auth()->user()->data_nascimento->format('Y-m-d') : '')) }}" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('data_nascimento')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="sexo" class="block text-sm font-medium text-gray-700 mb-2">Sexo *</label>
                                        <select id="sexo" name="sexo" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Selecione...</option>
                                            <option value="M" {{ old('sexo', $dadosMembro->sexo ?? auth()->user()->sexo) === 'M' ? 'selected' : '' }}>Masculino</option>
                                            <option value="F" {{ old('sexo', $dadosMembro->sexo ?? auth()->user()->sexo) === 'F' ? 'selected' : '' }}>Feminino</option>
                                        </select>
                                        @error('sexo')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Endereço -->
                            <div class="mb-6">
                                <h3 class="font-medium text-gray-900 mb-4">Endereço</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label for="endereco" class="block text-sm font-medium text-gray-700 mb-2">Endereço</label>
                                        <input type="text" id="endereco" name="endereco" value="{{ old('endereco', $dadosMembro->endereco ?? auth()->user()->endereco) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('endereco')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="cidade" class="block text-sm font-medium text-gray-700 mb-2">Cidade</label>
                                        <input type="text" id="cidade" name="cidade" value="{{ old('cidade', $dadosMembro->cidade ?? auth()->user()->cidade) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('cidade')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                                        <input type="text" id="estado" name="estado" value="{{ old('estado', $dadosMembro->estado ?? auth()->user()->estado) }}" maxlength="2"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('estado')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="cep" class="block text-sm font-medium text-gray-700 mb-2">CEP</label>
                                        <input type="text" id="cep" name="cep" value="{{ old('cep', $dadosMembro->cep ?? auth()->user()->cep) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('cep')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Informações Adicionais -->
                            <div class="mb-6">
                                <h3 class="font-medium text-gray-900 mb-4">Informações Adicionais</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="emergencia_nome" class="block text-sm font-medium text-gray-700 mb-2">Nome de Emergência</label>
                                        <input type="text" id="emergencia_nome" name="emergencia_nome" value="{{ old('emergencia_nome') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('emergencia_nome')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="emergencia_telefone" class="block text-sm font-medium text-gray-700 mb-2">Telefone de Emergência</label>
                                        <input type="text" id="emergencia_telefone" name="emergencia_telefone" value="{{ old('emergencia_telefone') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('emergencia_telefone')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                                        <textarea id="observacoes" name="observacoes" rows="3"
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('observacoes') }}</textarea>
                                        @error('observacoes')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Termos e Condições -->
                            <div class="mb-6">
                                <div class="flex items-start">
                                    <input type="checkbox" id="aceito_termos" name="aceito_termos" value="1" required
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                                    <label for="aceito_termos" class="ml-2 text-sm text-gray-700">
                                        Li e aceito os <a href="#" class="text-blue-600 hover:text-blue-800">termos e condições</a> do evento
                                    </label>
                                </div>
                                @error('aceito_termos')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Botões -->
                            <div class="flex justify-end space-x-4">
                                <a href="{{ route('member.eventos.show', $evento) }}" 
                                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                    Cancelar
                                </a>
                                <button type="submit" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                    <i class="fas fa-check mr-2"></i>Confirmar Inscrição
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Resumo do Evento -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Resumo do Evento</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Evento</span>
                                <span class="text-sm font-medium text-gray-900">{{ $evento->titulo }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Data</span>
                                <span class="text-sm font-medium text-gray-900">{{ $evento->data_inicio->format('d/m/Y') }}</span>
                            </div>

                            @if($evento->hora_inicio)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Hora</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $evento->hora_inicio->format('H:i') }}</span>
                                </div>
                            @endif

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Local</span>
                                <span class="text-sm font-medium text-gray-900">{{ $evento->local ?: 'Não informado' }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Valor</span>
                                <span class="text-sm font-medium text-gray-900">{{ $evento->valor_formatado }}</span>
                            </div>

                            @if($evento->vagas_totais)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Vagas Disponíveis</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $evento->vagas_disponiveis }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informações Importantes -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Importantes</h3>
                        
                        <div class="space-y-3 text-sm text-gray-600">
                            @if($evento->inscricao_obrigatoria)
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 mr-2 mt-0.5"></i>
                                    <span>Inscrição obrigatória para participar</span>
                                </div>
                            @endif

                            @if(!$evento->gratuito)
                                <div class="flex items-start">
                                    <i class="fas fa-credit-card text-blue-500 mr-2 mt-0.5"></i>
                                    <span>Pagamento será processado após confirmação</span>
                                </div>
                            @endif

                            @if($evento->inscricao_ate)
                                <div class="flex items-start">
                                    <i class="fas fa-clock text-orange-500 mr-2 mt-0.5"></i>
                                    <span>Inscrições até {{ $evento->inscricao_ate->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif

                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-500 mr-2 mt-0.5"></i>
                                <span>Você receberá uma confirmação por e-mail</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para telefone
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            
            if (value.length > 0) {
                if (value.length <= 2) {
                    value = `(${value}`;
                } else if (value.length <= 6) {
                    value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
                } else if (value.length <= 10) {
                    value = `(${value.slice(0, 2)}) ${value.slice(2, 6)}-${value.slice(6)}`;
                } else {
                    value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7)}`;
                }
            }
            e.target.value = value;
        });
    }

    // Máscara para CPF
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            
            if (value.length > 0) {
                if (value.length <= 3) {
                    value = value;
                } else if (value.length <= 6) {
                    value = `${value.slice(0, 3)}.${value.slice(3)}`;
                } else if (value.length <= 9) {
                    value = `${value.slice(0, 3)}.${value.slice(3, 6)}.${value.slice(6)}`;
                } else {
                    value = `${value.slice(0, 3)}.${value.slice(3, 6)}.${value.slice(6, 9)}-${value.slice(9)}`;
                }
            }
            e.target.value = value;
        });
    }

    // Máscara para CEP
    const cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 8) value = value.slice(0, 8);
            
            if (value.length > 0) {
                if (value.length <= 5) {
                    value = value;
                } else {
                    value = `${value.slice(0, 5)}-${value.slice(5)}`;
                }
            }
            e.target.value = value;
        });
    }
});
</script>
@endsection 