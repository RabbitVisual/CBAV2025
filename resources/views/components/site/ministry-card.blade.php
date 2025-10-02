@props(['ministerio'])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
    <div class="p-6">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4" style="background-color: {{ $ministerio->cor ?? '#4A90E2' }}">
                <i class="fas {{ $ministerio->icone ?? 'fa-users' }} text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $ministerio->nome }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $ministerio->users_count }} {{ Str::plural('membro', $ministerio->users_count) }}</p>
            </div>
        </div>
        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
            {{ Str::limit($ministerio->descricao, 100) }}
        </p>
    </div>
</div>