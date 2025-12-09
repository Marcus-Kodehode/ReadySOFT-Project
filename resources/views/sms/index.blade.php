{{-- File: resources/views/sms/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('SMS Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="mb-4 text-lg font-medium">SMS Settings Page</h3>
                    <p class="text-gray-600">This is a placeholder for the SMS settings page.</p>
                    <p class="mt-2 text-sm text-gray-500">
                        SMS Settings ID: {{ $smsSettings->id ?? 'Not created yet' }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Tenant ID: {{ $smsSettings->tenant_id ?? auth()->user()->tenant_id }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Enabled: {{ $smsSettings->enabled ? 'Yes' : 'No' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- SMS settings page - placeholder view for testing controller --}}
