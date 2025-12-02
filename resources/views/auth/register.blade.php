<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="{
        businessName: '{{ old('business_name') }}',
        slug: '{{ old('slug') }}',
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
            <select id="business_type" name="business_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
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
                <span class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-50 border border-r-0 border-gray-300 rounded-l-md">
                    {{ url('/') }}/
                </span>
                <input 
                    type="text" 
                    id="slug" 
                    name="slug" 
                    x-model="slug"
                    class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-r-md shadow-sm"
                    required />
            </div>
            <p class="mt-1 text-sm text-gray-500">
                {{ __('Auto-generated from business name, but you can edit it manually') }}
            </p>
            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
