<?php

// File: tests/Feature/AlertComponentTest.php

use Illuminate\Support\Facades\Blade;

test('it renders info alert by default', function () {
    $view = Blade::render('<x-alert>Test message</x-alert>');
    
    expect($view)->toContain('Test message')
        ->toContain('border-blue-500')
        ->toContain('bg-blue-50');
});

test('it renders success alert', function () {
    $view = Blade::render('<x-alert type="success">Success message</x-alert>');
    
    expect($view)->toContain('Success message')
        ->toContain('border-green-500')
        ->toContain('bg-green-50');
});

test('it renders error alert', function () {
    $view = Blade::render('<x-alert type="error">Error message</x-alert>');
    
    expect($view)->toContain('Error message')
        ->toContain('border-red-500')
        ->toContain('bg-red-50');
});

test('it renders warning alert', function () {
    $view = Blade::render('<x-alert type="warning">Warning message</x-alert>');
    
    expect($view)->toContain('Warning message')
        ->toContain('border-yellow-500')
        ->toContain('bg-yellow-50');
});

test('it renders alert with title', function () {
    $view = Blade::render('<x-alert type="success" title="Success!">Message here</x-alert>');
    
    expect($view)->toContain('Success!')
        ->toContain('Message here')
        ->toContain('font-medium');
});

test('it renders alert without title', function () {
    $view = Blade::render('<x-alert type="info">Just a message</x-alert>');
    
    expect($view)->toContain('Just a message');
});

test('it renders dismissible alert', function () {
    $view = Blade::render('<x-alert type="success" :dismissible="true">Dismissible message</x-alert>');
    
    expect($view)->toContain('Dismissible message')
        ->toContain('x-data')
        ->toContain('x-show')
        ->toContain('@click');
});

test('it renders non dismissible alert by default', function () {
    $view = Blade::render('<x-alert type="info">Non-dismissible message</x-alert>');
    
    expect($view)->toContain('Non-dismissible message');
    expect($view)->not->toContain('x-data');
});

test('it includes success icon', function () {
    $view = Blade::render('<x-alert type="success">Success</x-alert>');
    
    expect($view)->toContain('M5 13l4 4L19 7'); // Checkmark path
});

test('it includes error icon', function () {
    $view = Blade::render('<x-alert type="error">Error</x-alert>');
    
    expect($view)->toContain('M12 8v4m0 4h.01'); // Error icon path
});

test('it includes warning icon', function () {
    $view = Blade::render('<x-alert type="warning">Warning</x-alert>');
    
    expect($view)->toContain('M12 9v2m0 4h.01'); // Warning icon path
});

test('it includes info icon', function () {
    $view = Blade::render('<x-alert type="info">Info</x-alert>');
    
    expect($view)->toContain('M13 16h-1v-4h-1m1-4h.01'); // Info icon path
});

test('it accepts custom attributes', function () {
    $view = Blade::render('<x-alert id="custom-alert" class="shadow-lg">Message</x-alert>');
    
    expect($view)->toContain('id="custom-alert"')
        ->toContain('shadow-lg');
});

test('it has correct base structure', function () {
    $view = Blade::render('<x-alert type="success">Test</x-alert>');
    
    expect($view)->toContain('p-4')
        ->toContain('border-l-4')
        ->toContain('rounded')
        ->toContain('flex')
        ->toContain('items-start')
        ->toContain('gap-3');
});

test('it renders complex content', function () {
    $view = Blade::render('
        <x-alert type="info" title="Getting Started">
            <p>Welcome!</p>
            <ul>
                <li>Step 1</li>
                <li>Step 2</li>
            </ul>
        </x-alert>
    ');
    
    expect($view)->toContain('Getting Started')
        ->toContain('Welcome!')
        ->toContain('Step 1')
        ->toContain('Step 2');
});

test('it falls back to info type for invalid type', function () {
    $view = Blade::render('<x-alert type="invalid">Fallback</x-alert>');
    
    expect($view)->toContain('Fallback')
        ->toContain('border-blue-500')
        ->toContain('bg-blue-50');
});

// Test suite for alert component - verifies type props and features work correctly

