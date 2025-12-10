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

test('it renders badge with design guide spacing', function () {
    $view = Blade::render('<x-badge>Badge</x-badge>');
    
    expect($view)->toContain('Badge')
        ->toContain('px-2')
        ->toContain('py-1');
});

test('it renders badge with gray color', function () {
    $view = Blade::render('<x-badge color="gray">Inactive</x-badge>');
    
    expect($view)->toContain('Inactive')
        ->toContain('bg-gray-100')
        ->toContain('text-gray-800');
});

test('it renders badge with custom attributes', function () {
    $view = Blade::render('<x-badge id="custom-badge" class="cursor-pointer">Custom</x-badge>');
    
    expect($view)->toContain('Custom')
        ->toContain('id="custom-badge"')
        ->toContain('cursor-pointer');
});

test('it includes design guide classes', function () {
    $view = Blade::render('<x-badge>Test</x-badge>');
    
    expect($view)->toContain('font-medium')
        ->toContain('rounded-full')
        ->toContain('text-xs');
});

test('it falls back to info color for invalid color', function () {
    $view = Blade::render('<x-badge color="invalid">Fallback</x-badge>');
    
    expect($view)->toContain('Fallback')
        ->toContain('bg-blue-100')
        ->toContain('text-blue-800');
});

// Test suite for badge component - verifies color and size props work correctly
