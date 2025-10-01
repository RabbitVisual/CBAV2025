@extends('layouts.admin')

@section('title', 'Documentos de Declaração Anual - Gestão Financeira')

@section('content')
<div class="space-y-6">
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Documentos de Declaração Anual</h1>
            <p class="text-gray-600">Gerencie documentos para declaração anual da igreja</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.finance.documentos-declaracao-anual.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Novo Documento
            </a>
            <a href="{{ route('admin.finance.documentos-declaracao-anual.export') }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-download mr-2"></i>Exportar
            </a>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total de Documentos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Documentos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($estatisticas['total_documentos']) }}</p>
                </div>
            </div>
        </div>

        <!-- Documentos Validados -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Validados</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($estatisticas['documentos_validados']) }}</p>
                    <p class="text-sm text-green-600">R$ {{ number_format($estatisticas['valor_total_validados'], 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Documentos Pendentes -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pendentes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($estatisticas['documentos_pendentes']) }}</p>
                </div>
            </div>
        </div>

        <!-- Igrejas Ativas -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-church text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Igrejas Ativas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($estatisticas['igrejas_ativas']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Filtros</h3>
        </div>
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Protocolo, número ou igreja">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Documento</label>
                    <select name="tipo_documento" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach(App\Models\DocumentoDeclaracaoAnual::TIPOS_DOCUMENTO as $key => $tipo)
                            <option value="{{ $key }}" {{ request('tipo_documento') == $key ? 'selected' : '' }}>
                                {{ $tipo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach(App\Models\DocumentoDeclaracaoAnual::STATUS as $key => $status)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ano Exercício</label>
                    <select name="ano_exercicio" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @for($ano = date('Y'); $ano >= 2020; $ano--)
                            <option value="{{ $ano }}" {{ request('ano_exercicio') == $ano ? 'selected' : '' }}>
                                {{ $ano }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="md:col-span-2 lg:col-span-4 flex space-x-3">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Filtrar
                    </button>
                    <a href="{{ route('admin.finance.documentos-declaracao-anual.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Documentos -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Documentos de Declaração Anual</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Protocolo RF
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Igreja
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ano
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Valor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Validação
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($documentos as $documento)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                {{ $documento->protocolo_receita }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $documento->igreja->nome ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ App\Models\DocumentoDeclaracaoAnual::TIPOS_DOCUMENTO[$documento->tipo_documento] ?? $documento->tipo_documento }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $documento->ano_exercicio }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                R$ {{ number_format($documento->valor_total, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $documento->status === 'VALIDADO' ? 'bg-green-100 text-green-800' : 
                                       ($documento->status === 'PENDENTE' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($documento->status === 'VENCIDO' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ App\Models\DocumentoDeclaracaoAnual::STATUS[$documento->status] ?? $documento->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($documento->validado_em)
                                    {{ $documento->validado_em->format('d/m/Y H:i') }}
                                    <br>
                                    <span class="text-xs text-gray-500">
                                        por {{ $documento->validadoPor->name ?? 'N/A' }}
                                    </span>
                                @else
                                    <span class="text-gray-500">Não validado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.finance.documentos-declaracao-anual.show', $documento) }}" 
                                       class="text-blue-600 hover:text-blue-900" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.finance.documentos-declaracao-anual.edit', $documento) }}" 
                                       class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.finance.documentos-declaracao-anual.pdf', $documento) }}" 
                                       class="text-green-600 hover:text-green-900" title="Gerar PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <button onclick="excluirDocumento({{ $documento->id }}, '{{ $documento->protocolo_receita }}')" 
                                            class="text-red-600 hover:text-red-900" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                Nenhum documento de declaração anual encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($documentos->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $documentos->links() }}
            </div>
        @endif
    </div>
</div>

@include('components.modals')

@push('scripts')
<script>
// Função para excluir documento
function excluirDocumento(documentoId, protocolo) {
    showConfirmModal(
        'Confirmar Exclusão',
        `Tem certeza que deseja excluir o documento ${protocolo}?\n\nEsta ação não pode ser desfeita e todos os dados relacionados serão perdidos.`,
        function() {
            // Criar formulário dinâmico para exclusão
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/finance/documentos-declaracao-anual/${documentoId}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    );
}

// Mostrar toast de sucesso se houver mensagem de sucesso
@if(session('success'))
    showToast('{{ session('success') }}', 'success');
@endif

// Mostrar toast de erro se houver mensagem de erro
@if(session('error'))
    showToast('{{ session('error') }}', 'error');
@endif

// Mostrar toast de aviso se houver mensagem de aviso
@if(session('warning'))
    showToast('{{ session('warning') }}', 'warning');
@endif

// Mostrar toast de informação se houver mensagem de info
@if(session('info'))
    showToast('{{ session('info') }}', 'info');
@endif
</script>
@endpush
@endsection 