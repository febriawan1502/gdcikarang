@props(['name', 'title', 'maxWidth' => '2xl'])

@php
$maxWidth = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
][$maxWidth];
@endphp

<div
    x-data="{ show: false, name: '{{ $name }}' }"
    x-show="show"
    x-on:open-modal.window="show = ($event.detail === name)"
    x-on:close-modal.window="if ($event.detail === name) show = false"
    x-on:keydown.escape.window="show = false"
    style="display: none;"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div
        class="bg-white rounded-xl shadow-2xl w-[90%] max-h-[90vh] overflow-y-auto {{ $maxWidth }}"
        x-show="show"
        x-on:click.away="show = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2.5 m-0">
                @if(isset($icon))
                    {{ $icon }}
                @endif
                {{ $title }}
            </h3>
            <button type="button" class="flex items-center justify-center w-8 h-8 rounded-lg border-none bg-gray-100 text-gray-500 cursor-pointer transition-colors hover:bg-gray-200 hover:text-gray-700" x-on:click="show = false">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-6">
            {{ $slot }}
        </div>

        @if(isset($footer))
        <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            {{ $footer }}
        </div>
        @endif
    </div>
</div>
