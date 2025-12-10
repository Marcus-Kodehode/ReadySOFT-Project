<?php

// File: tests/Feature/ButtonComponentTest.php

use Illuminate\Support\Facades\Blade;

test('it renders primary button by default', function () {
    $view = Blade::render('<x-button>Click me</x-button>');
    
    expect($view)->toContain('Click me')
        ->toContain('bg-blue-600')
        ->toContain('text-white')
        ->toContain('type="button"');
});

test('it renders secondary button variant', function () {
    $view = Blade::render('<x-button variant="secondary">Click me</x-button>');
    
    expect($view)->toContain('Click me')
        ->toContain('bg-white')
        ->toContain('text-gray-700')
        ->toContain('border-gray-300');
});

test('it renders danger button variant', function () {
    $view = Blade::render('<x-button variant="danger">Delete</x-button>');
    
    expect($view)->toContain('Delete')
        ->toContain('bg-red-600')
        ->toContain('text-white')
        ->toContain('hover:bg-red-700');
});

test('it renders small size', function () {
    $view = Blade::render('<x-button size="sm">Small</x-button>');
    
    expect($view)->toContain('Small')
        ->toContain('px-3')
        ->toContain('py-1.5')
        ->toContain('text-sm');
});

test('it renders medium size by default', function () {
    $view = Blade::render('<x-button>Medium</x-button>');
    
    expect($view)->toContain('Medium')
        ->toContain('px-4')
        ->toContain('py-2')
        ->toContain('text-base');
});

test('it renders large size', function () {
    $view = Blade::render('<x-button size="lg">Large</x-button>');
    
    expect($view)->toContain('Large')
        ->toContain('px-6')
        ->toContain('py-3')
        ->toContain('text-lg');
});

test('it accepts custom attributes', function () {
    $view = Blade::render('<x-button id="my-button" data-test="value">Click</x-button>');
    
    expect($view)->toContain('id="my-button"')
        ->toContain('data-test="value"');
});

test('it can combine variant and size', function () {
    $view = Blade::render('<x-button variant="danger" size="lg">Delete All</x-button>');
    
    expect($view)->toContain('Delete All')
        ->toContain('bg-red-600')
        ->toContain('px-6')
        ->toContain('py-3');
});

test('it sets button type attribute', function () {
    $view = Blade::render('<x-button type="submit">Submit</x-button>');
    
    expect($view)->toContain('type="submit"');
});

test('it defaults to button type', function () {
    $view = Blade::render('<x-button>Click</x-button>');
    
    expect($view)->toContain('type="button"');
});

// Test suite for button component - verifies variant and size props work correctly
