<?php

// File: tests/Feature/ModalComponentTest.php

use Illuminate\Support\Facades\Blade;

test('modal component renders with title prop', function () {
    $view = Blade::render('<x-modal title="Test Modal">Modal content here</x-modal>');

    expect($view)->toContain('Test Modal')
        ->toContain('Modal content here');
});

test('modal component renders without title', function () {
    $view = Blade::render('<x-modal>Modal content without title</x-modal>');

    expect($view)->toContain('Modal content without title')
        ->not->toContain('<h3'); // No header should be rendered
});

test('modal component has Alpine.js data attribute', function () {
    $view = Blade::render('<x-modal title="Test">Content</x-modal>');

    expect($view)->toContain('x-data')
        ->toContain('open: false');
});

test('modal component has backdrop with click handler', function () {
    $view = Blade::render('<x-modal title="Test">Content</x-modal>');

    expect($view)->toContain('@click="open = false"')
        ->toContain('bg-black bg-opacity-50');
});

test('modal component has escape key handler', function () {
    $view = Blade::render('<x-modal title="Test">Content</x-modal>');

    expect($view)->toContain('@keydown.escape.window="open = false"');
});

test('modal component supports different max widths', function () {
    $widths = ['sm', 'md', 'lg', 'xl', '2xl'];
    
    foreach ($widths as $width) {
        $view = Blade::render("<x-modal title=\"Test\" maxWidth=\"{$width}\">Content</x-modal>");
        
        expect($view)->toContain("max-w-{$width}");
    }
});

test('modal component defaults to md width', function () {
    $view = Blade::render('<x-modal title="Test">Content</x-modal>');

    expect($view)->toContain('max-w-md');
});

test('modal component renders trigger slot', function () {
    $view = Blade::render('
        <x-modal title="Test Modal">
            <x-slot:trigger>
                <button>Open Modal</button>
            </x-slot:trigger>
            Modal content
        </x-modal>
    ');

    expect($view)->toContain('Open Modal')
        ->toContain('Modal content');
});

test('modal component renders footer slot', function () {
    $view = Blade::render('
        <x-modal title="Test Modal">
            Modal content
            <x-slot:footer>
                <button>Cancel</button>
                <button>Confirm</button>
            </x-slot:footer>
        </x-modal>
    ');

    expect($view)->toContain('Cancel')
        ->toContain('Confirm');
});

test('modal component has proper z-index for overlay', function () {
    $view = Blade::render('<x-modal title="Test">Content</x-modal>');

    expect($view)->toContain('z-50')
        ->toContain('z-10');
});

test('modal component has transition classes', function () {
    $view = Blade::render('<x-modal title="Test">Content</x-modal>');

    expect($view)->toContain('x-transition:enter')
        ->toContain('x-transition:leave')
        ->toContain('ease-out duration-300')
        ->toContain('ease-in duration-200');
});

test('modal component has x-cloak attribute', function () {
    $view = Blade::render('<x-modal title="Test">Content</x-modal>');

    expect($view)->toContain('x-cloak');
});

test('modal component renders with custom attributes', function () {
    $view = Blade::render('<x-modal title="Test" id="custom-modal" class="custom-class">Content</x-modal>');

    expect($view)->toContain('id="custom-modal"')
        ->toContain('custom-class');
});

test('modal component has proper styling classes', function () {
    $view = Blade::render('<x-modal title="Test">Content</x-modal>');

    // Check for design system classes
    expect($view)->toContain('bg-white')
        ->toContain('rounded-lg')
        ->toContain('shadow-xl')
        ->toContain('p-6')
        ->toContain('text-gray-900') // Title color
        ->toContain('text-gray-600'); // Content color
});

// Component test for modal functionality
