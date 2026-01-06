# Contributing to Schedulo

Thank you for your interest in contributing to Schedulo! This document provides guidelines and instructions for contributing to the project.

---

## 🔒 Important Notice

**Schedulo is a private project under strict control.** Contributions are currently limited to authorized collaborators only. All contributions must be reviewed and approved by the project maintainer before merging.

---

## 📋 Table of Contents

1. [Getting Authorization](#getting-authorization)
2. [Code of Conduct](#code-of-conduct)
3. [Development Setup](#development-setup)
4. [Contribution Workflow](#contribution-workflow)
5. [Coding Standards](#coding-standards)
6. [Commit Guidelines](#commit-guidelines)
7. [Pull Request Process](#pull-request-process)
8. [Testing Requirements](#testing-requirements)

---

## Getting Authorization

Before contributing, you must:

1. **Contact the maintainer**: Email marcusboerresen@gmail.com
2. **Explain your interest**: Describe what you'd like to contribute
3. **Wait for approval**: You'll receive access if approved
4. **Sign agreement**: You may be asked to sign a contributor agreement

**Note**: By contributing, you agree that all contributions become the exclusive property of Marcus Boersnes as stated in the LICENSE.

---

## Code of Conduct

### Our Standards

- Be respectful and professional
- Focus on constructive feedback
- Accept criticism gracefully
- Prioritize the project's best interests
- Maintain confidentiality of proprietary information

### Unacceptable Behavior

- Harassment or discriminatory language
- Sharing proprietary code without permission
- Unauthorized distribution of the software
- Disruptive or unprofessional conduct

---

## Development Setup

### Prerequisites

Ensure you have completed the [Installation Guide](INSTALLATION.md) before contributing.

### Fork and Clone

```bash
# Fork the repository (if granted access)
# Then clone your fork
git clone https://github.com/your-username/schedulo.git
cd schedulo

# Add upstream remote
git remote add upstream https://github.com/original-owner/schedulo.git
```

### Keep Your Fork Updated

```bash
git fetch upstream
git checkout main
git merge upstream/main
```

---

## Contribution Workflow

### 1. Create a Feature Branch

```bash
git checkout -b feature/your-feature-name
```

**Branch naming conventions**:
- `feature/` - New features (e.g., `feature/email-notifications`)
- `fix/` - Bug fixes (e.g., `fix/booking-validation`)
- `refactor/` - Code refactoring (e.g., `refactor/resource-service`)
- `docs/` - Documentation updates (e.g., `docs/api-endpoints`)
- `test/` - Test additions (e.g., `test/booking-controller`)

### 2. Make Your Changes

- Write clean, readable code
- Follow existing code style
- Add comments for complex logic
- Update documentation if needed

### 3. Test Your Changes

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=BookingTest

# Check code style
./vendor/bin/pint --test
```

### 4. Commit Your Changes

```bash
git add .
git commit -m "feat: add email notification system"
```

See [Commit Guidelines](#commit-guidelines) below.

### 5. Push to Your Fork

```bash
git push origin feature/your-feature-name
```

### 6. Create Pull Request

1. Go to the original repository on GitHub
2. Click "New Pull Request"
3. Select your branch
4. Fill in the PR template
5. Submit for review

---

## Coding Standards

### PHP (Laravel)

**Follow PSR-12 standards**:
```bash
./vendor/bin/pint
```

**Best practices**:
- Use type hints for parameters and return types
- Keep methods small and focused (single responsibility)
- Use dependency injection
- Write descriptive variable names
- Add PHPDoc blocks for classes and methods

**Example**:
```php
<?php

namespace App\Services;

use App\Models\Resource;
use Illuminate\Support\Collection;

class ResourceService
{
    /**
     * Get available resources for a given date range.
     *
     * @param string $startDate
     * @param string $endDate
     * @return Collection<Resource>
     */
    public function getAvailableResources(string $startDate, string $endDate): Collection
    {
        return Resource::query()
            ->where('active', true)
            ->whereDoesntHave('bookings', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_time', [$startDate, $endDate]);
            })
            ->get();
    }
}
```

### JavaScript

**Use modern ES6+ syntax**:
```javascript
// Good
const fetchResources = async () => {
    const response = await fetch('/api/resources');
    return response.json();
};

// Avoid
var fetchResources = function() {
    return fetch('/api/resources').then(function(response) {
        return response.json();
    });
};
```

### Blade Templates

**Keep logic minimal**:
```blade
{{-- Good --}}
@if($user->isAdmin())
    <x-admin-panel />
@endif

{{-- Avoid complex logic in views --}}
```

### CSS (Tailwind)

**Use Tailwind utility classes**:
```html
<!-- Good -->
<button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
    Submit
</button>

<!-- Avoid custom CSS unless necessary -->
```

---

## Commit Guidelines

### Commit Message Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types

- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, no logic change)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

### Examples

```bash
# Feature
git commit -m "feat(booking): add email confirmation"

# Bug fix
git commit -m "fix(auth): resolve login redirect issue"

# Documentation
git commit -m "docs(readme): update installation steps"

# Refactoring
git commit -m "refactor(resource): extract validation logic to service"
```

### Detailed Commit

```
feat(booking): add email confirmation system

- Implement email notification service
- Add booking confirmation template
- Update booking controller to send emails
- Add tests for email functionality

Closes #123
```

---

## Pull Request Process

### PR Template

When creating a PR, include:

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] All tests pass
- [ ] Added new tests
- [ ] Manual testing completed

## Checklist
- [ ] Code follows project style guidelines
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] No breaking changes (or documented)

## Screenshots (if applicable)
Add screenshots for UI changes
```

### Review Process

1. **Automated checks**: CI/CD runs tests and code quality checks
2. **Code review**: Maintainer reviews your code
3. **Feedback**: Address any requested changes
4. **Approval**: Once approved, your PR will be merged
5. **Cleanup**: Delete your feature branch after merge

### Review Criteria

Your PR will be evaluated on:
- Code quality and readability
- Test coverage
- Documentation completeness
- Performance impact
- Security considerations
- Adherence to project standards

---

## Testing Requirements

### Write Tests for New Features

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Resource;

class ResourceTest extends TestCase
{
    public function test_user_can_create_resource(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/resources', [
            'name' => 'Test Resource',
            'type' => 'Room',
            'capacity' => 10,
        ]);
        
        $response->assertStatus(302);
        $this->assertDatabaseHas('resources', [
            'name' => 'Test Resource',
        ]);
    }
}
```

### Test Coverage

- Aim for at least 80% code coverage
- Test happy paths and edge cases
- Include integration tests for critical features
- Test error handling

### Running Tests

```bash
# All tests
php artisan test

# With coverage
php artisan test --coverage

# Specific test file
php artisan test tests/Feature/ResourceTest.php

# Specific test method
php artisan test --filter=test_user_can_create_resource
```

---

## Questions?

If you have questions about contributing:

1. Check existing documentation
2. Review closed PRs for examples
3. Contact the maintainer: marcusboerresen@gmail.com

---

**Thank you for contributing to Schedulo! 🎉**

Your contributions help make this project better for everyone.
