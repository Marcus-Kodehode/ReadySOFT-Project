# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Schedulo** is a multi-tenant booking and resource management SaaS platform built with Laravel 12. Businesses (tenants) register, subscribe to a plan, and get a public booking page at `/{slug}` where customers can book their resources.

## Common Commands

### Development
```bash
# Start dev server with hot reload (runs server, queue, and Vite concurrently)
composer dev

# Or start each separately:
php artisan serve
npm run dev
```

### Setup
```bash
# Full automated setup
composer setup

# Manual setup
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate
php artisan db:seed --class=PlanSeeder
npm run build
```

### Testing
```bash
# Run all tests
composer test
# or
php artisan test

# Run a single test file
php artisan test tests/Feature/BookingControllerTest.php

# Run a specific test method
php artisan test --filter="test_method_name"
```

### Code Quality
```bash
# Format code (PSR-12 via Laravel Pint)
./vendor/bin/pint
```

### Database
```bash
# Reset with fresh migrations and seed plans
php artisan migrate:fresh --seed

# Clear caches
php artisan cache:clear && php artisan config:clear && php artisan view:clear
```

## Architecture

### Multi-Tenancy Model
This is a **single-database multi-tenant** architecture — not package-based (no `stancl/tenancy`). Isolation is enforced manually via:
- `tenant_id` foreign keys on `resources`, `bookings`, `subscriptions`, `sms_settings`
- `User` model has `tenant_id` and `role` fields (`admin` or `tenant_admin`)
- Controllers scope queries to `auth()->user()->tenant` to prevent cross-tenant access

### Middleware Stack
- `CheckActiveSubscription` — redirects tenant users to `/subscription/inactive` if no active subscription
- `CheckAdminRole` — restricts `/admin/*` routes to users with `role = 'admin'`
- Applied as `subscription` and `admin` aliases in route groups

### Route Structure
Routes in [routes/web.php](routes/web.php) are organized into groups:
1. **Public** — landing page, slug check API, available slots API
2. **Auth** — Laravel Breeze routes (`routes/auth.php`)
3. **Tenant** — `auth + verified + subscription` middleware; dashboard, resources, bookings, SMS settings, profile
4. **Subscription management** — `auth` only; inactive subscription page
5. **Admin** — `auth + admin` middleware; `/admin/*` routes for system administration
6. **Public booking** — `/{slug}` wildcard routes placed **last** to avoid catching other routes

### Key Models and Relationships
- `Tenant` — has many `User`, `Resource`, `Subscription`; has one `SmsSettings`
- `User` — belongs to `Tenant`; `role` field distinguishes system admins from tenant admins
- `Resource` — belongs to `Tenant`; has many `ResourceAvailability` (per-day opening hours) and `Booking`
- `Subscription` — joins `Tenant` to `Plan`; has `active`, `active_from`, `active_to`
- `Booking` — belongs to `Resource`; has `status` (`pending`, `confirmed`, `cancelled`)

### Services
- `AvailabilityService` — calculates available 30-minute slots for a resource on a given date, respecting opening hours (`ResourceAvailability`) and existing `pending`/`confirmed` bookings; supports capacity > 1
- `SlugService` — generates and validates unique tenant slugs
- `TeletopiaSmsService` — sends SMS via Teletopia HTTP JSON API (configured via `TELETOPIA_USERNAME`/`TELETOPIA_PASSWORD` env vars)

### Frontend
- Blade templates with Alpine.js for interactivity
- Tailwind CSS v4 via `@tailwindcss/vite` plugin
- Vite as the asset bundler ([vite.config.js](vite.config.js))
- Reusable Blade components in `resources/views/components/`

### Testing Setup
- Tests use **SQLite in-memory** database (configured in `phpunit.xml`)
- Testing framework: **Pest** with `pestphp/pest-plugin-laravel`
- Tests are in `tests/Feature/` (no Unit tests currently active)
- Seeds not run by default in tests — use factories or create models directly

### Environment Notes
- Database: MySQL locally (`readysoft_project`), SQLite in-memory for tests
- SMS integration requires `TELETOPIA_USERNAME`, `TELETOPIA_PASSWORD`, `TELETOPIA_API_URL`
- Plans must be seeded with `php artisan db:seed --class=PlanSeeder` before registration works
