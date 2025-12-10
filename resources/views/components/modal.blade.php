{{-- File: resources/views/components/modal.blade.php --}}

@props([
    'title' => '',
    'maxWidth' => 'md'
])

@php
    // Max width classes
    $maxWidthClasses = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
    ];
    
    $widthClass = $maxWidthClasses[$maxWidth] ?? $maxWidthClasses['md'];
@endphp

<div x-data="{ open: false }" {{ $attributes }}>
    {{-- Trigger slot - button or element that opens the modal --}}
    @isset($trigger)
        <div @click="open = true">
            {{ $trigger }}
        </div>
    @endisset
    
    {{-- Modal overlay and container --}}
    <div x-show="open" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="open = false">
        
        {{-- Backdrop --}}
        <div @click="open = false" 
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50"></div>
        
        {{-- Modal content --}}
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative z-10 w-full {{ $widthClass }} p-6 bg-white rounded-lg shadow-xl">
            
            {{-- Header with title --}}
            @if($title)
                <h3 class="mb-4 text-lg font-semibold text-gray-900">{{ $title }}</h3>
            @endif
            
            {{-- Content --}}
            <div class="mb-6 text-gray-600">
                {{ $slot }}
            </div>
            
            {{-- Footer with actions --}}
            @isset($footer)
                <div class="flex justify-end gap-3">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>

{{-- Alpine.js powered modal component with title prop and customizable content --}}
