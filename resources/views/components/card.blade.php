{{-- File: resources/views/components/card.blade.php --}}

@props([
    'padding' => true,
])

@php
    // Base classes for the card container
    $baseClasses = 'bg-white border border-gray-200 rounded-lg shadow-sm';
    
    // Padding classes (can be disabled for custom layouts)
    $paddingClasses = $padding ? 'p-6' : '';
    
    // Merge all classes
    $classes = $baseClasses . ' ' . $paddingClasses;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @isset($header)
        <div class="mb-4 pb-4 border-b border-gray-200">
            {{ $header }}
        </div>
    @endisset

    <div>
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="mt-4 pt-4 border-t border-gray-200">
            {{ $footer }}
        </div>
    @endisset
</div>

{{-- Reusable card component with optional header and footer slots --}}
