# Design Document - ReadySoft Booking Portal

## Oversikt

ReadySoft er en multi-tenant bookingportal hvor hver kunde (tenant) får sin egen underside for å motta bookinger. Systemet skal være intuitivt, raskt og sikkert med full tenant-isolasjon.

---

## Arkitektur

### System-arkitektur

```
┌─────────────────────────────────────────────────────────────┐
│                      Public Web Layer                        │
├─────────────────────────────────────────────────────────────┤
│  Landing Page (/)  │  Tenant Booking Page (/{slug})         │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                   Authentication Layer                       │
├─────────────────────────────────────────────────────────────┤
│  Laravel Breeze  │  Session Management  │  CSRF Protection  │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    Authorization Layer                       │
├─────────────────────────────────────────────────────────────┤
│  Middleware: CheckActiveSubscription                         │
│  Middleware: CheckTenantOwnership                            │
│  Policies: TenantPolicy, ResourcePolicy                      │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                     Application Layer                        │
├─────────────────────────────────────────────────────────────┤
│  Controllers  │  Form Requests  │  Services                 │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                       Domain Layer                           │
├─────────────────────────────────────────────────────────────┤
│  Models: Tenant, Subscription, Resource, Booking            │
│  Business Logic  │  Relationships                           │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                      Data Layer (MySQL)                      │
└─────────────────────────────────────────────────────────────┘
```

---

## Database Design

### ER-Diagram

```
┌─────────────┐         ┌──────────────┐         ┌─────────────┐
│    users    │────────▶│   tenants    │◀────────│    plans    │
└─────────────┘         └──────────────┘         └─────────────┘
                               │                         │
                               │                         │
                               ▼                         ▼
                        ┌──────────────┐         ┌─────────────┐
                        │ subscriptions│─────────│             │
                        └──────────────┘         └─────────────┘
                               │
                               │
                               ▼
                        ┌──────────────┐
                        │  resources   │
                        └──────────────┘
                               │
                               │
                               ▼
                        ┌──────────────┐
                        │   bookings   │
                        └──────────────┘
```

### Tabeller

#### users
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
name                VARCHAR(255)
email               VARCHAR(255) UNIQUE
email_verified_at   TIMESTAMP NULL
password            VARCHAR(255)
role                ENUM('admin', 'tenant_admin') DEFAULT 'tenant_admin'
tenant_id           BIGINT UNSIGNED NULL (FK -> tenants.id)
remember_token      VARCHAR(100) NULL
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

#### tenants
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
name                VARCHAR(255)
slug                VARCHAR(255) UNIQUE
business_type       VARCHAR(100)
description         TEXT NULL
active              BOOLEAN DEFAULT true
created_at          TIMESTAMP
updated_at          TIMESTAMP

INDEX idx_slug (slug)
INDEX idx_active (active)
```

#### plans
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
name                VARCHAR(255)
description         TEXT NULL
features            JSON NULL
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

#### subscriptions
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
tenant_id           BIGINT UNSIGNED (FK -> tenants.id)
plan_id             BIGINT UNSIGNED (FK -> plans.id)
active              BOOLEAN DEFAULT true
active_from         TIMESTAMP NULL
active_to           TIMESTAMP NULL
created_at          TIMESTAMP
updated_at          TIMESTAMP

INDEX idx_tenant_active (tenant_id, active)
```

#### resources
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
tenant_id           BIGINT UNSIGNED (FK -> tenants.id)
name                VARCHAR(255)
description         TEXT NULL
type                VARCHAR(100)
capacity            INTEGER DEFAULT 1
active              BOOLEAN DEFAULT true
created_at          TIMESTAMP
updated_at          TIMESTAMP

INDEX idx_tenant_active (tenant_id, active)
```

#### resource_availabilities
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
resource_id         BIGINT UNSIGNED (FK -> resources.id)
day_of_week         TINYINT (0=Sunday, 6=Saturday)
start_time          TIME
end_time            TIME
created_at          TIMESTAMP
updated_at          TIMESTAMP

INDEX idx_resource_day (resource_id, day_of_week)
```

#### bookings
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
resource_id         BIGINT UNSIGNED (FK -> resources.id)
customer_name       VARCHAR(255)
customer_email      VARCHAR(255)
customer_phone      VARCHAR(50)
booking_date        DATE
start_time          TIME
end_time            TIME
notes               TEXT NULL
status              ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'confirmed'
created_at          TIMESTAMP
updated_at          TIMESTAMP

INDEX idx_resource_date (resource_id, booking_date)
INDEX idx_status (status)
```

#### sms_settings
```sql
id                  BIGINT UNSIGNED PRIMARY KEY
tenant_id           BIGINT UNSIGNED (FK -> tenants.id)
api_key             TEXT (encrypted)
enabled             BOOLEAN DEFAULT false
created_at          TIMESTAMP
updated_at          TIMESTAMP

