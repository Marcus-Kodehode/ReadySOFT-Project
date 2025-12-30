<?php

// File: tests/Feature/ToastComponentTest.php

use Illuminate\Support\Facades\Blade;

test('toast component has required alpine attributes', function () {
    $view = Blade::render('<x-toast />');
    
    expect($view)->toContain('x-data')
        ->toContain('show')
        ->toContain('message')
        ->toContain('@notify.window');
});

test('toast component has auto dismiss timeout', function () {
    $view = Blade::render('<x-toast />');
    
    expect($view)->toContain('setTimeout')
        ->toContain('4000');
});

test('toast component has close button', function () {
    $view = Blade::render('<x-toast />');
    
    expect($view)->toContain('@click')
        ->toContain('show = false');
});

test('toast component has transition animations', function () {
    $view = Blade::render('<x-toast />');
    
    expect($view)->toContain('x-transition')
        ->toContain('ease-out')
        ->toContain('ease-in');
});

test('toast component is positioned in top right corner', function () {
    $view = Blade::render('<x-toast />');
    
    expect($view)->toContain('fixed')
        ->toContain('top-4')
        ->toContain('right-4')
        ->toContain('z-50');
});

test('toast component follows design guide styling', function () {
    $view = Blade::render('<x-toast />');
    
    expect($view)->toContain('bg-white')
        ->toContain('border-gray-200')
        ->toContain('rounded-lg')
        ->toContain('shadow-lg')
        ->toContain('p-4');
});

test('toast component has success icon', function () {
    $view = Blade::render('<x-toast />');
    
    expect($view)->toContain('text-green-500')
        ->toContain('svg');
});

test('toast component displays message with x-text', function () {
    $view = Blade::render('<x-toast />');
    
    expect($view)->toContain('x-text="message"')
        ->toContain('text-gray-900');
});

test('toast component has x-cloak attribute', function () {
    $view = Blade::render('<x-toast />');
    
    expect($view)->toContain('x-cloak');
});

test('toast component has smooth slide animations', function () {
    $view = Blade::render('<x-toast />');
    
    expect($view)->toContain('translate-x-4')
        ->toContain('translate-x-0')
        ->toContain('opacity-0')
        ->toContain('opacity-100');
});

test('toast component can be manually closed', function () {
    $view = Blade::render('<x-toast />');
    
    expect($view)->toContain('clearTimeout')
        ->toContain('Close');
});

test('toast component has proper accessibility', function () {
    $view = Blade::render('<x-toast />');
    
    expect($view)->toContain('sr-only')
        ->toContain('Close');
});

// Test suite for toast notification component - verifies Alpine.js integration and design
