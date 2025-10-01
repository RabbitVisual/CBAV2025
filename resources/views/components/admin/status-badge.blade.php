@props([
    'type' => 'success', // success, danger, warning, info
])

@php
    $baseClasses = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full';
    $typeClasses = [
        'success' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'danger' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
    ];
    $classes = "$baseClasses {$typeClasses[$type]}";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>