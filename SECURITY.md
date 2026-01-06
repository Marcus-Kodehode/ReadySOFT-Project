# Security Policy

## Reporting a Vulnerability

**Schedulo is a private project.** If you discover a security vulnerability, please report it responsibly.

### How to Report

**DO NOT** create a public GitHub issue for security vulnerabilities.

Instead, please email the maintainer directly:

📧 **Email**: marcusboerresen@gmail.com

**Subject**: [SECURITY] Brief description of the issue

### What to Include

Please provide as much information as possible:

1. **Description**: Clear description of the vulnerability
2. **Impact**: Potential impact and severity
3. **Steps to Reproduce**: Detailed steps to reproduce the issue
4. **Proof of Concept**: Code or screenshots demonstrating the vulnerability
5. **Suggested Fix**: If you have ideas for fixing the issue
6. **Your Contact Info**: So we can follow up with questions

### Example Report

```
Subject: [SECURITY] SQL Injection in Resource Search

Description:
The resource search functionality is vulnerable to SQL injection attacks.

Impact:
An attacker could potentially access or modify database records.

Steps to Reproduce:
1. Navigate to /resources/search
2. Enter the following in the search field: ' OR '1'='1
3. Submit the form
4. Observe unauthorized data access

Proof of Concept:
[Screenshot or code snippet]

Suggested Fix:
Use parameterized queries or Eloquent ORM instead of raw SQL.

Contact: your-email@example.com
```

---

## Response Timeline

- **Acknowledgment**: Within 48 hours
- **Initial Assessment**: Within 1 week
- **Status Update**: Every 2 weeks until resolved
- **Fix Deployment**: Depends on severity (critical issues prioritized)

---

## Severity Levels

### Critical
- Remote code execution
- SQL injection
- Authentication bypass
- Data breach potential

**Response**: Immediate (within 24-48 hours)

### High
- XSS vulnerabilities
- CSRF vulnerabilities
- Privilege escalation
- Sensitive data exposure

**Response**: Within 1 week

### Medium
- Information disclosure
- Denial of service
- Session management issues

**Response**: Within 2 weeks

### Low
- Minor security improvements
- Best practice violations
- Non-critical configuration issues

**Response**: Next release cycle

---

## Security Measures

### Current Protections

#### Application Security
- **CSRF Protection**: All forms protected with CSRF tokens
- **Password Security**: Bcrypt hashing with appropriate cost factor
- **SQL Injection Prevention**: Eloquent ORM and parameterized queries
- **XSS Protection**: Blade template escaping by default
- **Session Security**: Secure session configuration
- **Input Validation**: Server-side validation on all inputs

#### Infrastructure Security
- **Environment Variables**: Sensitive data in `.env` (not committed)
- **HTTPS**: Required for production deployments
- **Database Security**: Restricted database user permissions
- **File Permissions**: Proper file and directory permissions

#### Authentication & Authorization
- **Password Requirements**: Minimum 8 characters
- **Role-Based Access**: Tenant isolation and role permissions
- **Session Management**: Secure session handling
- **Remember Me**: Secure token-based remember functionality

### Planned Improvements

- [ ] Two-factor authentication (2FA)
- [ ] Rate limiting on authentication endpoints
- [ ] Security headers (CSP, HSTS, etc.)
- [ ] Automated security scanning
- [ ] Regular dependency updates
- [ ] Security audit logging
- [ ] IP whitelisting for admin access
- [ ] Encrypted database backups

---

## Disclosure Policy

### Responsible Disclosure

We follow responsible disclosure practices:

1. **Private Reporting**: Report vulnerabilities privately
2. **Coordinated Disclosure**: Work with us on timing
3. **Credit**: We'll credit you in release notes (if desired)
4. **No Retaliation**: We won't take legal action against good-faith researchers

### Public Disclosure

After a fix is deployed:

1. We'll publish a security advisory
2. Update CHANGELOG.md with security fixes
3. Credit the reporter (with permission)
4. Notify affected users if necessary

---

## Security Best Practices for Users

### For Administrators

- Use strong, unique passwords
- Enable 2FA when available
- Keep software updated
- Review user permissions regularly
- Monitor access logs
- Use HTTPS in production
- Backup data regularly
- Restrict database access

### For Developers

- Never commit `.env` files
- Use environment variables for secrets
- Keep dependencies updated
- Run security audits regularly
- Follow coding standards
- Write security tests
- Review code before merging
- Use secure coding practices

### For Deployment

```bash
# Production checklist
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Use strong database credentials
DB_PASSWORD=strong_random_password

# Configure secure session
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict

# Enable HTTPS
FORCE_HTTPS=true
```

---

## Security Updates

### Staying Informed

Security updates will be announced via:

- GitHub releases (for authorized users)
- Direct email to administrators
- CHANGELOG.md updates

### Applying Updates

```bash
# Pull latest changes
git pull origin main

# Update dependencies
composer update
npm update

# Run migrations
php artisan migrate

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Rebuild assets
npm run build
```

---

## Compliance

### Data Protection

- **GDPR Compliance**: User data handling follows GDPR principles
- **Data Minimization**: Only collect necessary data
- **Right to Deletion**: Users can request data deletion
- **Data Portability**: Users can export their data
- **Consent**: Clear consent for data processing

### Privacy

- User data is isolated per tenant
- No data sharing between tenants
- Secure data storage and transmission
- Regular security audits
- Incident response plan

---

## Contact

For security concerns:

📧 **Email**: marcusboerresen@gmail.com  
🔒 **PGP Key**: Available upon request

For general support:
- See [README.md](README.md)
- See [INSTALLATION.md](INSTALLATION.md)

---

## Acknowledgments

We appreciate security researchers who help keep Schedulo secure. Thank you for responsible disclosure!

### Hall of Fame

Security researchers who have responsibly disclosed vulnerabilities will be listed here (with permission).

---

**Last Updated**: January 6, 2026

This security policy may be updated periodically. Check back regularly for updates.
