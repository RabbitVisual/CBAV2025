@props(['activeTab' => 'geral'])

<div x-data="{ activeTab: '{{ $activeTab }}' }">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Configurações do Sistema</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Gerencie as configurações globais da aplicação.</p>
    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 dark:border-gray-700 mb-8">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button @click="activeTab = 'geral'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'geral', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'geral' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                Geral
            </button>
            <button @click="activeTab = 'pagamento'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'pagamento', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'pagamento' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                Pagamento
            </button>
            <button @click="activeTab = 'email'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'email', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'email' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                Email
            </button>
             <button @click="activeTab = 'seguranca'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'seguranca', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'seguranca' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                Segurança
            </button>
            <button @click="activeTab = 'cache'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'cache', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'cache' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                Cache & Backup
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <form action="{{ route('admin.system.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="hidden" name="active_tab" x-model="activeTab">

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
            <div class="p-6">
                {{ $slot }}
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end items-center gap-x-3">
                <x-admin.button-secondary type="button" onclick="window.location.href='{{ route('admin.system.settings.index') }}'">
                    Cancelar
                </x-admin.button-secondary>
                <x-admin.button-primary type="submit">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Configurações
                </x-admin.button-primary>
            </div>
        </div>
    </form>
</div>