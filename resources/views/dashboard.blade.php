{{-- File: resources/views/dashboard.blade.php --}}
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
                                    <x-badge color="success">Active</x-badge>
                                @else
                                    <x-badge color="error">Inactive</x-badge>
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

            <!-- Quick Actions Section -->
            <div class="mt-8 bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Quick Actions</h3>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <!-- New Resource Button (Primary) -->
                    <a href="{{ route('resources.create') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Resource
                    </a>

                    <!-- SMS Settings Button (Secondary) -->
                    <a href="{{ route('dashboard.sms') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        SMS Settings
                    </a>

                    <!-- Share Booking Page Button (Secondary with Copy Icon) -->
                    <div x-data="{ 
                        copied: false,
                        copyToClipboard() {
                            const url = '{{ url('/' . auth()->user()->tenant->slug) }}';
                            navigator.clipboard.writeText(url).then(() => {
                                this.copied = true;
                                setTimeout(() => this.copied = false, 2000);
                            });
                        }
                    }">
                        <button @click="copyToClipboard()" 
                                class="inline-flex items-center justify-center w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium sm:w-auto">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span x-text="copied ? 'Link Copied!' : 'Share Booking Page'"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Upcoming Bookings Table -->
            <div class="mt-8 bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Upcoming Bookings</h3>
                </div>
                <div class="overflow-x-auto">
                    @if($upcomingBookings->isEmpty())
                        <div class="px-6 py-8 text-center">
                            <p class="text-gray-500">No upcoming bookings</p>
                        </div>
                    @else
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Resource</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Customer</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Time</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($upcomingBookings as $booking)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            {{ $booking->resource->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                            {{ $booking->customer_name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
{{-- Tenant dashboard - viser statistikk og quick actions --}}
