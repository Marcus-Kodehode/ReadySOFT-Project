{{-- File: resources/views/components/badge.blade.php --}}

@props([
    'color' => 'info',
    'size' => 'md'
])

@php
    // Color variant classes - following design guide specifications
    $colorClasses = [
        'success' => 'px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full',
        'warning' => 'px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full',
        'error' => 'px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full',
        'info' => 'px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full',
        'gray' => 'px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full',
    ];

    // Get classes for current color
    $classes = $colorClasses[$color] ?? $colorClasses['info'];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>

{{-- Badge component with color variants (success, warning, error, info) --}}