UNIQUE idx_tenant (tenant_id)
```

---

## Multi-tenancy Strategi

### Tenant Isolation

**Strategi:** Database-level isolation med tenant_id foreign key

**Implementering:**
1. Alle tenant-spesifikke tabeller har `tenant_id` kolonne
2. Global scope på Eloquent modeller filtrerer automatisk på tenant_id
3. Middleware verifiserer tenant-tilgang før hver request
4. Policies sjekker tenant-eierskap

**Eksempel - Global Scope:**
```php
// app/Models/Resource.php
protected static function booted()
{
    static::addGlobalScope('tenant', function (Builder $query) {
        if (auth()->check() && auth()->user()->tenant_id) {
            $query->where('tenant_id', auth()->user()->tenant_id);
        }
    });
}
```

---

## Design System

### Fargepalett

```css
/* Primary Colors */
--primary-50:  #eff6ff;
--primary-100: #dbeafe;
--primary-500: #3b82f6;
--primary-600: #2563eb;  /* Main brand color */
--primary-700: #1d4ed8;

/* Success */
--success-500: #10b981;
--success-100: #d1fae5;

/* Warning */
--warning-500: #f59e0b;
--warning-100: #fef3c7;

/* Error */
--error-500: #ef4444;
--error-100: #fee2e2;

/* Neutral */
--gray-50:  #f9fafb;
--gray-100: #f3f4f6;
--gray-200: #e5e7eb;
--gray-300: #d1d5db;
--gray-600: #4b5563;
--gray-700: #374151;
--gray-900: #111827;
```

### Typography

```css
/* Font Family */
font-family: ui-sans-serif, system-ui, -apple-system, sans-serif;

/* Sizes */
text-xs:   0.75rem   (12px)
text-sm:   0.875rem  (14px)
text-base: 1rem      (16px)
text-lg:   1.125rem  (18px)
text-xl:   1.25rem   (20px)
text-2xl:  1.5rem    (24px)
text-3xl:  1.875rem  (30px)

/* Weights */
font-normal:    400
font-medium:    500
font-semibold:  600
font-bold:      700
```

### Spacing Scale

```
1:  0.25rem  (4px)
2:  0.5rem   (8px)
3:  0.75rem  (12px)
4:  1rem     (16px)   ← Standard
6:  1.5rem   (24px)
8:  2rem     (32px)
12: 3rem     (48px)
```

### Component Library

#### Buttons

**Primary Button**
```html
<button class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 
               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
               transition-colors font-medium">
    Button Text
</button>
```

**Secondary Button**
```html
<button class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg 
               hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 
               focus:ring-offset-2 transition-colors font-medium">
    Button Text
</button>
```

**Danger Button**
```html
<button class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 
               focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2
               transition-colors font-medium">
    Delete
</button>
```

#### Cards

**Basic Card**
```html
<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
    <!-- Content -->
</div>
```

**Stat Card**
```html
<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600">Label</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">24</p>
        </div>
        <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full">
            <!-- Icon -->
        </div>
    </div>
</div>
```

#### Forms

**Input Field**
```html
<div>
    <label class="block mb-1 text-sm font-medium text-gray-700">
        Label
    </label>
    <input type="text" 
           class="w-full px-3 py-2 border border-gray-300 rounded-lg 
                  focus:outline-none focus:ring-2 focus:ring-blue-500 
                  focus:border-transparent"
           placeholder="Placeholder">
    <p class="mt-1 text-sm text-gray-500">Helper text</p>
</div>
```

**Input with Error**
```html
<div>
    <label class="block mb-1 text-sm font-medium text-gray-700">Label</label>
    <input type="text" 
           class="w-full px-3 py-2 border-2 border-red-300 rounded-lg 
                  focus:outline-none focus:ring-2 focus:ring-red-500">
    <p class="flex items-center gap-1 mt-1 text-sm text-red-600">
        <svg class="w-4 h-4">!</svg>
        Error message
    </p>
</div>
```

#### Badges

```html
<!-- Active -->
<span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
    Active
</span>

<!-- Inactive -->
<span class="px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
    Inactive
</span>
```

#### Alerts

**Success Alert**
```html
<div class="p-4 border-l-4 border-green-500 rounded bg-green-50">
    <div class="flex items-start gap-3">
        <svg class="flex-shrink-0 w-5 h-5 text-green-500">✓</svg>
        <div>
            <p class="text-sm font-medium text-green-800">Success!</p>
            <p class="mt-1 text-sm text-green-700">Message here</p>
        </div>
    </div>
</div>
```

**Error Alert**
```html
<div class="p-4 border-l-4 border-red-500 rounded bg-red-50">
    <div class="flex items-start gap-3">
        <svg class="flex-shrink-0 w-5 h-5 text-red-500">!</svg>
        <div>
            <p class="text-sm font-medium text-red-800">Error</p>
            <p class="mt-1 text-sm text-red-700">Message here</p>
        </div>
    </div>
