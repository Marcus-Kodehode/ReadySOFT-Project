{{-- File: resources/views/components/button.blade.php --}}

@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button'
])

@php
    // Variant classes - following design guide specifications
    $variantClasses = [
        'primary' => 'px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium',
        'secondary' => 'px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium',
        'danger' => 'px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors font-medium',
    ];

    // Get classes for current variant
    $classes = $variantClasses[$variant] ?? $variantClasses['primary'];
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
    {{ $slot }}
</button>

{{-- Reusable button component with variant and size props --}}
