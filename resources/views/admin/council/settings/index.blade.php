@extends('layouts.admin')

@section('title', __('Configurações do Conselho'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Configurações do Conselho') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Configure as opções e comportamentos do sistema de conselho') }}</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="salvarConfiguracoes()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                {{ __('Salvar Configurações') }}
            </button>
            <a href="{{ route('admin.council.dashboard') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar ao Dashboard') }}
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.council.settings.update') }}" id="formConfiguracoes">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Configurações Gerais -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-cog mr-3 text-blue-500"></i>
                    {{ __('Configurações Gerais') }}
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="quorum_padrao" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Quórum Padrão (%)') }}
                        </label>
                        <input type="number" 
                               id="quorum_padrao" 
                               name="quorum_padrao" 
                               value="{{ $configuracoes['quorum_padrao'] ?? 50 }}"
                               min="1" 
                               max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">{{ __('Percentual mínimo de participantes para validar reuniões') }}</p>
                    </div>

                    <div>
                        <label for="duracao_padrao" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Duração Padrão (minutos)') }}
                        </label>
                        <input type="number" 
                               id="duracao_padrao" 
                               name="duracao_padrao" 
                               value="{{ $configuracoes['duracao_padrao'] ?? 120 }}"
                               min="30" 
                               max="480"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">{{ __('Duração padrão das reuniões em minutos') }}</p>
                    </div>

                    <div>
                        <label for="max_pautas" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Máximo de Pautas por Reunião') }}
                        </label>
                        <input type="number" 
                               id="max_pautas" 
                               name="max_pautas" 
                               value="{{ $configuracoes['max_pautas'] ?? 10 }}"
                               min="1" 
                               max="50"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">{{ __('Número máximo de pautas por reunião') }}</p>
                    </div>

                    <div>
                        <label for="tempo_votacao" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Tempo de Votação (minutos)') }}
                        </label>
                        <input type="number" 
                               id="tempo_votacao" 
                               name="tempo_votacao" 
                               value="{{ $configuracoes['tempo_votacao'] ?? 5 }}"
                               min="1" 
                               max="60"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">{{ __('Tempo padrão para votações') }}</p>
                    </div>
                </div>
            </div>

            <!-- Configurações de Votação -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-vote-yea mr-3 text-purple-500"></i>
                    {{ __('Configurações de Votação') }}
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="voto_secreto_padrao" 
                                   value="1"
                                   {{ ($configuracoes['voto_secreto_padrao'] ?? false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Voto secreto por padrão') }}</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Ativar voto secreto como padrão') }}</p>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="permitir_abstencao" 
                                   value="1"
                                   {{ ($configuracoes['permitir_abstencao'] ?? true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Permitir abstenção') }}</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Permitir que participantes se abstenham') }}</p>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="justificativa_obrigatoria" 
                                   value="1"
                                   {{ ($configuracoes['justificativa_obrigatoria'] ?? false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Justificativa obrigatória') }}</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Exigir justificativa para votos contrários') }}</p>
                    </div>

                    <div>
                        <label for="maioria_qualificada" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Maioria Qualificada (%)') }}
                        </label>
                        <input type="number" 
                               id="maioria_qualificada" 
                               name="maioria_qualificada" 
                               value="{{ $configuracoes['maioria_qualificada'] ?? 66 }}"
                               min="51" 
                               max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">{{ __('Percentual para maioria qualificada') }}</p>
                    </div>
                </div>
            </div>

            <!-- Configurações de Notificação -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-bell mr-3 text-yellow-500"></i>
                    {{ __('Configurações de Notificação') }}
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="notificar_reuniao" 
                                   value="1"
                                   {{ ($configuracoes['notificar_reuniao'] ?? true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Notificar nova reunião') }}</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Enviar notificação quando nova reunião for criada') }}</p>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="notificar_votacao" 
                                   value="1"
                                   {{ ($configuracoes['notificar_votacao'] ?? true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Notificar votações') }}</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Enviar notificação quando votação for iniciada') }}</p>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="notificar_resultado" 
                                   value="1"
                                   {{ ($configuracoes['notificar_resultado'] ?? true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Notificar resultados') }}</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Enviar notificação com resultado da votação') }}</p>
                    </div>

                    <div>
                        <label for="antecedencia_notificacao" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Antecedência para Notificação (horas)') }}
                        </label>
                        <input type="number" 
                               id="antecedencia_notificacao" 
                               name="antecedencia_notificacao" 
                               value="{{ $configuracoes['antecedencia_notificacao'] ?? 24 }}"
                               min="1" 
                               max="168"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">{{ __('Horas de antecedência para notificar reunião') }}</p>
                    </div>
                </div>
            </div>

            <!-- Configurações de Segurança -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-shield-alt mr-3 text-red-500"></i>
                    {{ __('Configurações de Segurança') }}
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="registrar_logs" 
                                   value="1"
                                   {{ ($configuracoes['registrar_logs'] ?? true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Registrar logs de atividade') }}</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Manter registro de todas as atividades') }}</p>
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="backup_automatico" 
                                   value="1"
                                   {{ ($configuracoes['backup_automatico'] ?? true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ __('Backup automático') }}</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Fazer backup automático dos dados') }}</p>
                    </div>

                    <div>
                        <label for="tempo_sessao" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Tempo de Sessão (minutos)') }}
                        </label>
                        <input type="number" 
                               id="tempo_sessao" 
                               name="tempo_sessao" 
                               value="{{ $configuracoes['tempo_sessao'] ?? 30 }}"
                               min="5" 
                               max="480"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">{{ __('Tempo de inatividade para logout') }}</p>
                    </div>

                    <div>
                        <label for="max_tentativas" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Máximo de Tentativas de Login') }}
                        </label>
                        <input type="number" 
                               id="max_tentativas" 
                               name="max_tentativas" 
                               value="{{ $configuracoes['max_tentativas'] ?? 3 }}"
                               min="1" 
                               max="10"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">{{ __('Número máximo de tentativas de login') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="mt-8 flex justify-end space-x-3">
            <a href="{{ route('admin.council.dashboard') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                {{ __('Cancelar') }}
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                {{ __('Salvar Configurações') }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function salvarConfiguracoes() {
    document.getElementById('formConfiguracoes').submit();
}

// Validação do formulário
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formConfiguracoes');
    
    form.addEventListener('submit', function(e) {
        const quorum = document.getElementById('quorum_padrao').value;
        const duracao = document.getElementById('duracao_padrao').value;
        const maxPautas = document.getElementById('max_pautas').value;
        const tempoVotacao = document.getElementById('tempo_votacao').value;
        
        if (quorum < 1 || quorum > 100) {
            e.preventDefault();
            alert('{{ __("Quórum deve estar entre 1 e 100%") }}');
            return false;
        }
        
        if (duracao < 30 || duracao > 480) {
            e.preventDefault();
            alert('{{ __("Duração deve estar entre 30 e 480 minutos") }}');
            return false;
        }
        
        if (maxPautas < 1 || maxPautas > 50) {
            e.preventDefault();
            alert('{{ __("Máximo de pautas deve estar entre 1 e 50") }}');
            return false;
        }
        
        if (tempoVotacao < 1 || tempoVotacao > 60) {
            e.preventDefault();
            alert('{{ __("Tempo de votação deve estar entre 1 e 60 minutos") }}');
            return false;
        }
    });
});
</script>
@endpush
@endsection 