<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}" x-data="{
        email: '{{ old('email', $request->email) }}',
        password: '',
        passwordConfirmation: '',
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
        validatePassword() {
            this.touched.password = true;
            if (!this.password || this.password.length === 0) {
                this.errors.password = 'Password is required';
            } else if (this.password.length < 8) {
                this.errors.password = 'Password must be at least 8 characters';
            } else {
                delete this.errors.password;
            }
            // Re-validate confirmation if it's been touched
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
        isFormValid() {
            return this.email && this.password && this.passwordConfirmation && 
                   Object.keys(this.errors).length === 0;
        }
    }">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                autofocus 
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

        <div class="flex items-center justify-end mt-4">
            <x-primary-button :disabled="!isFormValid()">
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
