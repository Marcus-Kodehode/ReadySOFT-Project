# Button Loading States Implementation Guide

## Overview
This guide explains how to implement loading states for submit buttons in the ReadySoft Booking Portal. Loading states provide visual feedback to users during form submission, preventing double-clicks and improving user experience.

## Standard Pattern

### Basic Implementation
```html
<form x-data="{ loading: false }" @submit="loading = true">
    <button type="submit" 
            :disabled="loading"
            class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 
                   transition-colors font-medium 
                   disabled:opacity-50 disabled:cursor-not-allowed">
        <span x-show="!loading">Submit</span>
        <span x-show="loading" class="flex items-center gap-2">
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Loading...
        </span>
    </button>
</form>
```

## Component Breakdown

### 1. Alpine.js Data
```html
x-data="{ loading: false }"
```
- Initializes the loading state as false
- Place on the `<form>` element or parent container

### 2. Submit Event Handler
```html
@submit="loading = true"
```
- Sets loading to true when form is submitted
- Triggers the visual state change

### 3. Disabled Binding
```html
:disabled="loading"
```
- Disables the button when loading is true
- Prevents double-clicks and multiple submissions

### 4. CSS Classes
```html
class="... disabled:opacity-50 disabled:cursor-not-allowed"
```
- `disabled:opacity-50` - Reduces button opacity when disabled
- `disabled:cursor-not-allowed` - Changes cursor to indicate disabled state

### 5. Content Toggle
```html
<span x-show="!loading">Submit</span>
<span x-show="loading" class="flex items-center gap-2">
    <!-- Spinner SVG -->
    Loading...
</span>
```
- Shows normal text when not loading
- Shows spinner and loading text when loading

## Spinner SVG

### Standard Spinner
```html
<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
```

### Spinner Sizes
- Small: `w-3 h-3` (12px)
- Medium: `w-4 h-4` (16px) - **Default**
- Large: `w-5 h-5` (20px)

## Button Variants

### Primary Button (Blue)
```html
<button type="submit" 
        :disabled="loading"
        class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 
               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 
               transition-colors font-medium 
               disabled:opacity-50 disabled:cursor-not-allowed">
    <span x-show="!loading">Save</span>
    <span x-show="loading" class="flex items-center gap-2">
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Saving...
    </span>
</button>
```

### Success Button (Green)
```html
<button type="submit" 
        :disabled="loading"
        class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 
               focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 
               transition-colors font-medium 
               disabled:opacity-50 disabled:cursor-not-allowed">
    <span x-show="!loading">Confirm</span>
    <span x-show="loading" class="flex items-center gap-2">
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Confirming...
    </span>
</button>
```

### Danger Button (Red)
```html
<button type="submit" 
        :disabled="loading"
        class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 
               focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 
               transition-colors font-medium 
               disabled:opacity-50 disabled:cursor-not-allowed">
    <span x-show="!loading">Delete</span>
    <span x-show="loading" class="flex items-center gap-2">
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Deleting...
    </span>
</button>
```

## Advanced Patterns

### With Confirmation Dialog
```html
<form x-data="{ loading: false }" 
      @submit="if (!confirm('Are you sure?')) { $event.preventDefault(); return false; } loading = true">
    <button type="submit" :disabled="loading" class="...">
        <span x-show="!loading">Delete</span>
        <span x-show="loading" class="flex items-center gap-2">
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Deleting...
        </span>
    </button>
</form>
```

### Multiple Buttons in Same Form
```html
<div x-data="{ loading: false }">
    <form @submit="loading = true">
        <!-- Form fields -->
        
        <div class="flex gap-3">
            <button type="submit" :disabled="loading" class="...">
                <span x-show="!loading">Save</span>
                <span x-show="loading" class="flex items-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
            </button>
            
            <a href="/cancel" class="...">Cancel</a>
        </div>
    </form>
</div>
```

### AJAX Form Submission
```html
<form x-data="{ loading: false }" 
      @submit.prevent="
          loading = true;
          fetch('/api/endpoint', {
              method: 'POST',
              body: new FormData($event.target)
          })
          .then(response => response.json())
          .then(data => {
              loading = false;
              // Handle success
          })
          .catch(error => {
              loading = false;
              // Handle error
          });
      ">
    <button type="submit" :disabled="loading" class="...">
        <span x-show="!loading">Submit</span>
        <span x-show="loading" class="flex items-center gap-2">
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Submitting...
        </span>
    </button>
</form>
```

## Loading Text Conventions

Use action-specific loading text:
- **Create**: "Creating..."
- **Update**: "Updating..."
- **Save**: "Saving..."
- **Delete**: "Deleting..."
- **Send**: "Sending..."
- **Search**: "Searching..."
- **Confirm**: "Confirming..."
- **Cancel**: "Cancelling..."
- **Sign Out**: "Signing Out..."

## Best Practices

### DO ✅
- Always disable the button during loading
- Use descriptive loading text (not just "Loading...")
- Include the spinner SVG for visual feedback
- Add `disabled:opacity-50` and `disabled:cursor-not-allowed` classes
- Place `x-data` on the form or parent container
- Use `@submit` event to trigger loading state

### DON'T ❌
- Don't forget to disable the button
- Don't use generic "Loading..." for all actions
- Don't place `x-data` on the button itself
- Don't forget the spinner animation
- Don't use inline `onsubmit` handlers (use Alpine.js)
- Don't forget to handle form validation errors

## Accessibility

### Screen Reader Support
```html
<button type="submit" 
        :disabled="loading"
        :aria-busy="loading"
        class="...">
    <span x-show="!loading">Submit</span>
    <span x-show="loading" class="flex items-center gap-2">
        <svg class="w-4 h-4 animate-spin" aria-hidden="true" fill="none" viewBox="0 0 24 24">
            <!-- SVG paths -->
        </svg>
        <span class="sr-only">Processing...</span>
        Submitting...
    </span>
</button>
```

## Testing Checklist

- [ ] Button shows spinner when clicked
- [ ] Button text changes to loading text
- [ ] Button becomes disabled during loading
- [ ] Button opacity reduces to 50%
- [ ] Cursor changes to not-allowed
- [ ] Form still submits correctly
- [ ] Works with form validation
- [ ] Works with confirmation dialogs
- [ ] Spinner animates smoothly
- [ ] Works in all major browsers

## Examples in Codebase

### Resource Forms
- `resources/views/resources/create.blade.php` - Create Resource button
- `resources/views/resources/edit.blade.php` - Update Resource button

### Booking Forms
- `resources/views/bookings/show.blade.php` - Confirm/Cancel buttons

### Settings Forms
- `resources/views/sms/index.blade.php` - Save Settings button

### Admin Forms
- `resources/views/admin/tenants.blade.php` - Search button

### Auth Forms
- `resources/views/subscription/inactive.blade.php` - Sign Out button

## Related Documentation
- Alpine.js Guide: `docs/guides/ALPINE_GUIDE.md`
- Design Guide: `docs/designs/DESIGN_GUIDE_1.md`
- Task Summary: `docs/summaries/TASK_16_SUMMARY.md`
