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
    <div class="max-w-7xl mx-auto px-4 py-8">
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
                        <button class="w-full mt-4 px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
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
    </div>
</body>
</html>

{{-- Public booking page - viser tenant info og ressurser for booking --}}
