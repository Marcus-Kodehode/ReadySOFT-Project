<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" x-data="{
        email: '{{ old('email') }}',
        errors: {},
        touched: {},
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
        isFormValid() {
            return this.email && Object.keys(this.errors).length === 0;
        }
    }">
        @csrf

        <!-- Email Address -->
        <div>
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
                autofocus />
            
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

        <div class="flex items-center justify-end mt-4">
            <x-primary-button :disabled="!isFormValid()">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
