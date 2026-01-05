<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" x-data="{
        name: '{{ old('name', $user->name) }}',
        email: '{{ old('email', $user->email) }}',
        errors: {},
        touched: {},
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
        isFormValid() {
            return this.name && this.email && Object.keys(this.errors).length === 0;
        }
    }">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name">
                {{ __('Name') }} <span class="text-red-500">*</span>
            </x-input-label>
            <input 
                id="name" 
                name="name" 
                type="text" 
                class="mt-1 block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                :class="{
                    'border-green-300 focus:ring-green-500': touched.name && !errors.name && name.length > 0,
                    'border-red-300 focus:ring-red-500': errors.name,
                    'border-gray-300 focus:ring-blue-500': !touched.name || (!errors.name && name.length === 0)
                }"
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
            
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email">
                {{ __('Email') }} <span class="text-red-500">*</span>
            </x-input-label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                class="mt-1 block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                :class="{
                    'border-green-300 focus:ring-green-500': touched.email && !errors.email && email.length > 0,
                    'border-red-300 focus:ring-red-500': errors.email,
                    'border-gray-300 focus:ring-blue-500': !touched.email || (!errors.email && email.length === 0)
                }"
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
            
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button :disabled="!isFormValid()">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
