{{-- File: resources/views/components/badge.blade.php --}}

@props([
    'color' => 'info',
    'size' => 'md'
])

@php
    // Color variant classes
    $colorClasses = [
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'error' => 'bg-red-100 text-red-800',
        'info' => 'bg-blue-100 text-blue-800',
    ];

    // Size classes
    $sizeClasses = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2 py-1 text-xs',
        'lg' => 'px-3 py-1.5 text-sm',
    ];

    // Base classes
    $baseClasses = 'inline-flex items-center font-medium rounded-full';

    // Merge all classes
    $classes = $baseClasses . ' ' . ($colorClasses[$color] ?? $colorClasses['info']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>

{{-- Badge component with color variants (success, warning, error, info) --}}
