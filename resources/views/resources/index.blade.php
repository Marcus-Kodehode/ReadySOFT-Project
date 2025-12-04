{{-- File: resources/views/resources/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Resources') }}
            </h2>
            <a href="{{ route('resources.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                New Resource
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ 
        showDeleteModal: false, 
        deleteResourceId: null, 
        deleteResourceName: '',
        openDeleteModal(id, name) {
            this.deleteResourceId = id;
            this.deleteResourceName = name;
            this.showDeleteModal = true;
        },
        closeDeleteModal() {
            this.showDeleteModal = false;
            this.deleteResourceId = null;
            this.deleteResourceName = '';
        },
        confirmDelete() {
            if (this.deleteResourceId) {
                document.getElementById('delete-form-' + this.deleteResourceId).submit();
            }
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mb-4 p-4 border-l-4 border-green-500 rounded bg-green-50">
                    <div class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-green-800">Success!</p>
                            <p class="mt-1 text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 border-l-4 border-red-500 rounded bg-red-50">
                    <div class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-red-800">Error</p>
                            <p class="mt-1 text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Empty State --}}
            @if($resources->isEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">No resources yet</h3>
                    <p class="mt-2 text-sm text-gray-600">Create your first resource to start receiving bookings</p>
                    <a href="{{ route('resources.create') }}" 
                       class="mt-6 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Create Resource
                    </a>
                </div>
            @else
                {{-- Desktop Table View --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 hidden md:table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Capacity
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($resources as $resource)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">{{ $resource->name }}</div>
                                        @if($resource->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($resource->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">{{ $resource->type }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">{{ $resource->capacity }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($resource->active)
                                            <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex gap-2 justify-end">
                                            <a href="{{ route('resources.edit', $resource->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 transition-colors">
                                                Edit
                                            </a>
                                            <form id="delete-form-{{ $resource->id }}" 
                                                  action="{{ route('resources.destroy', $resource->id) }}" 
                                                  method="POST" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button"
                                                    @click="openDeleteModal({{ $resource->id }}, '{{ addslashes($resource->name) }}')"
                                                    class="text-red-600 hover:text-red-800 transition-colors">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 block md:hidden">
                    @foreach($resources as $resource)
                        <div class="p-4 border-b border-gray-200 last:border-b-0">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $resource->name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $resource->type }}</p>
                                </div>
                                @if($resource->active)
                                    <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                            
                            @if($resource->description)
                                <p class="text-sm text-gray-500 mb-3">{{ Str::limit($resource->description, 100) }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                <span class="text-sm text-gray-600">Capacity: {{ $resource->capacity }}</span>
                                <div class="flex gap-2">
                                    <a href="{{ route('resources.edit', $resource->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                                        Edit
                                    </a>
                                    <button type="button"
                                            @click="openDeleteModal({{ $resource->id }}, '{{ addslashes($resource->name) }}')"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Delete Confirmation Modal --}}
            <div x-show="showDeleteModal" 
                 x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center p-4"
                 @keydown.escape.window="closeDeleteModal()">
                {{-- Backdrop --}}
                <div @click="closeDeleteModal()" 
                     class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
                
                {{-- Modal --}}
                <div class="relative z-10 w-full max-w-md p-6 bg-white rounded-lg shadow-xl"
                     @click.stop>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Delete Resource</h3>
                    <p class="mb-2 text-gray-600">
                        Are you sure you want to delete this resource?
                    </p>
                    <p class="mb-2 text-sm text-gray-700">
                        <span class="font-semibold" x-text="deleteResourceName"></span>
                    </p>
                    <p class="mb-6 text-sm text-red-600">
                        All bookings for this resource will also be deleted.
                    </p>
                    <div class="flex justify-end gap-3">
                        <button @click="closeDeleteModal()"
                                class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
                            Cancel
                        </button>
                        <button @click="confirmDelete()"
                                class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors font-medium">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Resource list view - viser alle ressurser for tenant --}}
