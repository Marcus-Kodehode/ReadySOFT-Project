{{-- File: resources/views/resources/_form.blade.php --}}

<div class="space-y-6" x-data="{
    name: '{{ old('name', $resource->name ?? '') }}',
    description: '{{ old('description', $resource->description ?? '') }}',
    type: '{{ old('type', $resource->type ?? '') }}',
    capacity: '{{ old('capacity', $resource->capacity ?? '1') }}',
    errors: {},
    validateName() {
        if (this.name.trim() === '') {
            this.errors.name = 'Name is required';
        } else if (this.name.length < 3) {
            this.errors.name = 'Name must be at least 3 characters';
        } else if (this.name.length > 255) {
            this.errors.name = 'Name must not exceed 255 characters';
        } else {
            delete this.errors.name;
        }
    },
    validateType() {
        if (this.type === '') {
            this.errors.type = 'Type is required';
        } else {
            delete this.errors.type;
        }
    },
    validateCapacity() {
        if (this.capacity === '' || this.capacity === null) {
            this.errors.capacity = 'Capacity is required';
        } else if (parseInt(this.capacity) < 1) {
            this.errors.capacity = 'Capacity must be at least 1';
        } else {
            delete this.errors.capacity;
        }
    }
}">
    {{-- Name Field --}}
    <div>
        <label for="name" class="block mb-1 text-sm font-medium text-gray-700">
            Name <span class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            id="name"
            name="name" 
            x-model="name"
            @blur="validateName()"
            required
            :class="errors.name ? 'border-red-300' : 'border-gray-300'"
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-300 @enderror"
            placeholder="Enter resource name">
        @error('name')
            <p class="flex items-center gap-1 mt-1 text-sm text-red-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
        @enderror
        <p x-show="errors.name" x-text="errors.name" class="flex items-center gap-1 mt-1 text-sm text-red-600" x-cloak>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </p>
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
            x-model="type"
            @blur="validateType()"
            required
            :class="errors.type ? 'border-red-300' : 'border-gray-300'"
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type') border-red-300 @enderror">
            <option value="">Select a type</option>
            <option value="Cabin">Cabin</option>
            <option value="Chair">Chair</option>
            <option value="Room">Room</option>
            <option value="Treatment Room">Treatment Room</option>
            <option value="Other">Other</option>
        </select>
        @error('type')
            <p class="flex items-center gap-1 mt-1 text-sm text-red-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
        @enderror
        <p x-show="errors.type" x-text="errors.type" class="flex items-center gap-1 mt-1 text-sm text-red-600" x-cloak>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </p>
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
            x-model="capacity"
            @blur="validateCapacity()"
            min="1"
            required
            :class="errors.capacity ? 'border-red-300' : 'border-gray-300'"
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('capacity') border-red-300 @enderror"
            placeholder="Enter capacity">
        @error('capacity')
            <p class="flex items-center gap-1 mt-1 text-sm text-red-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
        @enderror
        <p x-show="errors.capacity" x-text="errors.capacity" class="flex items-center gap-1 mt-1 text-sm text-red-600" x-cloak>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </p>
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
