<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="{
        name: '{{ old('name') }}',
        email: '{{ old('email') }}',
        password: '',
        passwordConfirmation: '',
        businessName: '{{ old('business_name') }}',
        businessType: '{{ old('business_type') }}',
        slug: '{{ old('slug') }}',
        slugAvailable: null,
        checking: false,
        suggestions: [],
        checkTimeout: null,
        errors: {},
        touched: {},
        isFormValid() {
            return this.name.trim().length >= 2 &&
                   this.email.trim().length > 0 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email) &&
                   this.password.length >= 8 &&
                   this.passwordConfirmation === this.password &&
                   this.businessName.trim().length >= 3 &&
                   this.businessType !== '' &&
                   this.slug.length >= 2 &&
                   this.slugAvailable === true &&
                   Object.keys(this.errors).length === 0;
        },
        validateName() {
            this.touched.name = true;
            if (!this.name || this.name.trim().length === 0) {
                this.errors.name = 'Name is required';
            } else if (this.name.trim().length < 2) {
                this.errors.name = 'Name must be at least 2 characters';
            } else {
                delete this.errors.name;
            }
        },
        validateEmail() {
            this.touched.email = true;
            if (!this.email || this.email.trim().length === 0) {
                this.errors.email = 'Email is required';
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
                this.errors.email = 'Please enter a valid email address';
            } else {
                delete this.errors.email;
            }
        },
        validatePassword() {
            this.touched.password = true;
            if (!this.password || this.password.length === 0) {
                this.errors.password = 'Password is required';
            } else if (this.password.length < 8) {
                this.errors.password = 'Password must be at least 8 characters';
            } else {
                delete this.errors.password;
            }
            if (this.touched.passwordConfirmation) {
                this.validatePasswordConfirmation();
            }
        },
        validatePasswordConfirmation() {
            this.touched.passwordConfirmation = true;
            if (!this.passwordConfirmation || this.passwordConfirmation.length === 0) {
                this.errors.passwordConfirmation = 'Password confirmation is required';
            } else if (this.passwordConfirmation !== this.password) {
                this.errors.passwordConfirmation = 'Passwords do not match';
            } else {
                delete this.errors.passwordConfirmation;
            }
        },
        validateBusinessName() {
            this.touched.businessName = true;
            if (!this.businessName || this.businessName.trim().length === 0) {
                this.errors.businessName = 'Business name is required';
            } else if (this.businessName.trim().length < 3) {
                this.errors.businessName = 'Business name must be at least 3 characters';
            } else {
                delete this.errors.businessName;
            }
        },
        validateBusinessType() {
            this.touched.businessType = true;
            if (!this.businessType || this.businessType === '') {
                this.errors.businessType = 'Business type is required';
            } else {
                delete this.errors.businessType;
            }
        },
        generateSlug() {
            // Konverter til lowercase
            let slug = this.businessName.toLowerCase();
            
            // Erstatt norske tegn
            slug = slug.replace(/æ/g, 'ae')
                       .replace(/ø/g, 'o')
                       .replace(/å/g, 'a');
            
            // Erstatt mellomrom og spesialtegn med bindestrek
            slug = slug.replace(/[^a-z0-9]+/g, '-');
            
            // Fjern bindestreker i starten og slutten
            slug = slug.replace(/^-+|-+$/g, '');
            
            this.slug = slug;
            this.checkSlugAvailability();
        },
        checkSlugAvailability() {
            // Clear existing timeout
            if (this.checkTimeout) {
                clearTimeout(this.checkTimeout);
            }
            
            // Reset state hvis slug er tom
            if (!this.slug || this.slug.length < 2) {
                this.slugAvailable = null;
                this.suggestions = [];
                return;
            }
            
            // Debounce: Vent 500ms før API call
            this.checkTimeout = setTimeout(async () => {
                this.checking = true;
                this.suggestions = [];
                
                try {
                    const response = await fetch(`/api/check-slug?slug=${encodeURIComponent(this.slug)}`);
                    const data = await response.json();
                    
                    this.slugAvailable = data.available;
                    this.suggestions = data.suggestions || [];
                } catch (error) {
                    console.error('Error checking slug:', error);
                    this.slugAvailable = null;
                } finally {
                    this.checking = false;
                }
            }, 500);
        },
        useSlug(suggestion) {
            this.slug = suggestion;
            this.checkSlugAvailability();
        }
    }" x-init="if (businessName) generateSlug()">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name">
                {{ __('Name') }} <span class="text-red-500">*</span>
            </x-input-label>
            <input 
                id="name" 
                class="block mt-1 w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent" 
                :class="{
                    'border-green-300 focus:ring-green-500': touched.name && !errors.name && name.length > 0,
                    'border-red-300 focus:ring-red-500': errors.name,
                    'border-gray-300 focus:ring-blue-500': !touched.name || (!errors.name && name.length === 0)
                }"
                type="text" 
                name="name" 
                x-model="name"
                @blur="validateName()"
                @input="if(touched.name) validateName()"
                required 
                autofocus 
                autocomplete="name" />
            
            <!-- Success Checkmark -->
            <p x-show="touched.name && !errors.name && name.length > 0" class="flex items-center gap-1 mt-1 text-sm text-green-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Valid name
            </p>
            
            <!-- Error Message -->
            <p x-show="errors.name" x-text="errors.name" class="flex items-center gap-1 mt-1 text-sm text-red-600">
            </p>
            
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email">
                {{ __('Email') }} <span class="text-red-500">*</span>
            </x-input-label>
            <input 
                id="email" 
                class="block mt-1 w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent" 
                :class="{
                    'border-green-300 focus:ring-green-500': touched.email && !errors.email && email.length > 0,
                    'border-red-300 focus:ring-red-500': errors.email,
                    'border-gray-300 focus:ring-blue-500': !touched.email || (!errors.email && email.length === 0)
                }"
                type="email" 
                name="email" 
                x-model="email"
                @blur="validateEmail()"
                @input="if(touched.email) validateEmail()"
                required 
                autocomplete="username" />
            
            <!-- Success Checkmark -->
            <p x-show="touched.email && !errors.email && email.length > 0" class="flex items-center gap-1 mt-1 text-sm text-green-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Valid email
            </p>
            
            <!-- Error Message -->
            <p x-show="errors.email" x-text="errors.email" class="flex items-center gap-1 mt-1 text-sm text-red-600">
            </p>
            
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password">
                {{ __('Password') }} <span class="text-red-500">*</span>
            </x-input-label>

            <input 
                id="password" 
                class="block mt-1 w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                :class="{
                    'border-green-300 focus:ring-green-500': touched.password && !errors.password && password.length >= 8,
                    'border-red-300 focus:ring-red-500': errors.password,
                    'border-gray-300 focus:ring-blue-500': !touched.password || (!errors.password && password.length < 8)
                }"
                type="password"
                name="password"
                x-model="password"
                @blur="validatePassword()"
                @input="if(touched.password) validatePassword()"
                required 
                autocomplete="new-password" />
            
            <!-- Success Checkmark -->
            <p x-show="touched.password && !errors.password && password.length >= 8" class="flex items-center gap-1 mt-1 text-sm text-green-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Valid password
            </p>
            
            <!-- Error Message -->
            <p x-show="errors.password" x-text="errors.password" class="flex items-center gap-1 mt-1 text-sm text-red-600">
            </p>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation">
                {{ __('Confirm Password') }} <span class="text-red-500">*</span>
            </x-input-label>

            <input 
                id="password_confirmation" 
                class="block mt-1 w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                :class="{
                    'border-green-300 focus:ring-green-500': touched.passwordConfirmation && !errors.passwordConfirmation && passwordConfirmation.length > 0,
                    'border-red-300 focus:ring-red-500': errors.passwordConfirmation,
                    'border-gray-300 focus:ring-blue-500': !touched.passwordConfirmation || (!errors.passwordConfirmation && passwordConfirmation.length === 0)
                }"
                type="password"
                name="password_confirmation" 
                x-model="passwordConfirmation"
                @blur="validatePasswordConfirmation()"
                @input="if(touched.passwordConfirmation) validatePasswordConfirmation()"
                required 
                autocomplete="new-password" />
            
            <!-- Success Checkmark -->
            <p x-show="touched.passwordConfirmation && !errors.passwordConfirmation && passwordConfirmation.length > 0" class="flex items-center gap-1 mt-1 text-sm text-green-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Passwords match
            </p>
            
            <!-- Error Message -->
            <p x-show="errors.passwordConfirmation" x-text="errors.passwordConfirmation" class="flex items-center gap-1 mt-1 text-sm text-red-600">
            </p>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Business Name -->
        <div class="mt-4">
            <x-input-label for="business_name">
                {{ __('Business Name') }} <span class="text-red-500">*</span>
            </x-input-label>
            <input 
                id="business_name" 
                class="block mt-1 w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent" 
                :class="{
                    'border-green-300 focus:ring-green-500': touched.businessName && !errors.businessName && businessName.length > 0,
                    'border-red-300 focus:ring-red-500': errors.businessName,
                    'border-gray-300 focus:ring-blue-500': !touched.businessName || (!errors.businessName && businessName.length === 0)
                }"
                type="text" 
                name="business_name" 
                x-model="businessName"
                @input="generateSlug(); if(touched.businessName) validateBusinessName()"
                @blur="validateBusinessName()"
                required 
                autocomplete="organization" />
            
            <!-- Success Checkmark -->
            <p x-show="touched.businessName && !errors.businessName && businessName.length > 0" class="flex items-center gap-1 mt-1 text-sm text-green-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Valid business name
            </p>
            
            <!-- Error Message -->
            <p x-show="errors.businessName" x-text="errors.businessName" class="flex items-center gap-1 mt-1 text-sm text-red-600">
            </p>
            
            <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
        </div>

        <!-- Business Type -->
        <div class="mt-4">
            <x-input-label for="business_type">
                {{ __('Business Type') }} <span class="text-red-500">*</span>
            </x-input-label>
            <select 
                id="business_type" 
                name="business_type" 
                x-model="businessType"
                @blur="validateBusinessType()"
                @change="validateBusinessType()"
                class="block mt-1 w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent" 
                :class="{
                    'border-green-300 focus:ring-green-500': touched.businessType && !errors.businessType && businessType !== '',
                    'border-red-300 focus:ring-red-500': errors.businessType,
                    'border-gray-300 focus:ring-blue-500': !touched.businessType || (!errors.businessType && businessType === '')
                }"
                required>
                <option value="">{{ __('Select Business Type') }}</option>
                <option value="Cabin Rental" {{ old('business_type') == 'Cabin Rental' ? 'selected' : '' }}>Cabin Rental</option>
                <option value="Hair Salon" {{ old('business_type') == 'Hair Salon' ? 'selected' : '' }}>Hair Salon</option>
                <option value="Spa & Wellness" {{ old('business_type') == 'Spa & Wellness' ? 'selected' : '' }}>Spa & Wellness</option>
                <option value="Room Rental" {{ old('business_type') == 'Room Rental' ? 'selected' : '' }}>Room Rental</option>
                <option value="Other" {{ old('business_type') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
            
            <!-- Success Checkmark -->
            <p x-show="touched.businessType && !errors.businessType && businessType !== ''" class="flex items-center gap-1 mt-1 text-sm text-green-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Valid selection
            </p>
            
            <!-- Error Message -->
            <p x-show="errors.businessType" x-text="errors.businessType" class="flex items-center gap-1 mt-1 text-sm text-red-600">
            </p>
            
            <x-input-error :messages="$errors->get('business_type')" class="mt-2" />
        </div>

        <!-- Slug Preview -->
        <div class="mt-4">
            <x-input-label for="slug">
                {{ __('Your Booking Page URL') }} <span class="text-red-500">*</span>
            </x-input-label>
            <div class="flex items-center mt-1">
                <span class="inline-flex items-center px-3 py-2 text-sm text-gray-600 bg-gray-50 border border-r-0 border-gray-300 rounded-l-lg">
                    {{ url('/') }}/
                </span>
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        id="slug" 
                        name="slug" 
                        x-model="slug"
                        @input="checkSlugAvailability()"
                        :class="{
                            'border-green-300 focus:border-transparent focus:ring-2 focus:ring-green-500': slugAvailable === true,
                            'border-red-300 focus:border-transparent focus:ring-2 focus:ring-red-500': slugAvailable === false,
                            'border-gray-300 focus:border-transparent focus:ring-2 focus:ring-blue-500': slugAvailable === null
                        }"
                        class="w-full px-3 py-2 rounded-r-lg focus:outline-none pr-10"
                        required />
                    
                    <!-- Status Icon -->
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <!-- Checking spinner -->
                        <svg x-show="checking" class="w-5 h-5 text-gray-400 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        
                        <!-- Available checkmark -->
                        <svg x-show="!checking && slugAvailable === true" class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        
                        <!-- Not available X -->
                        <svg x-show="!checking && slugAvailable === false" class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Feedback Messages -->
            <div class="mt-1">
                <p x-show="!checking && slugAvailable === true" class="text-sm text-green-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('This URL is available!') }}
                </p>
                
                <p x-show="!checking && slugAvailable === false" class="text-sm text-red-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    {{ __('This URL is already taken') }}
                </p>
                
                <!-- Suggestions -->
                <div x-show="!checking && slugAvailable === false && suggestions.length > 0" class="mt-2">
                    <p class="text-sm text-gray-600 mb-1">{{ __('Try these alternatives:') }}</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="suggestion in suggestions" :key="suggestion">
                            <button 
                                type="button"
                                @click="useSlug(suggestion)"
                                class="px-3 py-1 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                                x-text="suggestion">
                            </button>
                        </template>
                    </div>
                </div>
                
                <p x-show="slugAvailable === null && slug.length >= 2" class="text-sm text-gray-500">
                    {{ __('Auto-generated from business name, but you can edit it manually') }}
                </p>
            </div>
            
            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <button 
                type="submit"
                :disabled="!isFormValid()"
                :class="isFormValid() ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-300 cursor-not-allowed'"
                class="ms-4 inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Register') }}
            </button>
        </div>
    </form>
</x-guest-layout>
