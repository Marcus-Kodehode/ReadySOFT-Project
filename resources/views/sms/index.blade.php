{{-- File: resources/views/sms/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('SMS Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 border-l-4 border-green-500 rounded bg-green-50">
                    <div class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-green-800">Success!</p>
                            <p class="mt-1 text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- SMS Settings Form -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <h3 class="mb-2 text-lg font-semibold text-gray-900">SMS Configuration</h3>
                    <p class="mb-6 text-sm text-gray-600">Configure your Teletopia API key to enable SMS notifications for bookings.</p>

                    <form method="POST" action="{{ route('dashboard.sms.update') }}" x-data="{ loading: false }" @submit="loading = true">
                        @csrf
                        @method('PUT')

                        <!-- API Key Field -->
                        <div class="mb-6">
                            <label for="api_key" class="block mb-1 text-sm font-medium text-gray-700">
                                API Key
                            </label>
                            <input 
                                type="password" 
                                id="api_key" 
                                name="api_key"
                                value="{{ old('api_key', $smsSettings->api_key ? '••••••••••••' : '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('api_key') border-red-300 @enderror"
                                placeholder="Enter your Teletopia API key"
                                required>
                            
                            @error('api_key')
                                <p class="flex items-center gap-1 mt-1 text-sm text-red-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror

                            <p class="mt-2 text-sm text-gray-500">
                                Where to find your API key? 
                                <a href="https://teletopia.no/api-keys" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                                    Visit Teletopia Dashboard
                                </a>
                            </p>
                        </div>

                        <!-- Enable SMS Checkbox -->
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="enabled" 
                                    value="1"
                                    {{ old('enabled', $smsSettings->enabled) ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Enable SMS notifications</span>
                            </label>
                            <p class="mt-1 ml-6 text-sm text-gray-500">
                                When enabled, customers will receive SMS confirmations for their bookings.
                            </p>
                        </div>

                        <!-- Save Button -->
                        <div class="flex justify-end">
                            <button 
                                type="submit"
                                :disabled="loading"
                                class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!loading">Save Settings</span>
                                <span x-show="loading" class="flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Saving...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Test SMS Section -->
            <div class="mt-6 overflow-hidden bg-white shadow-sm sm:rounded-lg border border-gray-200" x-data="{ 
                loading: false, 
                message: '', 
                messageType: '',
                phoneNumber: '',
                smsMessage: '',
                wordCount: 0,
                charCount: 0,
                updateCounts() {
                    this.wordCount = this.smsMessage.trim().split(/\s+/).filter(w => w.length > 0).length;
                    this.charCount = this.smsMessage.length;
                }
            }">
                <div class="p-6">
                    <h3 class="mb-2 text-lg font-semibold text-gray-900">Test SMS</h3>
                    <p class="mb-4 text-sm text-gray-600">Send a test SMS to verify your configuration is working correctly.</p>
                    
                    <!-- VIKTIG ADVARSEL -->
                    <div class="mb-6 p-4 border-l-4 border-yellow-500 rounded bg-yellow-50">
                        <div class="flex items-start gap-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-yellow-800">⚠️ IMPORTANT - LIVE SMS CREDITS</p>
                                <p class="mt-1 text-sm text-yellow-700">Each test SMS costs 1 credit. Maximum 50 words and 160 characters to ensure only 1 SMS is sent.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Test Result Message -->
                    <div x-show="message" x-transition class="mb-4">
                        <div :class="{
                            'border-green-500 bg-green-50': messageType === 'success',
                            'border-red-500 bg-red-50': messageType === 'error'
                        }" class="p-4 border-l-4 rounded">
                            <div class="flex items-start gap-3">
                                <svg x-show="messageType === 'success'" class="flex-shrink-0 w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <svg x-show="messageType === 'error'" class="flex-shrink-0 w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p :class="{
                                        'text-green-800': messageType === 'success',
                                        'text-red-800': messageType === 'error'
                                    }" class="text-sm font-medium" x-text="messageType === 'success' ? 'Success!' : 'Error'"></p>
                                    <p :class="{
                                        'text-green-700': messageType === 'success',
                                        'text-red-700': messageType === 'error'
                                    }" class="mt-1 text-sm" x-text="message"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="
                        if (wordCount > 50) {
                            message = 'Message exceeds 50 words limit. Please shorten your message.';
                            messageType = 'error';
                            return;
                        }
                        if (charCount > 160) {
                            message = 'Message exceeds 160 characters limit. Please shorten your message.';
                            messageType = 'error';
                            return;
                        }
                        loading = true;
                        message = '';
                        fetch('{{ route('dashboard.sms.test') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ 
                                phone_number: phoneNumber,
                                message: smsMessage
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            loading = false;
                            message = data.message + (data.credits_used ? ' (Credits used: ' + data.credits_used + ')' : '');
                            messageType = data.success ? 'success' : 'error';
                        })
                        .catch(error => {
                            loading = false;
                            message = 'An error occurred while sending the test SMS.';
                            messageType = 'error';
                        });
                    ">
                        <!-- Phone Number Field -->
                        <div class="mb-4">
                            <label for="phone_number" class="block mb-1 text-sm font-medium text-gray-700">
                                Phone Number
                            </label>
                            <input 
                                type="tel" 
                                id="phone_number" 
                                x-model="phoneNumber"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="90084821 or 4790084821"
                                required>
                            <p class="mt-1 text-sm text-gray-500">
                                Norwegian 8-digit number (e.g., 90084821) or with country code (e.g., 4790084821). No + symbol needed.
                            </p>
                        </div>

                        <!-- SMS Message Field -->
                        <div class="mb-4">
                            <label for="sms_message" class="block mb-1 text-sm font-medium text-gray-700">
                                Test Message
                            </label>
                            <textarea 
                                id="sms_message" 
                                x-model="smsMessage"
                                @input="updateCounts()"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter your test message here..."
                                required></textarea>
                            
                            <!-- Character and Word Counter -->
                            <div class="flex justify-between mt-2 text-sm">
                                <div>
                                    <span :class="{ 'text-red-600 font-semibold': wordCount > 50, 'text-gray-600': wordCount <= 50 }">
                                        Words: <span x-text="wordCount"></span>/50
                                    </span>
                                    <span class="mx-2">|</span>
                                    <span :class="{ 'text-red-600 font-semibold': charCount > 160, 'text-gray-600': charCount <= 160 }">
                                        Characters: <span x-text="charCount"></span>/160
                                    </span>
                                </div>
                                <div>
                                    <span :class="{ 
                                        'text-green-600 font-semibold': wordCount <= 50 && charCount <= 160 && charCount > 0,
                                        'text-red-600 font-semibold': wordCount > 50 || charCount > 160,
                                        'text-gray-400': charCount === 0
                                    }">
                                        <span x-show="wordCount <= 50 && charCount <= 160 && charCount > 0">✓ 1 SMS</span>
                                        <span x-show="wordCount > 50 || charCount > 160">⚠ Too long!</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Send Test SMS Button -->
                        <div class="flex justify-end">
                            <button 
                                type="submit"
                                :disabled="loading || wordCount > 50 || charCount > 160 || charCount === 0"
                                class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!loading">Send Test SMS (1 Credit)</span>
                                <span x-show="loading" class="flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Sending...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- SMS settings page - konfigurer Teletopia og test SMS med 50-ords grense --}}
