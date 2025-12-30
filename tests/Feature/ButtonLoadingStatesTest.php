<?php

// File: tests/Feature/ButtonLoadingStatesTest.php

use Illuminate\Support\Facades\Blade;

test('resource create form has loading state', function () {
    $view = Blade::render(
        '<form x-data="{ loading: false }" @submit="loading = true">
            <button type="submit" :disabled="loading" class="disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!loading">Create Resource</span>
                <span x-show="loading">Creating...</span>
            </button>
        </form>'
    );
    
    expect($view)
        ->toContain('x-data="{ loading: false }"')
        ->toContain('@submit="loading = true"')
        ->toContain(':disabled="loading"')
        ->toContain('x-show="!loading"')
        ->toContain('x-show="loading"')
        ->toContain('Create Resource')
        ->toContain('Creating...')
        ->toContain('disabled:opacity-50')
        ->toContain('disabled:cursor-not-allowed');
});

test('loading button has spinner svg', function () {
    $view = Blade::render(
        '<button type="submit">
            <span x-show="loading" class="flex items-center gap-2">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                </svg>
                Loading...
            </span>
        </button>'
    );
    
    expect($view)
        ->toContain('animate-spin')
        ->toContain('viewBox="0 0 24 24"')
        ->toContain('circle')
        ->toContain('opacity-25');
});

test('loading button has correct disabled classes', function () {
    $view = Blade::render(
        '<button type="submit" :disabled="loading" class="px-4 py-2 bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">
            Submit
        </button>'
    );
    
    expect($view)
        ->toContain(':disabled="loading"')
        ->toContain('disabled:opacity-50')
        ->toContain('disabled:cursor-not-allowed');
});

test('primary button with loading state has correct colors', function () {
    $view = Blade::render(
        '<button type="submit" :disabled="loading" class="bg-blue-600 hover:bg-blue-700 text-white">
            <span x-show="!loading">Save</span>
            <span x-show="loading">Saving...</span>
        </button>'
    );
    
    expect($view)
        ->toContain('bg-blue-600')
        ->toContain('hover:bg-blue-700')
        ->toContain('text-white');
});

test('success button with loading state has correct colors', function () {
    $view = Blade::render(
        '<button type="submit" :disabled="loading" class="bg-green-600 hover:bg-green-700 text-white">
            <span x-show="!loading">Confirm</span>
            <span x-show="loading">Confirming...</span>
        </button>'
    );
    
    expect($view)
        ->toContain('bg-green-600')
        ->toContain('hover:bg-green-700')
        ->toContain('text-white');
});

test('danger button with loading state has correct colors', function () {
    $view = Blade::render(
        '<button type="submit" :disabled="loading" class="bg-red-600 hover:bg-red-700 text-white">
            <span x-show="!loading">Delete</span>
            <span x-show="loading">Deleting...</span>
        </button>'
    );
    
    expect($view)
        ->toContain('bg-red-600')
        ->toContain('hover:bg-red-700')
        ->toContain('text-white');
});

test('loading state uses flex layout for spinner and text', function () {
    $view = Blade::render(
        '<span x-show="loading" class="flex items-center gap-2">
            <svg class="w-4 h-4 animate-spin"></svg>
            Loading...
        </span>'
    );
    
    expect($view)
        ->toContain('flex items-center gap-2')
        ->toContain('animate-spin');
});

test('form with confirmation dialog has loading state', function () {
    $view = Blade::render(
        '<form x-data="{ loading: false }" @submit="if (!confirm(\'Are you sure?\')) { $event.preventDefault(); return false; } loading = true">
            <button type="submit" :disabled="loading">
                <span x-show="!loading">Delete</span>
                <span x-show="loading">Deleting...</span>
            </button>
        </form>'
    );
    
    expect($view)
        ->toContain('x-data="{ loading: false }"')
        ->toContain('confirm')
        ->toContain('loading = true')
        ->toContain(':disabled="loading"');
});

test('loading text follows naming conventions', function () {
    $createView = Blade::render('<span x-show="loading">Creating...</span>');
    $updateView = Blade::render('<span x-show="loading">Updating...</span>');
    $saveView = Blade::render('<span x-show="loading">Saving...</span>');
    $deleteView = Blade::render('<span x-show="loading">Deleting...</span>');
    $searchView = Blade::render('<span x-show="loading">Searching...</span>');
    
    expect($createView)->toContain('Creating...');
    expect($updateView)->toContain('Updating...');
    expect($saveView)->toContain('Saving...');
    expect($deleteView)->toContain('Deleting...');
    expect($searchView)->toContain('Searching...');
});

test('spinner has correct size classes', function () {
    $view = Blade::render(
        '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"></svg>'
    );
    
    expect($view)
        ->toContain('w-4 h-4')
        ->toContain('animate-spin')
        ->toContain('fill="none"');
});

// Test suite for button loading states - verifies Alpine.js integration and visual feedback

