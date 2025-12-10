{{-- File: resources/views/components/button.blade.php --}}

@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button'
])

@php
    // Variant classes
    $variantClasses = [
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 border-transparent',
        'secondary' => 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 focus:ring-blue-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 border-transparent',
    ];

    // Size classes
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
    ];

    // Base classes
    $baseClasses = 'inline-flex items-center border rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition-colors';

    // Merge all classes
    $classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
    {{ $slot }}
</button>

{{-- Reusable button component with variant and size props --}}
