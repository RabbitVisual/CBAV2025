@props(['disabled' => false, 'rows' => 4])

<textarea
    rows="{{ $rows }}"
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge(['class' => 'mt-1 block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm']) !!}
>{{ $slot }}</textarea>