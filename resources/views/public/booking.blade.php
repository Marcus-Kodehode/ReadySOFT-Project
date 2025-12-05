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
    <div class="max-w-7xl mx-auto px-4 py-8" x-data="{ 
        modalOpen: false, 
        currentStep: 1,
        selectedResourceId: null, 
        selectedResourceName: '',
        bookingDate: '',
        minDate: new Date().toISOString().split('T')[0],
        availableSlots: [],
        selectedTimeSlot: '',
        loadingSlots: false,
        customerName: '',
        customerEmail: '',
        customerPhone: '',
        customerNotes: '',
        errors: {},
        async fetchAvailableSlots() {
            if (!this.bookingDate || !this.selectedResourceId) {
                this.availableSlots = [];
                return;
            }
            
            this.loadingSlots = true;
            this.selectedTimeSlot = '';
            
            try {
                const response = await fetch(`/api/available-slots?resource_id=${this.selectedResourceId}&date=${this.bookingDate}`);
                const data = await response.json();
                this.availableSlots = data.slots || [];
            } catch (error) {
                console.error('Error fetching available slots:', error);
                this.availableSlots = [];
            } finally {
                this.loadingSlots = false;
            }
        },
        nextStep() {
            if (this.currentStep === 1 && this.bookingDate && this.selectedTimeSlot) {
                this.currentStep = 2;
            }
        },
        previousStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },
        resetModal() {
            this.currentStep = 1;
            this.bookingDate = '';
            this.selectedTimeSlot = '';
            this.availableSlots = [];
            this.customerName = '';
            this.customerEmail = '';
            this.customerPhone = '';
            this.customerNotes = '';
            this.errors = {};
        },
        validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },
        validatePhone(phone) {
            const re = /^[+]?[0-9]{8,15}$/;
            return re.test(phone.replace(/\s/g, ''));
        },
        validateCustomerInfo() {
            this.errors = {};
            
            if (!this.customerName || this.customerName.trim().length < 2) {
                this.errors.name = 'Name must be at least 2 characters';
            }
            
            if (!this.customerEmail || !this.validateEmail(this.customerEmail)) {
                this.errors.email = 'Please enter a valid email address';
            }
            
            if (!this.customerPhone || !this.validatePhone(this.customerPhone)) {
                this.errors.phone = 'Please enter a valid phone number (8-15 digits)';
            }
            
            return Object.keys(this.errors).length === 0;
        }
    }" x-init="$watch('bookingDate', () => fetchAvailableSlots())">
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
                        <button @click="modalOpen = true; selectedResourceId = {{ $resource->id }}; selectedResourceName = '{{ $resource->name }}'; resetModal();" class="w-full mt-4 px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
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
                    <button @click="modalOpen = false; resetModal();" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Step Indicator --}}
                <div class="flex items-center justify-center mb-6">
                    <div class="flex items-center">
                        {{-- Step 1 --}}
                        <div class="flex items-center">
                            <div :class="currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600'" class="flex items-center justify-center w-8 h-8 rounded-full font-medium text-sm">
                                1
                            </div>
                            <span :class="currentStep >= 1 ? 'text-gray-900' : 'text-gray-500'" class="ml-2 text-sm font-medium">Date & Time</span>
                        </div>
                        
                        {{-- Divider --}}
                        <div :class="currentStep >= 2 ? 'bg-blue-600' : 'bg-gray-300'" class="w-12 h-1 mx-3"></div>
                        
                        {{-- Step 2 --}}
                        <div class="flex items-center">
                            <div :class="currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600'" class="flex items-center justify-center w-8 h-8 rounded-full font-medium text-sm">
                                2
                            </div>
                            <span :class="currentStep >= 2 ? 'text-gray-900' : 'text-gray-500'" class="ml-2 text-sm font-medium">Your Info</span>
                        </div>
                    </div>
                </div>
                
                {{-- Booking Form --}}
                <form class="space-y-4">
                    {{-- Step 1: Select Date & Time --}}
                    <div x-show="currentStep === 1" x-transition>
                        {{-- Select Date --}}
                        <div>
                            <label for="booking_date" class="block mb-1 text-sm font-medium text-gray-700">
                                Select Date
                            </label>
                            <input 
                                type="date" 
                                id="booking_date"
                                x-model="bookingDate"
                                :min="minDate"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                            <p class="mt-1 text-sm text-gray-500">Choose a date for your booking</p>
                        </div>

                        {{-- Select Time --}}
                        <div x-show="bookingDate" x-transition>
                            <label for="time_slot" class="block mb-1 text-sm font-medium text-gray-700">
                                Select Time
                            </label>
                            
                            {{-- Loading State --}}
                            <div x-show="loadingSlots" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                                <p class="text-sm text-gray-500">Loading available times...</p>
                            </div>
                            
                            {{-- Time Slot Dropdown --}}
                            <select 
                                x-show="!loadingSlots && availableSlots.length > 0"
                                x-model="selectedTimeSlot"
                                id="time_slot"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">Choose a time slot</option>
                                <template x-for="slot in availableSlots" :key="slot">
                                    <option :value="slot" x-text="slot"></option>
                                </template>
                            </select>
                            
                            {{-- No Slots Available --}}
                            <div x-show="!loadingSlots && availableSlots.length === 0 && bookingDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-yellow-50">
                                <p class="text-sm text-yellow-800">No available time slots for this date. Please select another date.</p>
                            </div>
                            
                            <p x-show="!loadingSlots && availableSlots.length > 0" class="mt-1 text-sm text-gray-500">
                                Available time slots (30-minute intervals)
                            </p>
                        </div>
                    </div>

                    {{-- Step 2: Customer Information --}}
                    <div x-show="currentStep === 2" x-transition>
                        {{-- Customer Name --}}
                        <div>
                            <label for="customer_name" class="block mb-1 text-sm font-medium text-gray-700">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="customer_name"
                                x-model="customerName"
                                @blur="validateCustomerInfo()"
                                required
                                placeholder="John Doe"
                                :class="errors.name ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                            >
                            <p x-show="errors.name" x-text="errors.name" class="flex items-center gap-1 mt-1 text-sm text-red-600">
                            </p>
                        </div>

                        {{-- Customer Email --}}
                        <div>
                            <label for="customer_email" class="block mb-1 text-sm font-medium text-gray-700">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="customer_email"
                                x-model="customerEmail"
                                @blur="validateCustomerInfo()"
                                required
                                placeholder="john@example.com"
                                :class="errors.email ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                            >
                            <p x-show="errors.email" x-text="errors.email" class="flex items-center gap-1 mt-1 text-sm text-red-600">
                            </p>
                        </div>

                        {{-- Customer Phone --}}
                        <div>
                            <label for="customer_phone" class="block mb-1 text-sm font-medium text-gray-700">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="tel" 
                                id="customer_phone"
                                x-model="customerPhone"
                                @blur="validateCustomerInfo()"
                                required
                                placeholder="+47 12345678"
                                :class="errors.phone ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                            >
                            <p x-show="errors.phone" x-text="errors.phone" class="flex items-center gap-1 mt-1 text-sm text-red-600">
                            </p>
                            <p class="mt-1 text-sm text-gray-500">8-15 digits, international format accepted</p>
                        </div>

                        {{-- Customer Notes (Optional) --}}
                        <div>
                            <label for="customer_notes" class="block mb-1 text-sm font-medium text-gray-700">
                                Additional Notes <span class="text-gray-400">(Optional)</span>
                            </label>
                            <textarea 
                                id="customer_notes"
                                x-model="customerNotes"
                                rows="3"
                                placeholder="Any special requests or information..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            ></textarea>
                            <p class="mt-1 text-sm text-gray-500">Optional: Add any special requests</p>
                        </div>
                    </div>
                </form>
                
                {{-- Action Buttons --}}
                <div class="flex justify-between gap-3 mt-6">
                    {{-- Back Button (only on step 2) --}}
                    <button 
                        x-show="currentStep === 2"
                        @click="previousStep()"
                        type="button"
                        class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium"
                    >
                        Back
                    </button>
                    
                    {{-- Cancel Button --}}
                    <button 
                        @click="modalOpen = false; resetModal();" 
                        type="button"
                        class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium"
                    >
                        Cancel
                    </button>
                    
                    {{-- Next Button (step 1) --}}
                    <button 
                        x-show="currentStep === 1"
                        @click="nextStep()"
                        type="button"
                        :disabled="!bookingDate || !selectedTimeSlot"
                        :class="(bookingDate && selectedTimeSlot) ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-300 cursor-not-allowed'"
                        class="px-4 py-2 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium"
                    >
                        Next
                    </button>
                    
                    {{-- Submit Button (step 2) --}}
                    <button 
                        x-show="currentStep === 2"
                        type="button"
                        @click="if(validateCustomerInfo()) { alert('Booking submitted! (Form submission not yet implemented)') }"
                        :disabled="!customerName || !customerEmail || !customerPhone"
                        :class="(customerName && customerEmail && customerPhone) ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-300 cursor-not-allowed'"
                        class="px-4 py-2 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium"
                    >
                        Complete Booking
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

{{-- Public booking page - viser tenant info og ressurser for booking --}}
