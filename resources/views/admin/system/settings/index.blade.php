@extends('layouts.app')

@section('title', 'Configurações do Sistema')

@section('content')
    <x-admin.settings-layout :active-tab="session('active_tab', 'geral')">

        {{-- O conteúdo de cada aba é inserido aqui. --}}
        {{-- A lógica de qual aba mostrar é controlada pelo Alpine.js no componente settings-layout --}}

        <x-admin.settings.general-tab :configuracoes="$configuracoes" />

        {{-- As abas abaixo ainda precisam ser refatoradas para usar os componentes do Design System --}}
        {{-- <x-admin.settings.payment-tab :configuracoes="$configuracoes" /> --}}
        {{-- <x-admin.settings.email-tab :configuracoes="$configuracoes" /> --}}
        {{-- <x-admin.settings.security-tab :configuracoes="$configuracoes" /> --}}
        {{-- <x-admin.settings.cache-backup-tab :configuracoes="$configuracoes" /> --}}

        <div x-show="activeTab === 'pagamento'">
             <p class="text-center text-gray-500 dark:text-gray-400 p-8">A refatoração da aba de Pagamento está em andamento.</p>
        </div>
        <div x-show="activeTab === 'email'">
             <p class="text-center text-gray-500 dark:text-gray-400 p-8">A refatoração da aba de Email está em andamento.</p>
        </div>
        <div x-show="activeTab === 'seguranca'">
             <p class="text-center text-gray-500 dark:text-gray-400 p-8">A refatoração da aba de Segurança está em andamento.</p>
        </div>
        <div x-show="activeTab === 'cache'">
             <p class="text-center text-gray-500 dark:text-gray-400 p-8">A refatoração da aba de Cache & Backup está em andamento.</p>
        </div>

    </x-admin.settings-layout>
@endsection

@push('scripts')
<script>
    // Funções auxiliares, como limpar cache, podem ser movidas para um arquivo JS dedicado
    function limparCache() {
        if (confirm('Tem certeza que deseja limpar todo o cache da aplicação?')) {
            // Implementar chamada AJAX para a rota de limpar cache
            console.log('Limpando cache...');
        }
    }
</script>
@endpush