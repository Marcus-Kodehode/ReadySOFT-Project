{{-- File: resources/views/resources/_form.blade.php --}}

<div class="space-y-6">
    {{-- Name Field --}}
    <div>
        <label for="name" class="block mb-1 text-sm font-medium text-gray-700">
            Name <span class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            id="name"
            name="name" 
            value="{{ old('name', $resource->name ?? '') }}"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-300 @enderror"
            placeholder="Enter resource name">
        @error('name')
            <p class="flex items-center gap-1 mt-1 text-sm text-red-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Description Field --}}
    <div>
        <label for="description" class="block mb-1 text-sm font-medium text-gray-700">
            Description
        </label>
        <textarea 
            id="description"
            name="description" 
            rows="4"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-300 @enderror"
            placeholder="Enter resource description">{{ old('description', $resource->description ?? '') }}</textarea>
        @error('description')
            <p class="flex items-center gap-1 mt-1 text-sm text-red-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Type Field --}}
    <div>
        <label for="type" class="block mb-1 text-sm font-medium text-gray-700">
            Type <span class="text-red-500">*</span>
        </label>
        <select 
            id="type"
            name="type" 
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type') border-red-300 @enderror">
            <option value="">Select a type</option>
            <option value="Cabin" {{ old('type', $resource->type ?? '') == 'Cabin' ? 'selected' : '' }}>Cabin</option>
            <option value="Chair" {{ old('type', $resource->type ?? '') == 'Chair' ? 'selected' : '' }}>Chair</option>
            <option value="Room" {{ old('type', $resource->type ?? '') == 'Room' ? 'selected' : '' }}>Room</option>
            <option value="Treatment Room" {{ old('type', $resource->type ?? '') == 'Treatment Room' ? 'selected' : '' }}>Treatment Room</option>
            <option value="Other" {{ old('type', $resource->type ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('type')
            <p class="flex items-center gap-1 mt-1 text-sm text-red-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Capacity Field --}}
    <div>
        <label for="capacity" class="block mb-1 text-sm font-medium text-gray-700">
            Capacity <span class="text-red-500">*</span>
        </label>
        <input 
            type="number" 
            id="capacity"
            name="capacity" 
            value="{{ old('capacity', $resource->capacity ?? '1') }}"
            min="1"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('capacity') border-red-300 @enderror"
            placeholder="Enter capacity">
        @error('capacity')
            <p class="flex items-center gap-1 mt-1 text-sm text-red-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Active Status Field --}}
    <div class="flex items-center">
        <input 
            type="checkbox" 
            id="active"
            name="active" 
            value="1"
            {{ old('active', $resource->active ?? true) ? 'checked' : '' }}
            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
        <label for="active" class="ml-2 text-sm font-medium text-gray-700">
            Active
        </label>
    </div>
</div>

{{-- Shared form partial for create/edit --}}
