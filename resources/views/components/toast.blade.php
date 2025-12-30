{{-- File: resources/views/components/toast.blade.php --}}

{{-- 
    Toast Notification Component
    
    Usage:
    <script>
    window.dispatchEvent(new CustomEvent('notify', {
        detail: 'Your message here!'
    }));
    </script>
--}}

<div x-data="{ 
        show: false, 
        message: '',
        timeoutId: null
    }" 
     @notify.window="
        show = true; 
        message = $event.detail; 
        if (timeoutId) clearTimeout(timeoutId);
        timeoutId = setTimeout(() => show = false, 4000)
     "
     x-cloak>
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-4"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-4"
         class="fixed top-4 right-4 z-50 max-w-sm w-full">
        <div class="flex items-start gap-3 p-4 bg-white border border-gray-200 rounded-lg shadow-lg">
            <!-- Success Icon -->
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            
            <!-- Message -->
            <div class="flex-1 pt-0.5">
                <p class="text-sm font-medium text-gray-900" x-text="message"></p>
            </div>
            
            <!-- Close Button -->
            <button @click="show = false; if (timeoutId) clearTimeout(timeoutId)" 
                    type="button"
                    class="flex-shrink-0 inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded">
                <span class="sr-only">Close</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>
</div>

{{-- Toast notification component - global notification system med Alpine.js --}}
