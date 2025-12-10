<?php

// File: tests/Feature/CardComponentTest.php

use Illuminate\Support\Facades\Blade;

test('it renders basic card with content', function () {
    $view = Blade::render('<x-card>Test content</x-card>');
    
    expect($view)->toContain('Test content')
        ->toContain('bg-white')
        ->toContain('border-gray-200')
        ->toContain('rounded-lg')
        ->toContain('shadow-sm');
});

test('it renders card with default padding', function () {
    $view = Blade::render('<x-card>Content</x-card>');
    
    expect($view)->toContain('p-6');
});

test('it renders card without padding when disabled', function () {
    $view = Blade::render('<x-card :padding="false">Content</x-card>');
    
    expect($view)->not->toContain('p-6');
});

test('it renders card with header slot', function () {
    $view = Blade::render('
        <x-card>
            <x-slot name="header">
                <h3>Card Header</h3>
            </x-slot>
            Card content
        </x-card>
    ');
    
    expect($view)->toContain('Card Header')
        ->toContain('Card content')
        ->toContain('border-b')
        ->toContain('mb-4')
        ->toContain('pb-4');
});

test('it renders card with footer slot', function () {
    $view = Blade::render('
        <x-card>
            Card content
            <x-slot name="footer">
                <button>Action</button>
            </x-slot>
        </x-card>
    ');
    
    expect($view)->toContain('Card content')
        ->toContain('Action')
        ->toContain('border-t')
        ->toContain('mt-4')
        ->toContain('pt-4');
});

test('it renders card with both header and footer', function () {
    $view = Blade::render('
        <x-card>
            <x-slot name="header">
                <h3>Header</h3>
            </x-slot>
            Content here
            <x-slot name="footer">
                <button>Footer Button</button>
            </x-slot>
        </x-card>
    ');
    
    expect($view)->toContain('Header')
        ->toContain('Content here')
        ->toContain('Footer Button');
});

test('it merges custom classes with default classes', function () {
    $view = Blade::render('<x-card class="custom-class">Content</x-card>');
    
    expect($view)->toContain('custom-class')
        ->toContain('bg-white')
        ->toContain('border-gray-200');
});

test('it accepts custom attributes', function () {
    $view = Blade::render('<x-card id="my-card" data-test="value">Content</x-card>');
    
    expect($view)->toContain('id="my-card"')
        ->toContain('data-test="value"');
});

test('it renders stat card layout', function () {
    $view = Blade::render('
        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Label</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">24</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full">
                    Icon
                </div>
            </div>
        </x-card>
    ');
    
    expect($view)->toContain('Label')
        ->toContain('24')
        ->toContain('Icon');
});

test('it works without header and footer', function () {
    $view = Blade::render('<x-card>Simple content</x-card>');
    
    expect($view)->toContain('Simple content');
    // When no header/footer, those sections shouldn't be rendered
});

// Test suite for card component - verifies rendering with various slot configurations
