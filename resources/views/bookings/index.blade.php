{{-- File: resources/views/bookings/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bookings') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            {{-- Filter Tabs --}}
            <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-1 inline-flex">
                <a href="{{ route('bookings.index', ['filter' => 'all']) }}" 
                   class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $filter === 'all' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    All
                </a>
                <a href="{{ route('bookings.index', ['filter' => 'upcoming']) }}" 
                   class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $filter === 'upcoming' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    Upcoming
                </a>
                <a href="{{ route('bookings.index', ['filter' => 'past']) }}" 
                   class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $filter === 'past' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    Past
                </a>
            </div>

            {{-- Empty State --}}
            @if($bookings->isEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">No bookings found</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        @if($filter === 'upcoming')
                            You don't have any upcoming bookings yet.
                        @elseif($filter === 'past')
                            You don't have any past bookings.
                        @else
                            You don't have any bookings yet.
                        @endif
                    </p>
                </div>
            @else
                {{-- Desktop Table View --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 hidden md:table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Booking ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Resource
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Time
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bookings as $booking)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">#{{ $booking->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">{{ $booking->resource->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->resource->type }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $booking->customer_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->customer_email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($booking->status === 'confirmed')
                                            <x-badge color="success">Confirmed</x-badge>
                                        @elseif($booking->status === 'pending')
                                            <x-badge color="warning">Pending</x-badge>
                                        @elseif($booking->status === 'cancelled')
                                            <x-badge color="error">Cancelled</x-badge>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex gap-2 justify-end">
                                            <a href="{{ route('bookings.show', $booking->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 transition-colors">
                                                View Details
                                            </a>
                                            @if($booking->status !== 'cancelled')
                                                <form action="{{ route('bookings.updateStatus', $booking->id) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-800 transition-colors">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 block md:hidden">
                    @foreach($bookings as $booking)
                        <div class="p-4 border-b border-gray-200 last:border-b-0">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">#{{ $booking->id }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $booking->resource->name }}</p>
                                </div>
                                @if($booking->status === 'confirmed')
                                    <x-badge color="success">Confirmed</x-badge>
                                @elseif($booking->status === 'pending')
                                    <x-badge color="warning">Pending</x-badge>
                                @elseif($booking->status === 'cancelled')
                                    <x-badge color="error">Cancelled</x-badge>
                                @endif
                            </div>
                            
                            <div class="space-y-2 mb-3">
                                <p class="text-sm text-gray-700">
                                    <span class="font-medium">Customer:</span> {{ $booking->customer_name }}
                                </p>
                                <p class="text-sm text-gray-700">
                                    <span class="font-medium">Date:</span> {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                </p>
                                <p class="text-sm text-gray-700">
                                    <span class="font-medium">Time:</span> {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                </p>
                            </div>
                            
                            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-200">
                                <a href="{{ route('bookings.show', $booking->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                                    View Details
                                </a>
                                @if($booking->status !== 'cancelled')
                                    <form action="{{ route('bookings.updateStatus', $booking->id) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination Links --}}
                @if($bookings->hasPages())
                    <div class="mt-6">
                        {{ $bookings->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>

{{-- Booking list view - viser alle bookinger for tenant --}}
