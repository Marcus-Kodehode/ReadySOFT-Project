{{-- File: resources/views/components-demo.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Component Demo - Button, Card, Badge & Alert</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Component Demo - Button, Card, Badge & Alert</h1>
        
        <!-- Button Component Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 pb-4 border-b-2 border-gray-200">Button Component</h2>
        
        <!-- Variants Section -->
        <div class="mb-12">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Variants</h3>
            <div class="flex flex-wrap gap-4">
                <x-button variant="primary">Primary Button</x-button>
                <x-button variant="secondary">Secondary Button</x-button>
                <x-button variant="danger">Danger Button</x-button>
            </div>
        </div>

        <!-- Sizes Section -->
        <div class="mb-12">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Sizes</h3>
            <div class="flex flex-wrap items-center gap-4">
                <x-button size="sm">Small</x-button>
                <x-button size="md">Medium (Default)</x-button>
                <x-button size="lg">Large</x-button>
            </div>
        </div>

        <!-- Combinations Section -->
        <div class="mb-12">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Variant + Size Combinations</h3>
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
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Button Types</h3>
            <div class="flex flex-wrap gap-4">
                <x-button type="button">Type: Button (Default)</x-button>
                <x-button type="submit">Type: Submit</x-button>
                <x-button type="reset">Type: Reset</x-button>
            </div>
        </div>

        <!-- Custom Attributes Section -->
        <div class="mb-12">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Custom Attributes</h3>
            <div class="flex flex-wrap gap-4">
                <x-button id="custom-id" class="shadow-lg">With Custom ID & Class</x-button>
                <x-button disabled>Disabled Button</x-button>
                <x-button onclick="alert('Clicked!')">With onclick</x-button>
            </div>
        </div>

        <!-- Usage Examples Section -->
        <div class="mb-12">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Usage Examples</h3>
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

        <!-- Card Component Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 pb-4 border-b-2 border-gray-200">Card Component</h2>

            <!-- Basic Card Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Basic Card</h3>
                <x-card>
                    <p class="text-gray-700">This is a basic card with default padding. It uses the slot for content.</p>
                </x-card>
            </div>

            <!-- Card with Header Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Card with Header</h3>
                <x-card>
                    <x-slot name="header">
                        <h3 class="text-lg font-semibold text-gray-900">Card Title</h3>
                    </x-slot>
                    <p class="text-gray-700">This card has a header slot with a title. The header is separated from the content with a border.</p>
                </x-card>
            </div>

            <!-- Card with Footer Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Card with Footer</h3>
                <x-card>
                    <p class="text-gray-700 mb-4">This card has a footer slot with action buttons.</p>
                    <x-slot name="footer">
                        <div class="flex justify-end gap-3">
                            <x-button variant="secondary" size="sm">Cancel</x-button>
                            <x-button variant="primary" size="sm">Save</x-button>
                        </div>
                    </x-slot>
                </x-card>
            </div>

            <!-- Card with Header and Footer Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Card with Header and Footer</h3>
                <x-card>
                    <x-slot name="header">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Complete Card</h3>
                            <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Active</span>
                        </div>
                    </x-slot>
                    <p class="text-gray-700">This card demonstrates both header and footer slots. Perfect for forms, dialogs, or content sections that need clear separation.</p>
                    <x-slot name="footer">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Last updated: 2 hours ago</span>
                            <div class="flex gap-3">
                                <x-button variant="secondary" size="sm">Edit</x-button>
                                <x-button variant="danger" size="sm">Delete</x-button>
                            </div>
                        </div>
                    </x-slot>
                </x-card>
            </div>

            <!-- Card without Padding Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Card without Padding</h3>
                <x-card :padding="false">
                    <div class="p-4 bg-blue-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Custom Layout</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700">This card has padding disabled, allowing for custom layouts with different padding for different sections.</p>
                    </div>
                    <div class="p-4 bg-gray-50">
                        <p class="text-sm text-gray-600">Custom footer section with different background</p>
                    </div>
                </x-card>
            </div>

            <!-- Stat Card Example Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Stat Card Example</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <x-card>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Users</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">1,234</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </x-card>
                    <x-card>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Revenue</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">$45.2k</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </x-card>
                    <x-card>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Active Projects</p>
                                <p class="mt-2 text-3xl font-bold text-gray-900">24</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-full">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Usage Examples Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Usage Examples</h3>
                <x-card>
                    <pre class="text-sm text-gray-700 overflow-x-auto"><code>&lt;!-- Basic card --&gt;
&lt;x-card&gt;
    &lt;p&gt;Card content here&lt;/p&gt;
&lt;/x-card&gt;

&lt;!-- Card with header --&gt;
&lt;x-card&gt;
    &lt;x-slot name="header"&gt;
        &lt;h3&gt;Card Title&lt;/h3&gt;
    &lt;/x-slot&gt;
    &lt;p&gt;Card content&lt;/p&gt;
&lt;/x-card&gt;

&lt;!-- Card with footer --&gt;
&lt;x-card&gt;
    &lt;p&gt;Card content&lt;/p&gt;
    &lt;x-slot name="footer"&gt;
        &lt;x-button&gt;Action&lt;/x-button&gt;
    &lt;/x-slot&gt;
&lt;/x-card&gt;

&lt;!-- Card with header and footer --&gt;
&lt;x-card&gt;
    &lt;x-slot name="header"&gt;
        &lt;h3&gt;Title&lt;/h3&gt;
    &lt;/x-slot&gt;
    &lt;p&gt;Content&lt;/p&gt;
    &lt;x-slot name="footer"&gt;
        &lt;x-button&gt;Save&lt;/x-button&gt;
    &lt;/x-slot&gt;
&lt;/x-card&gt;

&lt;!-- Card without padding (for custom layouts) --&gt;
&lt;x-card :padding="false"&gt;
    &lt;div class="p-4"&gt;Custom layout&lt;/div&gt;
&lt;/x-card&gt;

&lt;!-- Card with custom classes --&gt;
&lt;x-card class="hover:shadow-lg transition-shadow"&gt;
    &lt;p&gt;Hover me!&lt;/p&gt;
&lt;/x-card&gt;</code></pre>
                </x-card>
            </div>
        </div>

        <!-- Badge Component Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 pb-4 border-b-2 border-gray-200">Badge Component</h2>

            <!-- Color Variants Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Color Variants</h3>
                <div class="flex flex-wrap gap-4">
                    <x-badge color="success">Success</x-badge>
                    <x-badge color="warning">Warning</x-badge>
                    <x-badge color="error">Error</x-badge>
                    <x-badge color="info">Info (Default)</x-badge>
                </div>
            </div>

            <!-- Sizes Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Sizes</h3>
                <div class="flex flex-wrap items-center gap-4">
                    <x-badge size="sm">Small</x-badge>
                    <x-badge size="md">Medium (Default)</x-badge>
                    <x-badge size="lg">Large</x-badge>
                </div>
            </div>

            <!-- Color + Size Combinations Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Color + Size Combinations</h3>
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-4">
                        <x-badge color="success" size="sm">Success Small</x-badge>
                        <x-badge color="success" size="md">Success Medium</x-badge>
                        <x-badge color="success" size="lg">Success Large</x-badge>
                    </div>
                    <div class="flex flex-wrap items-center gap-4">
                        <x-badge color="warning" size="sm">Warning Small</x-badge>
                        <x-badge color="warning" size="md">Warning Medium</x-badge>
                        <x-badge color="warning" size="lg">Warning Large</x-badge>
                    </div>
                    <div class="flex flex-wrap items-center gap-4">
                        <x-badge color="error" size="sm">Error Small</x-badge>
                        <x-badge color="error" size="md">Error Medium</x-badge>
                        <x-badge color="error" size="lg">Error Large</x-badge>
                    </div>
                    <div class="flex flex-wrap items-center gap-4">
                        <x-badge color="info" size="sm">Info Small</x-badge>
                        <x-badge color="info" size="md">Info Medium</x-badge>
                        <x-badge color="info" size="lg">Info Large</x-badge>
                    </div>
                </div>
            </div>

            <!-- Real-world Examples Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Real-world Examples</h3>
                <x-card>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">Subscription Status</span>
                            <x-badge color="success">Active</x-badge>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">Payment Status</span>
                            <x-badge color="warning">Pending</x-badge>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">Server Status</span>
                            <x-badge color="error">Down</x-badge>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">Notification</span>
                            <x-badge color="info">3 New</x-badge>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Custom Attributes Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Custom Attributes</h3>
                <div class="flex flex-wrap gap-4">
                    <x-badge id="custom-badge" class="cursor-pointer hover:opacity-80">Clickable Badge</x-badge>
                    <x-badge color="success" title="This is a tooltip">Hover for Tooltip</x-badge>
                </div>
            </div>

            <!-- Usage Examples Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Usage Examples</h3>
                <x-card>
                    <pre class="text-sm text-gray-700 overflow-x-auto"><code>&lt;!-- Info badge (default) --&gt;
&lt;x-badge&gt;Info&lt;/x-badge&gt;

&lt;!-- Success badge --&gt;
&lt;x-badge color="success"&gt;Active&lt;/x-badge&gt;

&lt;!-- Warning badge --&gt;
&lt;x-badge color="warning"&gt;Pending&lt;/x-badge&gt;

&lt;!-- Error badge --&gt;
&lt;x-badge color="error"&gt;Failed&lt;/x-badge&gt;

&lt;!-- Small badge --&gt;
&lt;x-badge size="sm"&gt;Small&lt;/x-badge&gt;

&lt;!-- Large success badge --&gt;
&lt;x-badge color="success" size="lg"&gt;Completed&lt;/x-badge&gt;

&lt;!-- With custom attributes --&gt;
&lt;x-badge id="my-badge" class="cursor-pointer"&gt;Clickable&lt;/x-badge&gt;</code></pre>
                </x-card>
            </div>
        </div>

        <!-- Alert Component Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 pb-4 border-b-2 border-gray-200">Alert Component</h2>

            <!-- Type Variants Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Type Variants</h3>
                <div class="space-y-4">
                    <x-alert type="success" title="Success!">
                        Your changes have been saved successfully.
                    </x-alert>
                    <x-alert type="error" title="Error">
                        There was a problem processing your request.
                    </x-alert>
                    <x-alert type="warning" title="Warning">
                        Your subscription will expire in 3 days.
                    </x-alert>
                    <x-alert type="info" title="Information">
                        This is an informational message for you.
                    </x-alert>
                </div>
            </div>

            <!-- Without Title Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Without Title</h3>
                <div class="space-y-4">
                    <x-alert type="success">
                        Operation completed successfully.
                    </x-alert>
                    <x-alert type="error">
                        An error occurred while processing your request.
                    </x-alert>
                    <x-alert type="warning">
                        Please review your settings before continuing.
                    </x-alert>
                    <x-alert type="info">
                        You have 3 new notifications.
                    </x-alert>
                </div>
            </div>

            <!-- Dismissible Alerts Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Dismissible Alerts</h3>
                <div class="space-y-4">
                    <x-alert type="success" title="Success!" :dismissible="true">
                        This alert can be dismissed by clicking the X button.
                    </x-alert>
                    <x-alert type="info" :dismissible="true">
                        This is a dismissible info alert without a title.
                    </x-alert>
                </div>
            </div>

            <!-- Real-world Examples Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Real-world Examples</h3>
                <div class="space-y-4">
                    <x-alert type="success" title="Booking Confirmed!">
                        Your booking for <strong>Cabin #3</strong> on <strong>December 15, 2025</strong> has been confirmed. You will receive a confirmation email shortly.
                    </x-alert>
                    
                    <x-alert type="error" title="Payment Failed">
                        We couldn't process your payment. Please check your card details and try again.
                    </x-alert>
                    
                    <x-alert type="warning" title="Subscription Expiring Soon">
                        Your subscription will expire on <strong>December 20, 2025</strong>. Renew now to avoid service interruption.
                    </x-alert>
                    
                    <x-alert type="info" title="New Feature Available">
                        We've added SMS notifications! Configure your settings in the dashboard to get started.
                    </x-alert>
                </div>
            </div>

            <!-- With Custom Content Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">With Custom Content</h3>
                <x-alert type="info" title="Getting Started">
                    <p class="mb-2">Welcome to ReadySoft! Here's how to get started:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Create your first resource</li>
                        <li>Set up your availability</li>
                        <li>Share your booking page</li>
                    </ul>
                </x-alert>
            </div>

            <!-- Custom Attributes Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Custom Attributes</h3>
                <x-alert type="success" id="custom-alert" class="shadow-lg">
                    This alert has custom ID and additional shadow class.
                </x-alert>
            </div>

            <!-- Usage Examples Section -->
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Usage Examples</h3>
                <x-card>
                    <pre class="text-sm text-gray-700 overflow-x-auto"><code>&lt;!-- Info alert (default) --&gt;
&lt;x-alert&gt;
    This is an info message.
&lt;/x-alert&gt;

&lt;!-- Success alert with title --&gt;
&lt;x-alert type="success" title="Success!"&gt;
    Your changes have been saved.
&lt;/x-alert&gt;

&lt;!-- Error alert --&gt;
&lt;x-alert type="error" title="Error"&gt;
    Something went wrong.
&lt;/x-alert&gt;

&lt;!-- Warning alert --&gt;
&lt;x-alert type="warning" title="Warning"&gt;
    Please review your settings.
&lt;/x-alert&gt;

&lt;!-- Dismissible alert --&gt;
&lt;x-alert type="info" :dismissible="true"&gt;
    This alert can be closed.
&lt;/x-alert&gt;

&lt;!-- Alert with custom content --&gt;
&lt;x-alert type="success" title="Welcome!"&gt;
    &lt;p&gt;Welcome to our platform!&lt;/p&gt;
    &lt;ul&gt;
        &lt;li&gt;Step 1&lt;/li&gt;
        &lt;li&gt;Step 2&lt;/li&gt;
    &lt;/ul&gt;
&lt;/x-alert&gt;

&lt;!-- With custom attributes --&gt;
&lt;x-alert type="error" id="my-alert" class="mb-4"&gt;
    Custom alert with ID and margin.
&lt;/x-alert&gt;</code></pre>
                </x-card>
            </div>
        </div>
    </div>
</body>
</html>

{{-- Demo page for button, card, badge, and alert components - shows all variants and usage examples --}}
