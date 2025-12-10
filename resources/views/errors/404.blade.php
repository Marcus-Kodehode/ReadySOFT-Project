{{-- File: resources/views/errors/404.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Not Found - Schedulo</title>
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
            </div>
        </div>
    </nav>
    
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="max-w-md w-full text-center">
            <!-- Error Icon -->
            <div class="flex justify-center mb-6">
                <div class="flex items-center justify-center w-20 h-20 bg-red-100 rounded-full">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>

            <!-- Error Message -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                Tenant Not Found
            </h1>
            
            <p class="text-lg text-gray-600 mb-8">
                The page you're looking for doesn't exist
            </p>

            <!-- Action Button -->
            <a href="{{ url('/') }}" 
               class="inline-block px-6 py-3 text-white bg-blue-600 rounded-lg hover:bg-blue-700 
                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                      transition-colors font-medium">
                Go to Home Page
            </a>

            <!-- Additional Help Text -->
            <p class="mt-8 text-sm text-gray-500">
                If you believe this is an error, please contact support.
            </p>
        </div>
    </div>
</body>
</html>

{{-- Custom 404 error page - vises når tenant slug ikke finnes --}}
