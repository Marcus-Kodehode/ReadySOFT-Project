<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="{
        businessName: '{{ old('business_name') }}',
        slug: '{{ old('slug') }}',
        slugAvailable: null,
        checking: false,
        suggestions: [],
        checkTimeout: null,
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
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Business Name -->
        <div class="mt-4">
            <x-input-label for="business_name" :value="__('Business Name')" />
            <x-text-input 
                id="business_name" 
                class="block mt-1 w-full" 
                type="text" 
                name="business_name" 
                x-model="businessName"
                @input="generateSlug()"
                required 
                autocomplete="organization" />
            <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
        </div>

        <!-- Business Type -->
        <div class="mt-4">
            <x-input-label for="business_type" :value="__('Business Type')" />
            <select id="business_type" name="business_type" class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                <option value="">{{ __('Select Business Type') }}</option>
                <option value="Cabin Rental" {{ old('business_type') == 'Cabin Rental' ? 'selected' : '' }}>Cabin Rental</option>
                <option value="Hair Salon" {{ old('business_type') == 'Hair Salon' ? 'selected' : '' }}>Hair Salon</option>
                <option value="Spa & Wellness" {{ old('business_type') == 'Spa & Wellness' ? 'selected' : '' }}>Spa & Wellness</option>
                <option value="Room Rental" {{ old('business_type') == 'Room Rental' ? 'selected' : '' }}>Room Rental</option>
                <option value="Other" {{ old('business_type') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
            <x-input-error :messages="$errors->get('business_type')" class="mt-2" />
        </div>

        <!-- Slug Preview -->
        <div class="mt-4">
            <x-input-label for="slug" :value="__('Your Booking Page URL')" />
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

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
