# ReadySoft Project - Complete File Structure

## Project Overview
This is a Laravel 12 application with Breeze authentication scaffolding, Tailwind CSS, and Vite as the build tool. The project follows Laravel's standard structure with organized routing, controllers, models, views, and comprehensive tests.

**Tech Stack:**
- **Backend:** Laravel 12
- **Frontend:** Blade templates with Alpine.js
- **Styling:** Tailwind CSS
- **Build Tool:** Vite
- **Authentication:** Laravel Breeze
- **Testing:** Pest PHP
- **Database:** MySQL

---

## Root Level Files

```
readysoft-project/
├── artisan                      # Laravel CLI entry point
├── composer.json                # PHP dependencies configuration
├── composer.lock                # Locked PHP dependencies
├── package.json                 # Node.js dependencies configuration
├── package-lock.json            # Locked Node.js dependencies
├── phpunit.xml                  # PHPUnit/Pest testing configuration
├── postcss.config.js            # PostCSS configuration (Tailwind)
├── tailwind.config.js           # Tailwind CSS configuration
├── vite.config.js               # Vite build configuration
├── .editorconfig                # Editor configuration (indentation, encoding)
├── .env                         # Environment variables (local)
├── .env.example                 # Environment variables template
├── .gitattributes               # Git attributes
├── .gitignore                   # Git ignore rules
├── .nvmrc                       # Node.js version specification
├── README.md                    # Project documentation
```

---

## Directory Structure

### `/app` - Application Core Code

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── AuthenticatedSessionController.php      # Handle login
│   │   │   ├── ConfirmablePasswordController.php       # Password confirmation
│   │   │   ├── EmailVerificationNotificationController.php
│   │   │   ├── EmailVerificationPromptController.php
│   │   │   ├── NewPasswordController.php               # Password reset
│   │   │   ├── PasswordController.php                  # Change password
│   │   │   ├── PasswordResetLinkController.php         # Send reset link
│   │   │   ├── RegisteredUserController.php            # User registration
│   │   │   └── VerifyEmailController.php               # Verify email
│   │   ├── Controller.php                              # Base controller
│   │   └── ProfileController.php                       # User profile management
│   └── Requests/
│       ├── Auth/
│       │   └── LoginRequest.php                        # Login validation
│       └── ProfileUpdateRequest.php                    # Profile update validation
├── Models/
│   └── User.php                                        # User model
├── Providers/
│   └── AppServiceProvider.php                          # Service provider
└── View/
    └── Components/
        ├── AppLayout.php                               # Main layout component
        └── GuestLayout.php                             # Guest layout component
```

**Purpose:** Contains all business logic, controllers, models, and requests.

---

### `/bootstrap` - Bootstrap Files

```
bootstrap/
├── app.php                      # Application instantiation
├── providers.php                # Service provider bootstrap
└── cache/
    ├── packages.php             # Cached packages list
    └── services.php             # Cached services configuration
```

**Purpose:** Core application bootstrap files and caching.

---

### `/config` - Configuration Files

```
config/
├── app.php                      # Application configuration
├── auth.php                     # Authentication configuration
├── cache.php                    # Cache driver configuration
├── database.php                 # Database connection configuration
├── filesystems.php              # File storage configuration
├── logging.php                  # Logging configuration
├── mail.php                     # Mail service configuration
├── queue.php                    # Queue jobs configuration
├── services.php                 # Third-party services configuration
└── session.php                  # Session configuration
```

**Purpose:** All application configuration settings.

---

### `/database` - Database Related Files

```
database/
├── factories/
│   └── UserFactory.php          # User model factory for testing
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php        # Create users table
│   ├── 0001_01_01_000001_create_cache_table.php        # Create cache table
│   └── 0001_01_01_000002_create_jobs_table.php         # Create jobs table
└── seeders/
    └── DatabaseSeeder.php                              # Database seeder class
```

**Purpose:** Database schema, migrations, and sample data generation.

---

### `/docs` - Documentation

```
docs/
├── designs/                     # UI/UX design files (empty)
├── guides/
│   ├── LARAVEL+BREEZE_GUIDE.md                        # Laravel Breeze setup guide
│   ├── MySQL_GUIDE.md                                 # MySQL usage guide
│   └── MySQL_INSTALLATION_GUIDE.md                    # MySQL installation guide
├── plans/                       # Project planning files (empty)
├── summaries/
│   └── FILE_STRUCTURE.md        # This file - complete project structure
└── tasks/                       # Task tracking files (empty)
```

**Purpose:** Project documentation, guides, and planning materials.

---

### `/public` - Public Web Root

```
public/
├── index.php                    # Laravel application entry point
├── .htaccess                    # Apache rewrite rules
├── favicon.ico                  # Website favicon
├── robots.txt                   # Search engine robots configuration
└── build/
    ├── manifest.json            # Vite asset manifest
    └── assets/
        ├── app-c0myYa0N.css     # Compiled CSS assets
        └── app-CJy8ASEk.js      # Compiled JavaScript assets
