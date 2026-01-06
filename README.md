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

### 1. Clone the repository
```bash
git clone https://github.com/yourusername/schedulo.git
cd schedulo
```

### 2. Install dependencies
```bash
composer install
npm install
```

### 3. Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure database
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=schedulo
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run migrations and seeders
```bash
php artisan migrate
php artisan db:seed --class=PlanSeeder
```

### 6. Build frontend assets
```bash
npm run build
```

### 7. Start development server
```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` in your browser.

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

This is a private project. Security vulnerabilities should be reported directly to the project maintainer at [your-email@example.com].

**Security measures implemented:**
- CSRF protection on all forms
- Password hashing with bcrypt
- SQL injection prevention via Eloquent ORM
- XSS protection in Blade templates
- Environment-based configuration

---

## 📝 License

**Proprietary License**

Copyright (c) 2026 ReadySOFT. All rights reserved.

This software is proprietary and confidential. Unauthorized copying, distribution, modification, or use of this software, via any medium, is strictly prohibited without explicit written permission from the copyright holder.

---

## 🤝 Contributing

This is a private project under strict control. Contributions are currently limited to authorized collaborators only.

If you're interested in contributing:
1. Contact the project maintainer for authorization
2. Fork the repository (if granted access)
3. Create a feature branch (`git checkout -b feature/amazing-feature`)
4. Commit your changes (`git commit -m 'Add amazing feature'`)
5. Push to the branch (`git push origin feature/amazing-feature`)
6. Open a Pull Request for review

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

## 📞 Support

For support, contact the development team or open an issue in the repository (authorized users only).

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

**Built with ❤️ by ReadySOFT**