</div>
```

---

## Alpine.js Components

### Modal
```html
<div x-data="{ open: false }">
    <button @click="open = true">Open Modal</button>
    
    <div x-show="open" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="open = false" 
             class="fixed inset-0 bg-black bg-opacity-50"></div>
        
        <div class="relative z-10 w-full max-w-md p-6 bg-white rounded-lg shadow-xl">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Title</h3>
            <p class="mb-6 text-gray-600">Content</p>
            <div class="flex justify-end gap-3">
                <button @click="open = false">Cancel</button>
                <button>Confirm</button>
            </div>
        </div>
    </div>
</div>
```

### Dropdown
```html
<div x-data="{ open: false }" class="relative">
    <button @click="open = !open">Menu</button>
    
    <div x-show="open" 
         @click.outside="open = false"
         x-cloak
         class="absolute right-0 w-48 py-1 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg">
        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            Item 1
        </a>
    </div>
</div>
```

### Toast Notification
```html
<div x-data="{ show: false, message: '' }" 
     @notify.window="show = true; message = $event.detail; setTimeout(() => show = false, 4000)">
    <div x-show="show" 
         x-transition
         class="fixed top-4 right-4 z-50 p-4 bg-white border border-gray-200 rounded-lg shadow-lg">
        <p x-text="message"></p>
    </div>
</div>
```

---

## Routing Structure

```
Public Routes:
GET  /                          → Landing page (all tenants)
GET  /{slug}                    → Tenant booking page
POST /{slug}/bookings           → Create booking

Auth Routes (Guest):
GET  /login                     → Login form
POST /login                     → Process login
GET  /register                  → Registration form
POST /register                  → Process registration
POST /logout                    → Logout

Protected Routes (Tenant Admin):
GET  /dashboard                 → Tenant dashboard
GET  /dashboard/resources       → List resources
GET  /dashboard/resources/create → Create resource form
POST /dashboard/resources       → Store resource
GET  /dashboard/resources/{id}/edit → Edit resource form
PUT  /dashboard/resources/{id}  → Update resource
DELETE /dashboard/resources/{id} → Delete resource
GET  /dashboard/bookings        → List bookings
GET  /dashboard/sms             → SMS settings
POST /dashboard/sms/test        → Test SMS

Protected Routes (System Admin):
GET  /admin                     → Admin dashboard
GET  /admin/tenants             → List all tenants
POST /admin/tenants/{id}/toggle → Toggle tenant active status
```

---

## Security Considerations

### Authentication
- Laravel Breeze handles login/registration
- Session-based authentication
- CSRF protection on all forms
- Password hashing with bcrypt

### Authorization
- Middleware: `CheckActiveSubscription`
- Middleware: `CheckTenantOwnership`
- Policies for resource access
- Global scopes for tenant isolation

### Data Protection
- API keys encrypted in database
- Sensitive data never in URLs
- Input validation on all forms
- SQL injection protection via Eloquent

### Tenant Isolation
- All queries filtered by tenant_id
- No cross-tenant data access
- Slug-based routing validated
- Admin role separated from tenant_admin

---

## Performance Optimization

### Database
- Indexes on frequently queried columns (slug, tenant_id, booking_date)
- Eager loading to prevent N+1 queries
- Query result caching where appropriate

### Frontend
- Vite for asset bundling
- Tailwind CSS purging in production
- Lazy loading of images
- Minimal JavaScript (Alpine.js only)

### Caching Strategy
- Cache landing page tenant list (5 min)
- Cache tenant lookup by slug (10 min)
- Session storage for user data

---

## Error Handling

### User-Facing Errors
- Custom 404 page (tenant not found)
- Custom 403 page (no access)
- Custom 500 page (server error)
- Inline form validation errors
- Toast notifications for actions

### Logging
- All errors logged to `storage/logs/laravel.log`
- SMS API failures logged separately
- Booking conflicts logged for debugging

---

## Testing Strategy

### Manual Testing
- Test all user flows end-to-end
- Test on mobile devices
- Test with different tenant data
- Test edge cases (double booking, invalid slug, etc.)

### Automated Testing (Post-MVP)
- Feature tests for critical paths
- Unit tests for business logic
- API tests for SMS integration

---

## Deployment Considerations

### Environment Variables
```
APP_NAME=ReadySoft
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
TELETOPIA_API_URL=https://api.teletopia.no
```

### Production Checklist
- [ ] Set APP_ENV=production
- [ ] Set APP_DEBUG=false
- [ ] Run `npm run build`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Set up SSL certificate
- [ ] Configure backup strategy
- [ ] Set up monitoring

---

**Version:** 1.0  
**Last Updated:** December 2025  
**Status:** Ready for Implementation
