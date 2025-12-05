{{-- File: resources/views/public/booking.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->name }} - Book Now</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8" x-data="{ modalOpen: false, selectedResourceId: null, selectedResourceName: '' }">
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ $tenant->name }}</h1>
            <p class="text-lg text-gray-600">{{ $tenant->business_type }}</p>
            @if($tenant->description)
                <p class="mt-4 text-gray-700">{{ $tenant->description }}</p>
            @endif
        </div>

        {{-- Resources Grid --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($tenant->resources as $resource)
                @if($resource->active)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="font-semibold text-lg text-gray-900">{{ $resource->name }}</h3>
                        @if($resource->description)
                            <p class="text-gray-600 text-sm mt-2">{{ $resource->description }}</p>
                        @endif
                        <p class="text-gray-500 text-xs mt-2">Capacity: {{ $resource->capacity }}</p>
                        <button @click="modalOpen = true; selectedResourceId = {{ $resource->id }}; selectedResourceName = '{{ $resource->name }}'" class="w-full mt-4 px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
                            Book Now
                        </button>
                    </div>
                @endif
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">No resources available for booking at this time.</p>
                </div>
            @endforelse
        </div>

        {{-- Booking Modal --}}
        <div x-show="modalOpen" 
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             @keydown.escape.window="modalOpen = false">
            {{-- Backdrop --}}
            <div @click="modalOpen = false" 
                 class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
            
            {{-- Modal Content --}}
            <div class="relative z-10 w-full max-w-md p-6 bg-white rounded-lg shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Book <span x-text="selectedResourceName"></span></h3>
                    <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <p class="mb-6 text-gray-600">Booking form will be implemented in the next task.</p>
                
                <div class="flex justify-end gap-3">
                    <button @click="modalOpen = false" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

{{-- Public booking page - viser tenant info og ressurser for booking --}}
