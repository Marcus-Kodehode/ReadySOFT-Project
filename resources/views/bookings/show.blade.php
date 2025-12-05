{{-- File: resources/views/bookings/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Booking Details') }}
            </h2>
            <a href="{{ route('bookings.index') }}" 
               class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mb-4 p-4 border-l-4 border-green-500 rounded bg-green-50">
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

            @if (session('error'))
                <div class="mb-4 p-4 border-l-4 border-red-500 rounded bg-red-50">
                    <div class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-red-800">Error</p>
                            <p class="mt-1 text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Booking Details Card --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                {{-- Header with Status --}}
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Booking #{{ $booking->id }}</h3>
                        <p class="text-sm text-gray-600 mt-1">Created {{ $booking->created_at->format('M d, Y \a\t H:i') }}</p>
                    </div>
                    <div>
                        @if($booking->status === 'confirmed')
                            <span class="px-3 py-1.5 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                                Confirmed
                            </span>
                        @elseif($booking->status === 'pending')
                            <span class="px-3 py-1.5 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-full">
                                Pending
                            </span>
                        @elseif($booking->status === 'cancelled')
                            <span class="px-3 py-1.5 text-sm font-medium text-red-800 bg-red-100 rounded-full">
                                Cancelled
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Booking Information --}}
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Resource Information --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Resource</h4>
                            <div class="space-y-2">
                                <p class="text-base font-semibold text-gray-900">{{ $booking->resource->name }}</p>
                                <p class="text-sm text-gray-600">{{ $booking->resource->type }}</p>
                                @if($booking->resource->description)
                                    <p class="text-sm text-gray-600">{{ $booking->resource->description }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Date & Time Information --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Date & Time</h4>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-base text-gray-900">{{ \Carbon\Carbon::parse($booking->booking_date)->format('l, F d, Y') }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-base text-gray-900">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Customer Information --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Customer Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Name</p>
                                <p class="text-base text-gray-900 font-medium">{{ $booking->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Email</p>
                                <a href="mailto:{{ $booking->customer_email }}" class="text-base text-blue-600 hover:text-blue-800">
                                    {{ $booking->customer_email }}
                                </a>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Phone</p>
                                <a href="tel:{{ $booking->customer_phone }}" class="text-base text-blue-600 hover:text-blue-800">
                                    {{ $booking->customer_phone }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Notes --}}
                    @if($booking->notes)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Notes</h4>
                            <p class="text-base text-gray-700 whitespace-pre-wrap">{{ $booking->notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                @if($booking->status !== 'cancelled')
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                        @if($booking->status === 'pending')
                            <form action="{{ route('bookings.updateStatus', $booking->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit"
                                        class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors font-medium">
                                    Confirm Booking
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('bookings.updateStatus', $booking->id) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit"
                                    class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors font-medium">
                                Cancel Booking
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Booking detail view - viser full informasjon om en enkelt booking --}}
