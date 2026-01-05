<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6" x-data="{
        currentPassword: '',
        password: '',
        passwordConfirmation: '',
        errors: {},
        touched: {},
        validateCurrentPassword() {
            this.touched.currentPassword = true;
            if (!this.currentPassword || this.currentPassword.length === 0) {
                this.errors.currentPassword = 'Current password is required';
            } else {
                delete this.errors.currentPassword;
            }
        },
        validatePassword() {
            this.touched.password = true;
            if (!this.password || this.password.length === 0) {
                this.errors.password = 'New password is required';
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
            return this.currentPassword && this.password && this.passwordConfirmation && 
                   Object.keys(this.errors).length === 0;
        }
    }">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password">
                {{ __('Current Password') }} <span class="text-red-500">*</span>
            </x-input-label>
            <input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                class="mt-1 block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                :class="{
                    'border-green-300 focus:ring-green-500': touched.currentPassword && !errors.currentPassword && currentPassword.length > 0,
                    'border-red-300 focus:ring-red-500': errors.currentPassword,
                    'border-gray-300 focus:ring-blue-500': !touched.currentPassword || (!errors.currentPassword && currentPassword.length === 0)
                }"
                x-model="currentPassword"
                @blur="validateCurrentPassword()"
                @input="if(touched.currentPassword) validateCurrentPassword()"
                autocomplete="current-password" 
                required />
            
            <!-- Success Checkmark -->
            <p x-show="touched.currentPassword && !errors.currentPassword && currentPassword.length > 0" class="flex items-center gap-1 mt-1 text-sm text-green-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Valid
            </p>
            
            <!-- Error Message -->
            <p x-show="errors.currentPassword" x-text="errors.currentPassword" class="flex items-center gap-1 mt-1 text-sm text-red-600">
            </p>
            
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password">
                {{ __('New Password') }} <span class="text-red-500">*</span>
            </x-input-label>
            <input 
                id="update_password_password" 
                name="password" 
                type="password" 
                class="mt-1 block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                :class="{
                    'border-green-300 focus:ring-green-500': touched.password && !errors.password && password.length >= 8,
                    'border-red-300 focus:ring-red-500': errors.password,
                    'border-gray-300 focus:ring-blue-500': !touched.password || (!errors.password && password.length < 8)
                }"
                x-model="password"
                @blur="validatePassword()"
                @input="if(touched.password) validatePassword()"
                autocomplete="new-password" 
                required />
            
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
            
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation">
                {{ __('Confirm Password') }} <span class="text-red-500">*</span>
            </x-input-label>
            <input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                type="password" 
                class="mt-1 block w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                :class="{
                    'border-green-300 focus:ring-green-500': touched.passwordConfirmation && !errors.passwordConfirmation && passwordConfirmation.length > 0,
                    'border-red-300 focus:ring-red-500': errors.passwordConfirmation,
                    'border-gray-300 focus:ring-blue-500': !touched.passwordConfirmation || (!errors.passwordConfirmation && passwordConfirmation.length === 0)
                }"
                x-model="passwordConfirmation"
                @blur="validatePasswordConfirmation()"
                @input="if(touched.passwordConfirmation) validatePasswordConfirmation()"
                autocomplete="new-password" 
                required />
            
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
            
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button :disabled="!isFormValid()">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
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
