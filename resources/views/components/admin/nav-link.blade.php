@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 bg-gray-50 text-blue-600 dark:bg-gray-700 dark:text-white'
            : 'flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-blue-600 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white';
@endphp

<li>
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
</li>