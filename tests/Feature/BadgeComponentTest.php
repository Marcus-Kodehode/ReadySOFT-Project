<?php

// File: tests/Feature/BadgeComponentTest.php

use Illuminate\Support\Facades\Blade;

test('it renders badge with default color', function () {
    $view = Blade::render('<x-badge>Test Badge</x-badge>');
    
    expect($view)->toContain('Test Badge')
        ->toContain('bg-blue-100')
        ->toContain('text-blue-800');
});

test('it renders badge with success color', function () {
    $view = Blade::render('<x-badge color="success">Success</x-badge>');
    
    expect($view)->toContain('Success')
        ->toContain('bg-green-100')
        ->toContain('text-green-800');
});

test('it renders badge with warning color', function () {
    $view = Blade::render('<x-badge color="warning">Warning</x-badge>');
    
    expect($view)->toContain('Warning')
        ->toContain('bg-yellow-100')
        ->toContain('text-yellow-800');
});

test('it renders badge with error color', function () {
    $view = Blade::render('<x-badge color="error">Error</x-badge>');
    
    expect($view)->toContain('Error')
        ->toContain('bg-red-100')
        ->toContain('text-red-800');
});

test('it renders badge with info color', function () {
    $view = Blade::render('<x-badge color="info">Info</x-badge>');
    
    expect($view)->toContain('Info')
        ->toContain('bg-blue-100')
        ->toContain('text-blue-800');
});

test('it renders badge with small size', function () {
    $view = Blade::render('<x-badge size="sm">Small</x-badge>');
    
    expect($view)->toContain('Small')
        ->toContain('px-2')
        ->toContain('py-0.5');
});

test('it renders badge with medium size', function () {
    $view = Blade::render('<x-badge size="md">Medium</x-badge>');
    
    expect($view)->toContain('Medium')
        ->toContain('px-2')
        ->toContain('py-1');
});

test('it renders badge with large size', function () {
    $view = Blade::render('<x-badge size="lg">Large</x-badge>');
    
    expect($view)->toContain('Large')
        ->toContain('px-3')
        ->toContain('py-1.5');
});

test('it renders badge with combined color and size', function () {
    $view = Blade::render('<x-badge color="success" size="lg">Success Large</x-badge>');
    
    expect($view)->toContain('Success Large')
        ->toContain('bg-green-100')
        ->toContain('text-green-800')
        ->toContain('px-3')
        ->toContain('py-1.5');
});

test('it renders badge with custom attributes', function () {
    $view = Blade::render('<x-badge id="custom-badge" class="cursor-pointer">Custom</x-badge>');
    
    expect($view)->toContain('Custom')
        ->toContain('id="custom-badge"')
        ->toContain('cursor-pointer');
});

test('it includes base classes', function () {
    $view = Blade::render('<x-badge>Test</x-badge>');
    
    expect($view)->toContain('inline-flex')
        ->toContain('items-center')
        ->toContain('font-medium')
        ->toContain('rounded-full');
});

test('it falls back to info color for invalid color', function () {
    $view = Blade::render('<x-badge color="invalid">Fallback</x-badge>');
    
    expect($view)->toContain('Fallback')
        ->toContain('bg-blue-100')
        ->toContain('text-blue-800');
});

test('it falls back to medium size for invalid size', function () {
    $view = Blade::render('<x-badge size="invalid">Fallback</x-badge>');
    
    expect($view)->toContain('Fallback')
        ->toContain('px-2')
        ->toContain('py-1');
});

// Test suite for badge component - verifies color and size props work correctly
