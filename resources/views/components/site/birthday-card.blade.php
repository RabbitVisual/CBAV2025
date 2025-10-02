@props(['user'])

<div class="text-center">
    <div class="relative inline-block">
        <img class="h-24 w-24 rounded-full object-cover mx-auto shadow-lg border-4 border-white dark:border-gray-700"
             src="{{ $user->foto_url }}"
             alt="Foto de {{ $user->name }}">
        <div class="absolute bottom-0 right-0 bg-pink-500 text-white rounded-full p-1.5 shadow-md">
            <i class="fas fa-birthday-cake text-xs"></i>
        </div>
    </div>
    <h4 class="mt-4 text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $user->name }}</h4>
    @if($user->profile->data_nascimento)
        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->profile->data_nascimento->format('d/m') }}</p>
    @endif
</div>