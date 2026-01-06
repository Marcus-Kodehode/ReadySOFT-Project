# Schedulo

**A modern multi-tenant booking and resource management platform**

Schedulo is a comprehensive SaaS solution designed for businesses that need to manage bookings, resources, and customer relationships. Built with Laravel 12 and modern web technologies, it provides a scalable, secure, and user-friendly platform for salons, spas, cabin rentals, and other service-based businesses.

---

## 🚀 Features

- **Multi-Tenant Architecture** - Complete isolation between tenants with dedicated data and custom URLs
- **Resource Management** - Create and manage bookable resources (rooms, equipment, staff, etc.)
- **Booking System** - Intuitive booking interface with real-time availability
- **Subscription Plans** - Flexible pricing tiers with feature-based access control
- **User Authentication** - Secure registration and login with role-based permissions
- **Responsive Design** - Mobile-first UI built with Tailwind CSS
- **Real-time Validation** - Client-side form validation with Alpine.js
- **Custom Slugs** - Unique booking URLs for each tenant (e.g., `yoursite.com/salon-rosa`)

---

## 🛠️ Tech Stack

### Backend
- **Laravel 12** - PHP framework for web artisans
- **PHP 8.3** - Modern PHP with performance improvements
- **MySQL** - Relational database for data persistence
- **Laravel Breeze** - Lightweight authentication scaffolding

### Frontend
- **Vite** - Next-generation frontend tooling
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Blade Templates** - Laravel's powerful templating engine

### Development Tools
- **Composer** - PHP dependency management
- **NPM** - JavaScript package management
- **Laravel Pint** - Code style fixer
- **Pest** - Testing framework

---

## 📋 Requirements

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18.x or higher
- MySQL 8.0 or higher
- NPM or Yarn

---

## 🔧 Installation

For detailed installation instructions, see the **[Complete Installation Guide](INSTALLATION.md)**.

### Quick Start

```bash
# Clone repository
git clone https://github.com/yourusername/schedulo.git
cd schedulo

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env, then:
php artisan migrate
php artisan db:seed --class=PlanSeeder

# Build assets and start server
npm run build
php artisan serve
```

Visit `http://127.0.0.1:8000` in your browser.

**Need help?** Check the [Installation Guide](INSTALLATION.md) for troubleshooting and detailed steps.

---

## 🚀 Quick Start (Alternative)

Use the automated setup script:
```bash
composer setup
php artisan serve
```

For development with hot reload:
```bash
composer dev
```

---

## 📁 Project Structure

```
schedulo/
├── app/
│   ├── Http/Controllers/    # Application controllers
│   ├── Models/              # Eloquent models
│   ├── Services/            # Business logic services
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── views/               # Blade templates
│   ├── js/                  # JavaScript files
│   └── css/                 # Stylesheets
├── routes/
│   ├── web.php              # Web routes
│   └── api.php              # API routes
├── public/                  # Public assets
└── tests/                   # Test files
```

---

## 🧪 Testing

Run the test suite:
```bash
composer test
```

Or use Pest directly:
```bash
php artisan test
```

---

## 🔐 Security

This is a private project. Security vulnerabilities should be reported directly to the project maintainer at marcusboerresen@gmail.com.

**Security measures implemented:**
- CSRF protection on all forms
- Password hashing with bcrypt
- SQL injection prevention via Eloquent ORM
- XSS protection in Blade templates
- Environment-based configuration

**Please do not disclose security vulnerabilities publicly.**

---

## 📝 License

**Proprietary License**

Copyright (c) 2026 Marcus Boersnes. All rights reserved.

This software is proprietary and confidential. Unauthorized copying, distribution, modification, or use of this software, via any medium, is strictly prohibited without explicit written permission from the copyright holder.

See [LICENSE](LICENSE) for full terms.

---

## 🤝 Contributing

This is a private project under strict control. Contributions are currently limited to authorized collaborators only.

**Interested in contributing?** Read the [Contributing Guidelines](CONTRIBUTING.md) for:
- How to get authorization
- Development workflow
- Coding standards
- Pull request process
- Testing requirements

All contributions must be reviewed and approved before merging.

---

## 👨‍💻 Development

### Code Style
This project follows PSR-12 coding standards. Format your code with:
```bash
./vendor/bin/pint
```

### Database
Reset database with fresh migrations:
```bash
php artisan migrate:fresh --seed
```

### Cache Management
Clear application cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## 📞 Support & Documentation

- **Installation Guide**: [INSTALLATION.md](INSTALLATION.md)
- **Contributing Guide**: [CONTRIBUTING.md](CONTRIBUTING.md)
- **Changelog**: [CHANGELOG.md](CHANGELOG.md)
- **Email Support**: marcusboerresen@gmail.com

For issues, contact the maintainer directly (authorized users only).

---

## 🗺️ Roadmap

- [ ] Advanced booking calendar view
- [ ] Email notifications for bookings
- [ ] SMS reminders
- [ ] Payment integration (Stripe/Vipps)
- [ ] Customer portal
- [ ] Analytics dashboard
- [ ] Mobile app (iOS/Android)
- [ ] API for third-party integrations

---

**Built with ❤️ by Marcus**
