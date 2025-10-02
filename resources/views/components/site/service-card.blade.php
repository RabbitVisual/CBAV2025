@props(['icon', 'title', 'time', 'description'])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center border-t-4 border-blue-500 hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
    <div class="w-20 h-20 bg-blue-100 dark:bg-blue-900/50 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fas {{ $icon }} text-3xl text-blue-600 dark:text-blue-400"></i>
    </div>
    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $title }}</h3>
    <p class="text-3xl font-light text-blue-500 dark:text-blue-400 mb-4">{{ $time }}</p>
    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $description }}</p>
</div>