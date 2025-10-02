@props(['name', 'title', 'enabled' => false, 'icon' => ''])

<div class="border border-gray-200 dark:border-gray-700 rounded-lg" x-data="{ enabled: {{ $enabled ? 'true' : 'false' }} }">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center gap-x-3">
            @if($icon)
                <i class="{{ $icon }} text-xl text-gray-500"></i>
            @endif
            <h4 class="text-base font-semibold text-gray-900 dark:text-white">{{ $title }}</h4>
        </div>
        <x-admin.toggle-switch :name="$name.'_enabled'" x-model="enabled" />
    </div>
    <div class="p-6 bg-gray-50 dark:bg-gray-800/50" x-show="enabled" x-transition>
        <div class="space-y-6">
            {{ $slot }}
        </div>
    </div>
</div>