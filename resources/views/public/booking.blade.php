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
        submitting: false,
        customerName: '',
        customerEmail: '',
        customerPhone: '',
        customerNotes: '',
        errors: {},
        touched: {},
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
            if (this.validateStep1()) {
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
            this.submitting = false;
            this.customerName = '';
            this.customerEmail = '';
            this.customerPhone = '';
            this.customerNotes = '';
            this.errors = {};
            this.touched = {};
        },
        validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },
        validatePhone(phone) {
            const re = /^[+]?[0-9]{8,15}$/;
            return re.test(phone.replace(/\s/g, ''));
        },
        validateStep1() {
            const stepErrors = {};
            
            if (!this.bookingDate) {
                stepErrors.date = 'Please select a date';
            } else if (this.bookingDate < this.minDate) {
                stepErrors.date = 'Date must be in the future';
            }
            
            if (!this.selectedTimeSlot) {
                stepErrors.timeSlot = 'Please select a time slot';
            }
            
            this.errors = { ...this.errors, ...stepErrors };
            return Object.keys(stepErrors).length === 0;
        },
        validateField(field) {
            this.touched[field] = true;
            
            switch(field) {
                case 'name':
                    if (!this.customerName || this.customerName.trim().length === 0) {
                        this.errors.name = 'Name is required';
                    } else if (this.customerName.trim().length < 2) {
                        this.errors.name = 'Name must be at least 2 characters';
                    } else if (this.customerName.trim().length > 255) {
                        this.errors.name = 'Name must not exceed 255 characters';
                    } else {
                        delete this.errors.name;
                    }
                    break;
                    
                case 'email':
                    if (!this.customerEmail || this.customerEmail.trim().length === 0) {
                        this.errors.email = 'Email is required';
                    } else if (!this.validateEmail(this.customerEmail)) {
                        this.errors.email = 'Please enter a valid email address';
                    } else {
                        delete this.errors.email;
                    }
                    break;
                    
                case 'phone':
                    if (!this.customerPhone || this.customerPhone.trim().length === 0) {
                        this.errors.phone = 'Phone number is required';
                    } else if (!this.validatePhone(this.customerPhone)) {
                        this.errors.phone = 'Please enter a valid phone number (8-15 digits)';
                    } else {
                        delete this.errors.phone;
                    }
                    break;
                    
                case 'date':
                    if (!this.bookingDate) {
                        this.errors.date = 'Please select a date';
                    } else if (this.bookingDate < this.minDate) {
                        this.errors.date = 'Date must be in the future';
                    } else {
                        delete this.errors.date;
                    }
                    break;
                    
                case 'timeSlot':
                    if (!this.selectedTimeSlot) {
                        this.errors.timeSlot = 'Please select a time slot';
                    } else {
                        delete this.errors.timeSlot;
                    }
                    break;
            }
        },
        validateCustomerInfo() {
            this.validateField('name');
            this.validateField('email');
            this.validateField('phone');
            
            return !this.errors.name && !this.errors.email && !this.errors.phone;
        },
        isStep1Valid() {
            return this.bookingDate && this.selectedTimeSlot && !this.errors.date && !this.errors.timeSlot;
        },
        isStep2Valid() {
            return this.customerName && this.customerEmail && this.customerPhone && 
                   !this.errors.name && !this.errors.email && !this.errors.phone;
        }
    }" x-init="$watch('bookingDate', () => { fetchAvailableSlots(); if(touched.date) validateField('date'); }); $watch('selectedTimeSlot', () => { if(touched.timeSlot) validateField('timeSlot'); }); $watch('customerName', () => { if(touched.name) validateField('name'); }); $watch('customerEmail', () => { if(touched.email) validateField('email'); }); $watch('customerPhone', () => { if(touched.phone) validateField('phone'); })">
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
                                Select Date <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                id="booking_date"
                                x-model="bookingDate"
                                :min="minDate"
                                @blur="validateField('date')"
                                @change="validateField('date')"
                                required
                                :class="errors.date ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                            >
                            <p x-show="errors.date" x-text="errors.date" class="flex items-center gap-1 mt-1 text-sm text-red-600">
                            </p>
                            <p x-show="!errors.date" class="mt-1 text-sm text-gray-500">Choose a date for your booking</p>
                        </div>

                        {{-- Select Time --}}
                        <div x-show="bookingDate" x-transition>
                            <label for="time_slot" class="block mb-1 text-sm font-medium text-gray-700">
                                Select Time <span class="text-red-500">*</span>
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
                                @blur="validateField('timeSlot')"
                                @change="validateField('timeSlot')"
                                required
                                :class="errors.timeSlot ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
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
                            
                            <p x-show="errors.timeSlot" x-text="errors.timeSlot" class="flex items-center gap-1 mt-1 text-sm text-red-600">
                            </p>
                            <p x-show="!errors.timeSlot && !loadingSlots && availableSlots.length > 0" class="mt-1 text-sm text-gray-500">
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
                                @blur="validateField('name')"
                                @input="if(touched.name) validateField('name')"
                                required
                                placeholder="John Doe"
                                :class="errors.name ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                            >
                            <p x-show="errors.name" x-text="errors.name" class="flex items-center gap-1 mt-1 text-sm text-red-600">
                            </p>
                            <p x-show="!errors.name && customerName.trim().length >= 2" class="flex items-center gap-1 mt-1 text-sm text-green-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Valid
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
                                @blur="validateField('email')"
                                @input="if(touched.email) validateField('email')"
                                required
                                placeholder="john@example.com"
                                :class="errors.email ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                            >
                            <p x-show="errors.email" x-text="errors.email" class="flex items-center gap-1 mt-1 text-sm text-red-600">
                            </p>
                            <p x-show="!errors.email && validateEmail(customerEmail)" class="flex items-center gap-1 mt-1 text-sm text-green-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Valid
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
                                @blur="validateField('phone')"
                                @input="if(touched.phone) validateField('phone')"
                                required
                                placeholder="+47 12345678"
                                :class="errors.phone ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                            >
                            <p x-show="errors.phone" x-text="errors.phone" class="flex items-center gap-1 mt-1 text-sm text-red-600">
                            </p>
                            <p x-show="!errors.phone && validatePhone(customerPhone)" class="flex items-center gap-1 mt-1 text-sm text-green-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Valid
                            </p>
                            <p x-show="!errors.phone && !validatePhone(customerPhone) && customerPhone.length === 0" class="mt-1 text-sm text-gray-500">8-15 digits, international format accepted</p>
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
                        @click="touched.date = true; touched.timeSlot = true; validateField('date'); validateField('timeSlot'); if(isStep1Valid()) nextStep();"
                        type="button"
                        :disabled="!isStep1Valid()"
                        :class="isStep1Valid() ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-300 cursor-not-allowed'"
                        class="px-4 py-2 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium"
                    >
                        Next
                    </button>
                    
                    {{-- Submit Button (step 2) --}}
                    <button 
                        x-show="currentStep === 2"
                        type="button"
                        @click="touched.name = true; touched.email = true; touched.phone = true; validateField('name'); validateField('email'); validateField('phone'); if(isStep2Valid()) { submitting = true; setTimeout(() => { alert('Booking submitted! (Form submission not yet implemented)'); submitting = false; }, 1500); }"
                        :disabled="!isStep2Valid() || submitting"
                        :class="(isStep2Valid() && !submitting) ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-300 cursor-not-allowed'"
                        class="px-4 py-2 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium flex items-center gap-2"
                    >
                        {{-- Loading Spinner --}}
                        <svg x-show="submitting" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="submitting ? 'Submitting...' : 'Complete Booking'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

{{-- Public booking page - viser tenant info og ressurser for booking --}}
