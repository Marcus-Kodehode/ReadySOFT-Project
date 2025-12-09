{{-- File: resources/views/admin/tenants.blade.php --}}
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

            <!-- Tenants Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Slug
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Business Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Created
                                </th>
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
                                            <span 
                                                x-show="active"
                                                class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                Active
                                            </span>
                                            <span 
                                                x-show="!active"
                                                class="px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
                                                Inactive
                                            </span>
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
                        {{ $tenants->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
{{-- Tenant management view - viser liste over alle tenants med Name, Slug, Business Type, Status, Created, Actions --}}
