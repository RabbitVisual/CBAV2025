@extends('layouts.admin')

@section('title', __('Templates de Pauta'))

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Templates de Pauta') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('Gerencie templates reutilizáveis para pautas de reuniões') }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.council.agenda.template.history') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-history mr-2"></i>
                {{ __('Histórico') }}
            </a>
            <a href="{{ route('admin.council.agenda.template.export') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-download mr-2"></i>
                {{ __('Exportar') }}
            </a>
            <a href="{{ route('admin.council.agenda.template.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                {{ __('Novo Template') }}
            </a>
            <a href="{{ route('admin.council.dashboard') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Voltar ao Dashboard') }}
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <form method="GET" action="{{ route('admin.council.agenda.template.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Buscar') }}
                    </label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="{{ __('Nome ou descrição...') }}">
                </div>

                <div>
                    <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Categoria') }}
                    </label>
                    <select id="categoria" 
                            name="categoria"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todas') }}</option>
                        <option value="reuniao_ordinaria" {{ request('categoria') == 'reuniao_ordinaria' ? 'selected' : '' }}>{{ __('Reunião Ordinária') }}</option>
                        <option value="reuniao_extraordinaria" {{ request('categoria') == 'reuniao_extraordinaria' ? 'selected' : '' }}>{{ __('Reunião Extraordinária') }}</option>
                        <option value="votacao" {{ request('categoria') == 'votacao' ? 'selected' : '' }}>{{ __('Votação') }}</option>
                        <option value="evento" {{ request('categoria') == 'evento' ? 'selected' : '' }}>{{ __('Evento') }}</option>
                        <option value="geral" {{ request('categoria') == 'geral' ? 'selected' : '' }}>{{ __('Geral') }}</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Status') }}
                    </label>
                    <select id="status" 
                            name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>{{ __('Ativo') }}</option>
                        <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>{{ __('Inativo') }}</option>
                        <option value="rascunho" {{ request('status') == 'rascunho' ? 'selected' : '' }}>{{ __('Rascunho') }}</option>
                    </select>
                </div>

                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Ordenar por') }}
                    </label>
                    <select id="sort" 
                            name="sort"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>{{ __('Mais Recentes') }}</option>
                        <option value="nome" {{ request('sort') == 'nome' ? 'selected' : '' }}>{{ __('Nome') }}</option>
                        <option value="categoria" {{ request('sort') == 'categoria' ? 'selected' : '' }}>{{ __('Categoria') }}</option>
                        <option value="usos" {{ request('sort') == 'usos' ? 'selected' : '' }}>{{ __('Mais Usados') }}</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-search mr-2"></i>
                    {{ __('Filtrar') }}
                </button>
                <a href="{{ route('admin.council.agenda.template.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    {{ __('Limpar') }}
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de Templates -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($templates && $templates->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Template') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Categoria') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Itens') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Usos') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Ações') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($templates as $template)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $template->nome }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($template->descricao, 100) }}</div>
                                        <div class="text-xs text-gray-400">{{ __('Criado por') }} {{ $template->criadoPor->name ?? 'Sistema' }} {{ __('em') }} {{ $template->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $template->categoria_color }}">
                                        {{ $template->categoria_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $template->itens->count() }} {{ __('itens') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $template->conselhos->count() }} {{ __('vezes') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $template->status_color }}">
                                        {{ $template->status_text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button onclick="usarTemplate({{ $template->id }})" 
                                                class="text-blue-600 hover:text-blue-900" 
                                                title="{{ __('Usar Template') }}">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <a href="{{ route('admin.council.agenda.template.edit', $template) }}" 
                                           class="text-green-600 hover:text-green-900" 
                                           title="{{ __('Editar') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="duplicarTemplate({{ $template->id }})" 
                                                class="text-purple-600 hover:text-purple-900" 
                                                title="{{ __('Duplicar') }}">
                                            <i class="fas fa-clone"></i>
                                        </button>
                                        <button onclick="excluirTemplate({{ $template->id }})" 
                                                class="text-red-600 hover:text-red-900" 
                                                title="{{ __('Excluir') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-copy text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Nenhum template encontrado') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('Crie seu primeiro template de pauta para agilizar o processo.') }}</p>
                <button onclick="criarTemplate()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('Criar Primeiro Template') }}
                </button>
            </div>
        @endif
    </div>

    <!-- Paginação -->
    @if($templates && method_exists($templates, 'hasPages') && $templates->hasPages())
        <div class="mt-6">
            {{ $templates->links() }}
        </div>
    @endif
</div>

@include('components.modals')

@push('scripts')
<script>
// Função para criar template
function criarTemplate() {
    window.location.href = '{{ route("admin.council.agenda.template.create") }}';
}

// Função para usar template
function usarTemplate(templateId) {
    window.location.href = `/admin/council/agenda/templates/${templateId}/usar`;
}

// Função para duplicar template
function duplicarTemplate(templateId) {
    showConfirmModal(
        '{{ __("Duplicar Template") }}',
        '{{ __("Tem certeza que deseja duplicar este template?") }}',
        () => {
            fetch(`/admin/council/agenda/templates/${templateId}/duplicar`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessModal(data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showErrorModal(data.message);
                }
            })
            .catch(error => {
                showErrorModal('{{ __("Erro ao duplicar template") }}');
            });
        }
    );
}

// Função para excluir template
function excluirTemplate(templateId) {
    showConfirmModal(
        '{{ __("Excluir Template") }}',
        '{{ __("Tem certeza que deseja excluir este template? Esta ação não pode ser desfeita.") }}',
        () => {
            fetch(`/admin/council/agenda/templates/${templateId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessModal(data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showErrorModal(data.message);
                }
            })
            .catch(error => {
                showErrorModal('{{ __("Erro ao excluir template") }}');
            });
        }
    );
}
</script>
@endpush
@endsection 