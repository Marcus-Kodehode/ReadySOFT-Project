# Schedulo - Complete Installation Guide

This guide will walk you through the complete installation process for Schedulo, from system requirements to running the application.

---

## 📋 Table of Contents

1. [System Requirements](#system-requirements)
2. [Installation Steps](#installation-steps)
3. [Configuration](#configuration)
4. [Database Setup](#database-setup)
5. [Running the Application](#running-the-application)
6. [Troubleshooting](#troubleshooting)
7. [First Steps](#first-steps)

---

## System Requirements

Before you begin, ensure your system meets these requirements:

### Required Software

- **PHP**: 8.2 or higher
  - Extensions: `mbstring`, `xml`, `bcmath`, `pdo`, `pdo_mysql`, `curl`, `zip`, `gd`
- **Composer**: 2.x or higher
- **Node.js**: 18.x or higher
- **NPM**: 9.x or higher (comes with Node.js)
- **MySQL**: 8.0 or higher
- **Git**: Latest version

### Recommended

- **Web Server**: Apache or Nginx (for production)
- **SSL Certificate**: For production deployment
- **Memory**: At least 512MB RAM for development

---

## Installation Steps

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/schedulo.git
cd schedulo
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

This will install all Laravel and PHP dependencies defined in `composer.json`.

**Note**: If you encounter memory issues, try:
```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

### Step 3: Install JavaScript Dependencies

```bash
npm install
```

This installs all frontend dependencies including Vite, Tailwind CSS, and Alpine.js.

### Step 4: Create Environment File

```bash
# Windows
copy .env.example .env

# macOS/Linux
cp .env.example .env
```

### Step 5: Generate Application Key

```bash
php artisan key:generate
```

This creates a unique encryption key for your application.

---

## Configuration

### Database Configuration

Open the `.env` file and configure your database settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=schedulo
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

### Application Configuration

Update these settings in `.env`:

```env
APP_NAME=Schedulo
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

**Important**: Set `APP_DEBUG=false` in production!

### Mail Configuration (Optional)

For email notifications (future feature):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@schedulo.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Database Setup

### Step 1: Create Database

Create a new MySQL database:

```sql
CREATE DATABASE schedulo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Or use your MySQL client (phpMyAdmin, MySQL Workbench, etc.)

### Step 2: Run Migrations

```bash
php artisan migrate
```

This creates all necessary database tables.

### Step 3: Seed Database

```bash
php artisan db:seed --class=PlanSeeder
```

This creates the default subscription plans (Basic, Professional, Enterprise).

**Optional**: To reset and reseed everything:
```bash
php artisan migrate:fresh --seed
```

⚠️ **Warning**: `migrate:fresh` will delete all existing data!

---

## Running the Application

### Development Mode

#### Option 1: Simple Start

```bash
php artisan serve
```

Then in a separate terminal:
```bash
npm run dev
```

Visit: `http://127.0.0.1:8000`

#### Option 2: Automated Start (Recommended)

```bash
composer dev
```

This starts the Laravel server, queue worker, and Vite dev server simultaneously.

### Production Build

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Troubleshooting

### Common Issues

#### 1. "Class not found" errors

**Solution**: Clear and regenerate autoload files
```bash
composer dump-autoload
php artisan clear-compiled
```

#### 2. "No application encryption key has been specified"

**Solution**: Generate a new key
```bash
php artisan key:generate
```

#### 3. "SQLSTATE[HY000] [2002] Connection refused"

**Solution**: Check database credentials in `.env` and ensure MySQL is running
```bash
# Check MySQL status (macOS/Linux)
sudo systemctl status mysql

# Windows - check in Services
```

#### 4. "Vite manifest not found"

**Solution**: Build frontend assets
```bash
npm install
npm run build
```

#### 5. "Permission denied" errors

**Solution**: Set correct permissions (macOS/Linux)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 6. Port 8000 already in use

**Solution**: Use a different port
```bash
php artisan serve --port=8080
```

### Clear All Caches

If you encounter strange behavior:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

---

## First Steps

### 1. Register Your First Account

1. Visit `http://127.0.0.1:8000`
2. Click "Register"
3. Fill in the registration form:
   - **Name**: Your full name
   - **Email**: Your email address
   - **Password**: At least 8 characters
   - **Business Name**: Your business name (e.g., "Salon Rosa")
   - **Business Type**: Select from dropdown
   - **URL Slug**: Auto-generated, but you can customize it

4. Click "Register"

### 2. Explore the Dashboard

After registration, you'll be redirected to your dashboard where you can:
- View your subscription plan
- Create resources (rooms, equipment, staff)
- Manage bookings
- Configure settings

### 3. Create Your First Resource

1. Navigate to "Resources" in the sidebar
2. Click "Create Resource"
3. Fill in the details:
   - **Name**: Resource name (e.g., "Treatment Room 1")
   - **Description**: Brief description
   - **Type**: Select type (Room, Equipment, Staff, etc.)
   - **Capacity**: Number of people/items
   - **Opening Hours**: Set availability

4. Click "Create Resource"

### 4. Test Booking System

1. Visit your public booking page: `http://127.0.0.1:8000/your-slug`
2. Browse available resources
3. Make a test booking

---

## Development Tips

### Database Management

**View database in terminal**:
```bash
php artisan tinker
>>> \App\Models\User::all();
>>> \App\Models\Resource::count();
```

**Create test data**:
```bash
php artisan tinker
>>> \App\Models\User::factory()->count(5)->create();
```

### Code Quality

**Format code**:
```bash
./vendor/bin/pint
```

**Run tests**:
```bash
php artisan test
```

### Useful Commands

```bash
# List all routes
php artisan route:list

# List all artisan commands
php artisan list

# Check Laravel version
php artisan --version

# Open interactive shell
php artisan tinker

# Create a new controller
php artisan make:controller YourController

# Create a new model with migration
php artisan make:model YourModel -m
```

---

## Next Steps

- Read the [User Guide](USER_GUIDE.md) for detailed feature documentation
- Check the [API Documentation](API.md) if you plan to integrate with other systems
- Review [Contributing Guidelines](CONTRIBUTING.md) if you want to contribute

---

## Getting Help

If you encounter issues not covered in this guide:

1. Check the [Troubleshooting](#troubleshooting) section
2. Review Laravel documentation: https://laravel.com/docs
3. Contact support: marcusboerresen@gmail.com

---

**Installation complete! 🎉**

You're now ready to start using Schedulo. Happy booking!
