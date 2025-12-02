<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen px-4 py-12 bg-gray-50">
        <div class="w-full max-w-md">
            <!-- Icon -->
            <div class="flex justify-center mb-6">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-amber-100">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>

            <!-- Card -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h1 class="mb-4 text-2xl font-bold text-center text-gray-900">
                    Your Subscription is Inactive
                </h1>
                
                <p class="mb-6 text-base text-center text-gray-600">
                    Your account subscription is currently inactive. Please contact support to activate your account and continue using our services.
                </p>

                <!-- Contact Information -->
                <div class="p-4 mb-6 border-l-4 border-blue-500 rounded bg-blue-50">
                    <div class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Need Help?</p>
                            <p class="mt-1 text-sm text-blue-700">
                                Contact our support team at 
                                <a href="mailto:support@readysoft.no" class="font-medium underline hover:text-blue-800">
                                    support@readysoft.no
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Sign Out
                        </button>
                    </form>
                    
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 font-medium text-center text-gray-700 transition-colors bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Back to Dashboard
                    </a>
                </div>
            </div>

            <!-- Additional Info -->
            <p class="mt-6 text-sm text-center text-gray-500">
                If you believe this is an error, please contact support immediately.
            </p>
        </div>
    </div>
</x-guest-layout>

{{-- 
    Inactive Subscription Page
    
    Vises når en bruker med inaktiv subscription prøver å aksessere beskyttede ruter.
    Gir tydelig informasjon om situasjonen og hvordan de kan få hjelp.
--}}
