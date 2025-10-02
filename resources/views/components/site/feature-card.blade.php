@props(['icon', 'title', 'description'])

<div class="text-center p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-lg hover:bg-white dark:hover:bg-gray-700 transition-all duration-300">
    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas {{ $icon }} text-2xl text-blue-600 dark:text-blue-400"></i>
    </div>
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $title }}</h3>
    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ $description }}</p>
</div>