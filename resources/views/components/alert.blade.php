{{-- File: resources/views/components/alert.blade.php --}}

@props([
    'type' => 'info',
    'title' => null,
    'dismissible' => false
])

@php
    // Type variant classes
    $typeClasses = [
        'success' => [
            'container' => 'border-green-500 bg-green-50',
            'icon' => 'text-green-500',
            'title' => 'text-green-800',
            'message' => 'text-green-700',
        ],
        'error' => [
            'container' => 'border-red-500 bg-red-50',
            'icon' => 'text-red-500',
            'title' => 'text-red-800',
            'message' => 'text-red-700',
        ],
        'warning' => [
            'container' => 'border-yellow-500 bg-yellow-50',
            'icon' => 'text-yellow-500',
            'title' => 'text-yellow-800',
            'message' => 'text-yellow-700',
        ],
        'info' => [
            'container' => 'border-blue-500 bg-blue-50',
            'icon' => 'text-blue-500',
            'title' => 'text-blue-800',
            'message' => 'text-blue-700',
        ],
    ];

    // Icons for each type
    $icons = [
        'success' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        'error' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        'warning' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
        'info' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
    ];

    // Get classes for current type
    $currentType = $typeClasses[$type] ?? $typeClasses['info'];
    $currentIcon = $icons[$type] ?? $icons['info'];

    // Base classes
    $baseClasses = 'p-4 border-l-4 rounded';
    $containerClasses = $baseClasses . ' ' . $currentType['container'];
@endphp

<div {{ $attributes->merge(['class' => $containerClasses]) }} @if($dismissible) x-data="{ show: true }" x-show="show" x-transition @endif>
    <div class="flex items-start gap-3">
        <div class="flex-shrink-0 {{ $currentType['icon'] }}">
            {!! $currentIcon !!}
        </div>
        <div class="flex-1">
            @if($title)
                <p class="text-sm font-medium {{ $currentType['title'] }}">{{ $title }}</p>
            @endif
            <div class="text-sm {{ $currentType['message'] }} {{ $title ? 'mt-1' : '' }}">
                {{ $slot }}
            </div>
        </div>
        @if($dismissible)
            <button @click="show = false" type="button" class="flex-shrink-0 {{ $currentType['icon'] }} hover:opacity-75">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        @endif
    </div>
</div>

{{-- Alert component with type variants (success, error, warning, info) and optional dismissible functionality --}}
