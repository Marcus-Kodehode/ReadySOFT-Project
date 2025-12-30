{{-- File: resources/views/admin/tenants.blade.php --}}
@php
    // Sorteringsvariabler
    $currentSort = request('sort', 'created_at');
    $currentDirection = request('direction', 'desc');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tenant Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    All Tenants
                </h1>
            </div>

            <!-- Filter Tabs -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <a href="{{ route('admin.tenants', ['search' => request('search')]) }}" 
                           class="@if(!request('filter') || request('filter') === 'all') border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            All
                            <span class="@if(!request('filter') || request('filter') === 'all') bg-blue-100 text-blue-600 @else bg-gray-100 text-gray-900 @endif ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                {{ \App\Models\Tenant::count() }}
                            </span>
                        </a>
                        <a href="{{ route('admin.tenants', ['filter' => 'active', 'search' => request('search')]) }}" 
                           class="@if(request('filter') === 'active') border-green-500 text-green-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Active
                            <span class="@if(request('filter') === 'active') bg-green-100 text-green-600 @else bg-gray-100 text-gray-900 @endif ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                {{ \App\Models\Tenant::where('active', true)->count() }}
                            </span>
                        </a>
                        <a href="{{ route('admin.tenants', ['filter' => 'inactive', 'search' => request('search')]) }}" 
                           class="@if(request('filter') === 'inactive') border-red-500 text-red-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Inactive
                            <span class="@if(request('filter') === 'inactive') bg-red-100 text-red-600 @else bg-gray-100 text-gray-900 @endif ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                {{ \App\Models\Tenant::where('active', false)->count() }}
                            </span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="mb-6">
                <form method="GET" action="{{ route('admin.tenants') }}" class="flex gap-4" x-data="{ loading: false }" @submit="loading = true">
                    <!-- Preserve filter parameter -->
                    @if(request('filter'))
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                    @endif
                    
                    <div class="flex-1">
                        <label for="search" class="sr-only">Search tenants</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                name="search" 
                                id="search"
                                value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Search by name or slug...">
                        </div>
                    </div>
                    <button 
                        type="submit"
                        :disabled="loading"
                        class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">Search</span>
                        <span x-show="loading" class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Searching...
                        </span>
                    </button>
                    @if(request('search'))
                        <a 
                            href="{{ route('admin.tenants', ['filter' => request('filter')]) }}"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Tenants Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <!-- Sortable: Name -->
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @php
                                        $newDirection = ($currentSort === 'name' && $currentDirection === 'asc') ? 'desc' : 'asc';
                                        $sortUrl = route('admin.tenants', array_merge(request()->query(), ['sort' => 'name', 'direction' => $newDirection]));
                                    @endphp
                                    <a href="{{ $sortUrl }}" class="group inline-flex items-center gap-1 hover:text-gray-700">
                                        Name
                                        @if($currentSort === 'name')
                                            @if($currentDirection === 'asc')
                                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            @endif
                                        @else
                                            <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                
                                <!-- Sortable: Slug -->
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @php
                                        $newDirection = ($currentSort === 'slug' && $currentDirection === 'asc') ? 'desc' : 'asc';
                                        $sortUrl = route('admin.tenants', array_merge(request()->query(), ['sort' => 'slug', 'direction' => $newDirection]));
                                    @endphp
                                    <a href="{{ $sortUrl }}" class="group inline-flex items-center gap-1 hover:text-gray-700">
                                        Slug
                                        @if($currentSort === 'slug')
                                            @if($currentDirection === 'asc')
                                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            @endif
                                        @else
                                            <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                
                                <!-- Sortable: Business Type -->
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @php
                                        $newDirection = ($currentSort === 'business_type' && $currentDirection === 'asc') ? 'desc' : 'asc';
                                        $sortUrl = route('admin.tenants', array_merge(request()->query(), ['sort' => 'business_type', 'direction' => $newDirection]));
                                    @endphp
                                    <a href="{{ $sortUrl }}" class="group inline-flex items-center gap-1 hover:text-gray-700">
                                        Business Type
                                        @if($currentSort === 'business_type')
                                            @if($currentDirection === 'asc')
                                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            @endif
                                        @else
                                            <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                
                                <!-- Sortable: Status -->
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @php
                                        $newDirection = ($currentSort === 'active' && $currentDirection === 'asc') ? 'desc' : 'asc';
                                        $sortUrl = route('admin.tenants', array_merge(request()->query(), ['sort' => 'active', 'direction' => $newDirection]));
                                    @endphp
                                    <a href="{{ $sortUrl }}" class="group inline-flex items-center gap-1 hover:text-gray-700">
                                        Status
                                        @if($currentSort === 'active')
                                            @if($currentDirection === 'asc')
                                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            @endif
                                        @else
                                            <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                
                                <!-- Sortable: Created -->
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    @php
                                        $newDirection = ($currentSort === 'created_at' && $currentDirection === 'asc') ? 'desc' : 'asc';
                                        $sortUrl = route('admin.tenants', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => $newDirection]));
                                    @endphp
                                    <a href="{{ $sortUrl }}" class="group inline-flex items-center gap-1 hover:text-gray-700">
                                        Created
                                        @if($currentSort === 'created_at')
                                            @if($currentDirection === 'asc')
                                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            @endif
                                        @else
                                            <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                
                                <!-- Non-sortable: Actions -->
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($tenants as $tenant)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $tenant->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">
                                            {{ $tenant->slug }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">
                                            {{ $tenant->business_type }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <!-- Status Toggle Switch -->
                                        <div x-data="{ 
                                            active: {{ $tenant->active ? 'true' : 'false' }},
                                            toggling: false,
                                            async toggle() {
                                                if (this.toggling) return;
                                                this.toggling = true;
                                                
                                                try {
                                                    const response = await fetch('{{ route('admin.tenants.toggle', $tenant->id) }}', {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            'Accept': 'application/json'
                                                        }
                                                    });
                                                    
                                                    if (response.ok) {
                                                        this.active = !this.active;
                                                    } else {
                                                        alert('Failed to update tenant status');
                                                    }
                                                } catch (error) {
                                                    alert('Error updating tenant status');
                                                } finally {
                                                    this.toggling = false;
                                                }
                                            }
                                        }" class="flex items-center gap-3">
                                            <!-- Toggle Switch -->
                                            <button 
                                                @click="toggle()"
                                                :disabled="toggling"
                                                type="button"
                                                :class="active ? 'bg-green-600' : 'bg-gray-200'"
                                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                                role="switch"
                                                :aria-checked="active.toString()">
                                                <span class="sr-only">Toggle tenant status</span>
                                                <span 
                                                    :class="active ? 'translate-x-5' : 'translate-x-0'"
                                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                                </span>
                                            </button>
                                            
                                            <!-- Status Badge -->
                                            <x-badge 
                                                x-show="active"
                                                color="success">
                                                Active
                                            </x-badge>
                                            <x-badge 
                                                x-show="!active"
                                                color="info">
                                                Inactive
                                            </x-badge>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">
                                            {{ $tenant->created_at->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex gap-2">
                                            <!-- View Details Link -->
                                            <a href="{{ url('/' . $tenant->slug) }}" 
                                               target="_blank"
                                               class="text-blue-600 hover:text-blue-800">
                                                View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <p class="text-lg font-medium text-gray-900 mb-2">No Tenants Found</p>
                                            <p class="text-sm text-gray-500">There are no tenants in the system yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($tenants->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $tenants->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
{{-- Tenant management view - viser liste over alle tenants med Name, Slug, Business Type, Status, Created, Actions --}}
