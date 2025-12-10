{{-- File: resources/views/components-demo.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Button Component Demo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Button Component Demo</h1>
        
        <!-- Variants Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Variants</h2>
            <div class="flex flex-wrap gap-4">
                <x-button variant="primary">Primary Button</x-button>
                <x-button variant="secondary">Secondary Button</x-button>
                <x-button variant="danger">Danger Button</x-button>
            </div>
        </div>

        <!-- Sizes Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Sizes</h2>
            <div class="flex flex-wrap items-center gap-4">
                <x-button size="sm">Small</x-button>
                <x-button size="md">Medium (Default)</x-button>
                <x-button size="lg">Large</x-button>
            </div>
        </div>

        <!-- Combinations Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Variant + Size Combinations</h2>
            <div class="space-y-4">
                <div class="flex flex-wrap items-center gap-4">
                    <x-button variant="primary" size="sm">Primary Small</x-button>
                    <x-button variant="primary" size="md">Primary Medium</x-button>
                    <x-button variant="primary" size="lg">Primary Large</x-button>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <x-button variant="secondary" size="sm">Secondary Small</x-button>
                    <x-button variant="secondary" size="md">Secondary Medium</x-button>
                    <x-button variant="secondary" size="lg">Secondary Large</x-button>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <x-button variant="danger" size="sm">Danger Small</x-button>
                    <x-button variant="danger" size="md">Danger Medium</x-button>
                    <x-button variant="danger" size="lg">Danger Large</x-button>
                </div>
            </div>
        </div>

        <!-- Button Types Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Button Types</h2>
            <div class="flex flex-wrap gap-4">
                <x-button type="button">Type: Button (Default)</x-button>
                <x-button type="submit">Type: Submit</x-button>
                <x-button type="reset">Type: Reset</x-button>
            </div>
        </div>

        <!-- Custom Attributes Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Custom Attributes</h2>
            <div class="flex flex-wrap gap-4">
                <x-button id="custom-id" class="shadow-lg">With Custom ID & Class</x-button>
                <x-button disabled>Disabled Button</x-button>
                <x-button onclick="alert('Clicked!')">With onclick</x-button>
            </div>
        </div>

        <!-- Usage Examples Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Usage Examples</h2>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <pre class="text-sm text-gray-700 overflow-x-auto"><code>&lt;!-- Primary button (default) --&gt;
&lt;x-button&gt;Click me&lt;/x-button&gt;

&lt;!-- Secondary button --&gt;
&lt;x-button variant="secondary"&gt;Cancel&lt;/x-button&gt;

&lt;!-- Danger button --&gt;
&lt;x-button variant="danger"&gt;Delete&lt;/x-button&gt;

&lt;!-- Small button --&gt;
&lt;x-button size="sm"&gt;Small&lt;/x-button&gt;

&lt;!-- Large danger button --&gt;
&lt;x-button variant="danger" size="lg"&gt;Delete All&lt;/x-button&gt;

&lt;!-- Submit button --&gt;
&lt;x-button type="submit"&gt;Submit Form&lt;/x-button&gt;

&lt;!-- With custom attributes --&gt;
&lt;x-button id="my-btn" class="w-full"&gt;Full Width&lt;/x-button&gt;</code></pre>
            </div>
        </div>
    </div>
</body>
</html>

{{-- Demo page for button component - shows all variants, sizes and combinations --}}
