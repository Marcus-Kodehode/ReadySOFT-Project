<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Welcome, {{ auth()->user()->name }}!
                </h1>
            </div>

            <!-- Stat Cards Grid -->
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                <!-- Bookings Today Card -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Bookings Today</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $bookingsToday }}</p>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Bookings This Week Card -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Bookings This Week</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $bookingsThisWeek }}</p>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Resources Card -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Active Resources</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $activeResources }}</p>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Subscription Status Card -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Subscription Status</p>
                            <div class="mt-2">
                                @if($subscriptionStatus)
                                    <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