```

**Purpose:** Web-accessible files, entry point, and compiled assets.

---

### `/resources` - Frontend Assets and Views

```
resources/
├── css/
│   └── app.css                  # Main application CSS with Tailwind
├── js/
│   ├── app.js                   # Main JavaScript entry point
│   └── bootstrap.js             # JavaScript bootstrap (axios, etc.)
└── views/
    ├── welcome.blade.php        # Welcome/home page
    ├── dashboard.blade.php      # User dashboard
    ├── auth/
    │   ├── confirm-password.blade.php          # Confirm password page
    │   ├── forgot-password.blade.php           # Password reset request
    │   ├── login.blade.php                     # Login form
    │   ├── register.blade.php                  # Registration form
    │   ├── reset-password.blade.php            # Password reset form
    │   └── verify-email.blade.php              # Email verification
    ├── components/
    │   ├── application-logo.blade.php          # App logo component
    │   ├── auth-session-status.blade.php       # Auth status messages
    │   ├── danger-button.blade.php             # Danger button component
    │   ├── dropdown.blade.php                  # Dropdown menu
    │   ├── dropdown-link.blade.php             # Dropdown link
    │   ├── input-error.blade.php               # Error message display
    │   ├── input-label.blade.php               # Form label
    │   ├── modal.blade.php                     # Modal dialog
    │   ├── nav-link.blade.php                  # Navigation link
    │   ├── primary-button.blade.php            # Primary button
    │   ├── responsive-nav-link.blade.php       # Responsive nav link
    │   ├── secondary-button.blade.php          # Secondary button
    │   └── text-input.blade.php                # Text input field
    ├── layouts/
    │   ├── app.blade.php                       # Main application layout
    │   ├── guest.blade.php                     # Guest layout
    │   └── navigation.blade.php                # Navigation component
    └── profile/
        ├── edit.blade.php                      # Profile edit page
        └── partials/
            ├── delete-user-form.blade.php      # Delete account form
            ├── update-password-form.blade.php  # Password update form
            └── update-profile-information-form.blade.php  # Profile info form
```

**Purpose:** All frontend views (Blade templates), CSS styling, and JavaScript code.

---

### `/routes` - Route Definitions

```
routes/
├── web.php                      # Web routes (page navigation)
├── auth.php                     # Authentication routes (Breeze)
└── console.php                  # Console/CLI routes
```

**Purpose:** URL routing and endpoint definitions.

---

### `/storage` - Application Storage

```
storage/
├── app/
│   ├── private/                 # Private file storage
│   └── public/                  # Public file storage
├── framework/
│   ├── cache/
│   │   └── data/                # Cache data storage
│   ├── sessions/                # Session storage
│   ├── testing/                 # Testing storage
│   └── views/                   # Compiled view cache
├── logs/
│   ├── browser.log              # Browser testing log
│   └── laravel.log              # Application logs
```

**Purpose:** Runtime storage for files, cache, sessions, and logs.

---

### `/tests` - Application Tests

```
tests/
├── Pest.php                     # Pest configuration
├── TestCase.php                 # Base test case class
├── Feature/
│   ├── ExampleTest.php          # Example feature test
│   ├── ProfileTest.php          # Profile feature tests
│   └── Auth/
│       ├── AuthenticationTest.php              # Login/logout tests
│       ├── EmailVerificationTest.php           # Email verification tests
│       ├── PasswordConfirmationTest.php        # Password confirmation tests
│       ├── PasswordResetTest.php               # Password reset tests
│       ├── PasswordUpdateTest.php              # Password update tests
│       └── RegistrationTest.php                # User registration tests
└── Unit/
    └── ExampleTest.php          # Example unit test
```

**Purpose:** Feature tests (integration), unit tests, and testing utilities.

---

## Key Dependencies

### PHP Packages (via Composer)
- **Laravel Framework** - Web application framework
- **Laravel Breeze** - Authentication scaffolding
- **Pest** - Testing framework
- **Carbon** - Date/time manipulation
- **Doctrine DBAL** - Database abstraction
- Plus many other supporting packages

### Node.js Packages (via npm)
- **Vite** - Frontend build tool
- **Tailwind CSS** - Utility-first CSS framework
- **PostCSS** - CSS transformation tool
- **Alpine.js** - Lightweight JavaScript framework
- **Axios** - HTTP client
- Plus supporting build tools and dependencies

---

## Important Notes

1. **Environment Variables:** Configuration values are stored in `.env` file (not version controlled)
2. **Vendor Directory:** `/vendor` directory contains all Composer packages (not included in listing)
3. **Node Modules:** `/node_modules` directory contains all npm packages (not included in listing)
4. **Cache Directories:** `/storage/framework/views` contains compiled Blade templates
5. **Build Output:** All compiled assets are in `/public/build` directory
6. **Database:** Migrations define the database schema. Run `php artisan migrate` to apply.

---

## Common Commands

```bash
# Development
php artisan serve                           # Start development server
npm run dev                                 # Start Vite dev server

# Build
npm run build                               # Build for production

# Database
php artisan migrate                         # Run migrations
php artisan seed:run                        # Run seeders

# Testing
./vendor/bin/pest                           # Run tests
php artisan test                            # Alternative test command

# Cleanup
php artisan optimize                        # Optimize the application
php artisan cache:clear                     # Clear all caches
```

---

Last updated: November 28, 2025
