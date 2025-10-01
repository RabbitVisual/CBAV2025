@extends('layouts.admin')

@section('title', __('Controle de Presença - Conselho'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Controle de Presença') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Conselho:') }} <strong>{{ $conselho->titulo }}</strong></p>
        </div>
        <div class="flex space-x-3">
            <button onclick="salvarPresenca()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                {{ __('Salvar Presença') }}
            </button>
            <a href="{{ route('admin.council.show', $conselho) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar ao Conselho') }}
            </a>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-users text-blue-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Total de Participantes') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['total_participantes'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check text-green-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Presentes') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['presentes'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-times text-red-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Ausentes') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['ausentes'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-percentage text-purple-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">{{ __('Quórum') }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $estatisticas['quorum_percentual'] }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações do Conselho -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-info-circle mr-3 text-blue-500"></i>
            {{ __('Informações do Conselho') }}
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-700">{{ __('Data da Reunião') }}</h3>
                <p class="text-lg font-semibold text-gray-900">{{ $conselho->data_reuniao->format('d/m/Y') }}</p>
            </div>
            
            <div>
                <h3 class="text-sm font-medium text-gray-700">{{ __('Horário') }}</h3>
                <p class="text-lg font-semibold text-gray-900">{{ $conselho->hora_inicio }} - {{ $conselho->hora_fim }}</p>
            </div>
            
            <div>
                <h3 class="text-sm font-medium text-gray-700">{{ __('Local') }}</h3>
                <p class="text-lg font-semibold text-gray-900">{{ $conselho->local ?? 'Não informado' }}</p>
            </div>
            
            <div>
                <h3 class="text-sm font-medium text-gray-700">{{ __('Quórum Mínimo') }}</h3>
                <p class="text-lg font-semibold text-gray-900">{{ $conselho->quorum_minimo }} participantes</p>
            </div>
            
            <div>
                <h3 class="text-sm font-medium text-gray-700">{{ __('Quórum Atual') }}</h3>
                <p class="text-lg font-semibold {{ $conselho->quorum_atingido ? 'text-green-600' : 'text-red-600' }}">
                    {{ $conselho->quorum_atual }} participantes
                </p>
            </div>
            
            <div>
                <h3 class="text-sm font-medium text-gray-700">{{ __('Status') }}</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $conselho->status_color }}">
                    {{ $conselho->status_text }}
                </span>
            </div>
        </div>
    </div>

    <!-- Lista de Participantes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-list mr-3 text-green-500"></i>
                {{ __('Lista de Participantes') }}
            </h2>
        </div>
        
        <form action="{{ route('admin.council.attendance.update', $conselho) }}" method="POST" id="formPresenca">
            @csrf
            @method('PUT')
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Participante') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Função') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Presença') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Horário de Chegada') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Observações') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($participantes as $participante)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($participante->user->foto_existe)
                                                <img class="h-10 w-10 rounded-full" 
                                                     src="{{ $participante->user->foto_url }}" 
                                                     alt="{{ $participante->user->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ $participante->user->iniciais }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $participante->user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $participante->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $participante->funcao_color }}">
                                        {{ $participante->funcao_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <label class="flex items-center">
                                            <input type="radio" 
                                                   name="presenca[{{ $participante->id }}]" 
                                                   value="presente"
                                                   {{ $participante->status == 'presente' ? 'checked' : '' }}
                                                   class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ __('Presente') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" 
                                                   name="presenca[{{ $participante->id }}]" 
                                                   value="ausente"
                                                   {{ $participante->status == 'ausente' ? 'checked' : '' }}
                                                   class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ __('Ausente') }}</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" 
                                                   name="presenca[{{ $participante->id }}]" 
                                                   value="justificado"
                                                   {{ $participante->status == 'justificado' ? 'checked' : '' }}
                                                   class="rounded-full border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ __('Justificado') }}</span>
                                        </label>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="time" 
                                           name="horario_chegada[{{ $participante->id }}]"
                                           value="{{ $participante->horario_chegada }}"
                                           class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="text" 
                                           name="observacoes[{{ $participante->id }}]"
                                           value="{{ $participante->observacoes }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="{{ __('Observações...') }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <!-- Ações em Lote -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Ações em Lote') }}</h3>
        
        <div class="flex space-x-4">
            <button onclick="marcarTodosPresentes()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-check mr-2"></i>
                {{ __('Marcar Todos Presentes') }}
            </button>
            
            <button onclick="marcarTodosAusentes()" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-times mr-2"></i>
                {{ __('Marcar Todos Ausentes') }}
            </button>
            
            <button onclick="limparPresenca()" 
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-eraser mr-2"></i>
                {{ __('Limpar Presença') }}
            </button>
        </div>
    </div>

    <!-- Relatório de Presença -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Relatório de Presença') }}</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('Resumo') }}</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('Total de Participantes:') }}</span>
                        <span class="text-sm font-medium">{{ $estatisticas['total_participantes'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('Presentes:') }}</span>
                        <span class="text-sm font-medium text-green-600">{{ $estatisticas['presentes'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('Ausentes:') }}</span>
                        <span class="text-sm font-medium text-red-600">{{ $estatisticas['ausentes'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">{{ __('Justificados:') }}</span>
                        <span class="text-sm font-medium text-yellow-600">{{ $estatisticas['justificados'] }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-sm font-medium text-gray-700">{{ __('Quórum:') }}</span>
                        <span class="text-sm font-medium {{ $conselho->quorum_atingido ? 'text-green-600' : 'text-red-600' }}">
                            {{ $estatisticas['quorum_percentual'] }}%
                        </span>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">{{ __('Ações') }}</h4>
                <div class="space-y-2">
                    <button onclick="exportarRelatorio()" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-download mr-2"></i>
                        {{ __('Exportar Relatório') }}
                    </button>
                    
                    <button onclick="imprimirRelatorio()" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-print mr-2"></i>
                        {{ __('Imprimir Relatório') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function salvarPresenca() {
    document.getElementById('formPresenca').submit();
}

function marcarTodosPresentes() {
    if (confirm('{{ __("Deseja marcar todos os participantes como presentes?") }}')) {
        const radioButtons = document.querySelectorAll('input[type="radio"][value="presente"]');
        radioButtons.forEach(radio => radio.checked = true);
    }
}

function marcarTodosAusentes() {
    if (confirm('{{ __("Deseja marcar todos os participantes como ausentes?") }}')) {
        const radioButtons = document.querySelectorAll('input[type="radio"][value="ausente"]');
        radioButtons.forEach(radio => radio.checked = true);
    }
}

function limparPresenca() {
    if (confirm('{{ __("Deseja limpar todas as marcações de presença?") }}')) {
        const radioButtons = document.querySelectorAll('input[type="radio"]');
        radioButtons.forEach(radio => radio.checked = false);
    }
}

function exportarRelatorio() {
    window.open('{{ route("admin.council.attendance.export", $conselho) }}', '_blank');
}

function imprimirRelatorio() {
    window.open('{{ route("admin.council.attendance.print", $conselho) }}', '_blank');
}

// Auto-save a cada 30 segundos
setInterval(function() {
    const form = document.getElementById('formPresenca');
    if (form) {
        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    }
}, 30000);
</script>
@endpush
@endsection 