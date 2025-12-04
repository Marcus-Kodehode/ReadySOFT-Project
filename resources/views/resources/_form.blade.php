{{-- File: resources/views/resources/_form.blade.php --}}

@php
    $weekdays = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        0 => 'Sunday'
    ];
    
    // Hent eksisterende availabilities hvis vi redigerer
    $existingAvailabilities = [];
    if (isset($resource) && $resource->exists && $resource->relationLoaded('availabilities')) {
        foreach ($resource->availabilities as $availability) {
            $existingAvailabilities[$availability->day_of_week] = [
                'enabled' => true,
                'start_time' => substr($availability->start_time, 0, 5), // Format HH:MM
                'end_time' => substr($availability->end_time, 0, 5) // Format HH:MM
            ];
        }
    }
@endphp

<div class="space-y-6" x-data="{
    name: '{{ old('name', $resource->name ?? '') }}',
    description: '{{ old('description', $resource->description ?? '') }}',
    type: '{{ old('type', $resource->type ?? '') }}',
    capacity: '{{ old('capacity', $resource->capacity ?? '1') }}',
    sameHoursEveryDay: false,
    availabilities: {
        @foreach($weekdays as $dayNum => $dayName)
            {{ $dayNum }}: {
                enabled: {{ isset($existingAvailabilities[$dayNum]) ? 'true' : 'false' }},
                start_time: '{{ old("availabilities.$dayNum.start_time", $existingAvailabilities[$dayNum]['start_time'] ?? '09:00') }}',
                end_time: '{{ old("availabilities.$dayNum.end_time", $existingAvailabilities[$dayNum]['end_time'] ?? '17:00') }}'
            }{{ !$loop->last ? ',' : '' }}
        @endforeach
    },
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
    },
    applySameHours() {
        if (this.sameHoursEveryDay) {
            const mondayHours = this.availabilities[1];
            Object.keys(this.availabilities).forEach(day => {
                if (this.availabilities[day].enabled) {
                    this.availabilities[day].start_time = mondayHours.start_time;
                    this.availabilities[day].end_time = mondayHours.end_time;
                }
            });
        }
    },
    validateTime(day) {
        const availability = this.availabilities[day];
        if (availability.enabled && availability.start_time >= availability.end_time) {
            this.errors['time_' + day] = 'End time must be after start time';
        } else {
            delete this.errors['time_' + day];
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

    {{-- Opening Hours Section --}}
    <div class="pt-6 border-t border-gray-200">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">Opening Hours</h3>
        
        {{-- Quick Setup: Same hours every day --}}
        <div class="mb-4">
            <label class="flex items-center">
                <input 
                    type="checkbox" 
                    x-model="sameHoursEveryDay"
                    @change="applySameHours()"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                <span class="ml-2 text-sm font-medium text-gray-700">
                    Same hours every day
                </span>
            </label>
            <p class="mt-1 ml-6 text-xs text-gray-500">
                Apply Monday's hours to all enabled days
            </p>
        </div>

        {{-- Weekday Hours --}}
        <div class="space-y-3">
            @foreach($weekdays as $dayNum => $dayName)
                <div class="p-4 border border-gray-200 rounded-lg" 
                     :class="availabilities[{{ $dayNum }}].enabled ? 'bg-white' : 'bg-gray-50'">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center">
                        {{-- Day Checkbox --}}
                        <div class="flex items-center md:w-32">
                            <input 
                                type="checkbox" 
                                id="day_{{ $dayNum }}"
                                name="availabilities[{{ $dayNum }}][enabled]"
                                value="1"
                                x-model="availabilities[{{ $dayNum }}].enabled"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                            <label for="day_{{ $dayNum }}" class="ml-2 text-sm font-medium text-gray-700">
                                {{ $dayName }}
                            </label>
                        </div>

                        {{-- Time Inputs --}}
                        <div class="flex items-center flex-1 gap-3" x-show="availabilities[{{ $dayNum }}].enabled">
                            <div class="flex-1">
                                <label for="start_time_{{ $dayNum }}" class="block mb-1 text-xs font-medium text-gray-600">
                                    Start Time
                                </label>
                                <input 
                                    type="time" 
                                    id="start_time_{{ $dayNum }}"
                                    name="availabilities[{{ $dayNum }}][start_time]"
                                    x-model="availabilities[{{ $dayNum }}].start_time"
                                    @change="validateTime({{ $dayNum }}); if(sameHoursEveryDay && {{ $dayNum }} === 1) applySameHours()"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <span class="text-gray-500">-</span>

                            <div class="flex-1">
                                <label for="end_time_{{ $dayNum }}" class="block mb-1 text-xs font-medium text-gray-600">
                                    End Time
                                </label>
                                <input 
                                    type="time" 
                                    id="end_time_{{ $dayNum }}"
                                    name="availabilities[{{ $dayNum }}][end_time]"
                                    x-model="availabilities[{{ $dayNum }}].end_time"
                                    @change="validateTime({{ $dayNum }}); if(sameHoursEveryDay && {{ $dayNum }} === 1) applySameHours()"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    {{-- Time Validation Error --}}
                    <div x-show="errors['time_{{ $dayNum }}']" class="mt-2">
                        <p x-text="errors['time_{{ $dayNum }}']" class="flex items-center gap-1 text-sm text-red-600">
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="mt-3 text-sm text-gray-500">
            Select which days this resource is available and set the opening hours for each day.
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
