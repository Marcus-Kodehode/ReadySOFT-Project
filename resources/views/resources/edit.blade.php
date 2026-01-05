{{-- File: resources/views/resources/edit.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Resource') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('resources.update', $resource->id) }}" x-data="{ loading: false }" @submit="if(!$el.querySelector('[x-data]').__x.$data.isFormValid()) { $event.preventDefault(); return; } loading = true">
                        @csrf
                        @method('PUT')

                        @include('resources._form')

                        <div class="flex justify-end gap-3 mt-6">
                            <a href="{{ route('resources.index') }}" 
                               class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
                                Cancel
                            </a>
                            <button type="submit" 
                                    :disabled="loading || !$el.closest('form').querySelector('[x-data]').__x.$data.isFormValid()"
                                    :class="($el.closest('form').querySelector('[x-data]').__x.$data.isFormValid() && !loading) ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-300 cursor-not-allowed'"
                                    class="px-4 py-2 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!loading">Update Resource</span>
                                <span x-show="loading" class="flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Updating...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Edit form --}}
