<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" x-data="{
        email: '{{ old('email') }}',
        password: '',
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
            } else {
                delete this.errors.password;
            }
        },
        isFormValid() {
            return this.email && this.password && 
                   Object.keys(this.errors).length === 0;
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
                    'border-green-300 focus:ring-green-500': touched.password && !errors.password && password.length > 0,
                    'border-red-300 focus:ring-red-500': errors.password,
                    'border-gray-300 focus:ring-blue-500': !touched.password || (!errors.password && password.length === 0)
                }"
                type="password"
                name="password"
                x-model="password"
                @blur="validatePassword()"
                @input="if(touched.password) validatePassword()"
                required 
                autocomplete="current-password" />
            
            <!-- Success Checkmark -->
            <p x-show="touched.password && !errors.password && password.length > 0" class="flex items-center gap-1 mt-1 text-sm text-green-600">
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

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3" :disabled="!isFormValid()">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
