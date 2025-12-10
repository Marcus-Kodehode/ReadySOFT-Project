{{-- File: resources/views/public/booking-confirmation.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - Schedulo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation Header -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" class="flex items-center gap-2">
                    <img src="{{ asset('images/icons/readysoft2.png') }}" alt="Schedulo Logo" class="h-8 w-auto">
                    <span class="text-lg sm:text-xl font-bold text-blue-600">Schedulo</span>
                </a>
                <a href="/" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">
                    ← Back to Home
                </a>
            </div>
        </div>
    </nav>
    
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full">
            <!-- Success Icon -->
            <div class="flex justify-center mb-6">
                <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Confirmation Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                <h1 class="text-2xl font-bold text-gray-900 text-center mb-2">Booking Confirmed!</h1>
                <p class="text-center text-gray-600 mb-6">Your booking has been successfully confirmed.</p>

                <!-- Booking Details -->
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Booking ID:</span>
                        <span class="text-sm font-semibold text-gray-900">#{{ $booking->id }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Resource:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $booking->resource->name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Date:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Time:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm font-medium text-gray-600">Customer:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $booking->customer_name }}</span>
                    </div>
                </div>

                <!-- Notification Message -->
                <div class="p-4 border-l-4 border-blue-500 rounded bg-blue-50 mb-6">
                    <div class="flex items-start gap-3">
                        <svg class="flex-shrink-0 w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm text-blue-700">
                                You will receive a confirmation via email/SMS
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <a href="{{ route('booking.show', ['slug' => $booking->resource->tenant->slug]) }}" 
                   class="block w-full px-4 py-3 text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
                    Book Another
                </a>
            </div>
        </div>
    </div>
</body>
</html>

{{-- Public booking confirmation page - viser bekreftelse etter vellykket booking --}}
