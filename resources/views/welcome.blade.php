{{-- File: resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ReadySoft') }} - Book Your Next Experience</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-blue-600">ReadySoft</span>
                </div>
                
                @if (Route::has('login'))
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold">Book Your Next Experience</h1>
            <p class="text-xl mt-4">Find and book services from trusted providers</p>
            <a href="{{ route('register') }}" class="mt-8 inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                Get Started
            </a>
        </div>
    </div>

    <!-- Tenants Grid Section -->
    @if($tenants->isNotEmpty())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ search: '' }">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Available Services</h2>
                
                <!-- Search Input -->
                <div class="max-w-md">
                    <label for="tenant-search" class="block text-sm font-medium text-gray-700 mb-2">
                        Search by name
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="tenant-search"
                            x-model="search"
                            placeholder="Search for services..."
                            class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tenants as $tenant)
                    <div 
                        class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow"
                        x-show="search === '' || '{{ strtolower($tenant->name) }}'.includes(search.toLowerCase())"
                        x-transition
                    >
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $tenant->name }}</h3>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                {{ $tenant->business_type }}
                            </span>
                        </div>
                        
                        @if($tenant->description)
                            <p class="text-gray-600 text-sm mt-2 mb-4">
                                {{ Str::limit($tenant->description, 100) }}
                            </p>
                        @endif
                        
                        <a href="/{{ $tenant->slug }}" class="mt-4 block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Book Now
                        </a>
                    </div>
                @endforeach
            </div>
            
            <!-- No Results Message -->
            <div 
                x-show="search !== '' && !Array.from(document.querySelectorAll('[x-show]')).some(el => el.style.display !== 'none' && el !== $el)"
                x-cloak
                class="text-center py-12"
            >
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No services found</h3>
                <p class="mt-2 text-gray-600">Try adjusting your search terms</p>
            </div>
        </div>
    @else
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center py-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">No Services Available Yet</h2>
                <p class="text-gray-600 mb-6">Be the first to offer your services on our platform!</p>
                <a href="{{ route('register') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Register Your Business
                </a>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-gray-300 text-sm">
                    © {{ date('Y') }} ReadySoft. All rights reserved.
                </div>
                <div class="flex gap-6">
                    <a href="#" class="text-gray-300 hover:text-white text-sm transition-colors">About</a>
                    <a href="#" class="text-gray-300 hover:text-white text-sm transition-colors">Contact</a>
                    <a href="#" class="text-gray-300 hover:text-white text-sm transition-colors">Privacy</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
{{-- Landing page - viser hero seksjon og liste over alle aktive tenants --}}
