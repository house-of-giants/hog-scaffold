# Security Implementation Guide

This document provides comprehensive information about the security features implemented in the HoG Scaffold WordPress theme.

## Table of Contents

1. [Overview](#overview)
2. [Security Features](#security-features)
3. [Configuration](#configuration)
4. [File Structure](#file-structure)
5. [Security Classes](#security-classes)
6. [Best Practices](#best-practices)
7. [Troubleshooting](#troubleshooting)
8. [Security Updates](#security-updates)

## Overview

The HoG Scaffold theme implements a comprehensive security framework designed to protect WordPress installations from common vulnerabilities and attacks. The security implementation follows WordPress coding standards and security best practices.

### Security Principles

- **Defense in Depth**: Multiple layers of security controls
- **Least Privilege**: Minimal permissions and access rights
- **Input Validation**: All user inputs are sanitized and validated
- **Output Encoding**: All outputs are properly escaped
- **Secure by Default**: Security features are enabled by default

## Security Features

### 1. Input Sanitization and Validation

**Location**: `inc/security.php`

Comprehensive input sanitization for all user-provided data:

- **Text Input**: Length limits, special character handling
- **Email Validation**: RFC-compliant email validation
- **Phone Numbers**: Format validation and sanitization
- **URLs**: Protocol validation and sanitization
- **File Uploads**: MIME type validation, content scanning

**Functions**:

- `sanitize_text_input()`
- `sanitize_email_input()`
- `sanitize_phone_input()`
- `sanitize_url_input()`
- `sanitize_textarea_input()`

### 2. Nonce Verification System

**Location**: `inc/security.php`, `assets/js/security.js`

WordPress nonce implementation for CSRF protection:

- **Form Protection**: All forms include nonce verification
- **AJAX Security**: Secure AJAX request handling
- **Admin Actions**: Protected admin form submissions
- **File Uploads**: Nonce-protected file upload endpoints

**Key Features**:

- Automatic nonce generation
- JavaScript nonce handling
- Expired nonce detection
- Action-specific nonces

### 3. File Security Hardening

**Location**: `inc/classes/File_Security.php`, `.htaccess` files

Comprehensive file upload and access security:

- **Upload Validation**: File type, size, and content validation
- **Malware Scanning**: Basic content scanning for suspicious patterns
- **Secure Filenames**: Sanitized and randomized filenames
- **Directory Protection**: .htaccess files prevent direct access
- **MIME Type Verification**: Real file type detection

**Protected Directories**:

- `/inc/` - PHP includes directory
- `/assets/` - Asset files with execution prevention
- Upload directories with index.php files

### 4. Authentication Enhancement

**Location**: `inc/classes/Login_Security.php`

Advanced login security and user authentication:

- **Login Attempt Limiting**: Configurable failed attempt thresholds
- **Account Lockouts**: Temporary lockouts after failed attempts
- **Password Strength**: Enforced strong password requirements
- **Login Monitoring**: Comprehensive login attempt logging
- **IP-based Tracking**: Per-IP and per-user attempt tracking

**Password Requirements**:

- Minimum 8 characters
- Uppercase and lowercase letters
- Numbers and special characters
- Common password blacklist

### 5. Security Headers Implementation

**Location**: `inc/security.php`

Comprehensive HTTP security headers:

- **X-Frame-Options**: Clickjacking protection
- **X-XSS-Protection**: Cross-site scripting protection
- **X-Content-Type-Options**: MIME type sniffing prevention
- **Content-Security-Policy**: Resource loading restrictions
- **Strict-Transport-Security**: HTTPS enforcement
- **Referrer-Policy**: Referrer information control

**CSP Directives**:

```
default-src 'self';
script-src 'self' 'unsafe-inline' 'unsafe-eval' [trusted domains];
style-src 'self' 'unsafe-inline' [font providers];
img-src 'self' data: https: http:;
```

### 6. WordPress Core Hardening

**Location**: `inc/security.php`

WordPress-specific security enhancements:

- **Version Hiding**: Remove WordPress version information
- **User Enumeration Prevention**: Block author discovery methods
- **XMLRPC Security**: Disable or secure XMLRPC endpoints
- **File Editing Prevention**: Disable theme/plugin editing
- **Directory Browsing**: Prevent directory listing
- **Heartbeat Optimization**: Reduce unnecessary requests

## Configuration

### Security Features Toggle

Security features can be enabled/disabled via the `hog_scaffold_security_features` option:

```php
$security_features = array(
    'disable_xmlrpc' => true,
    'log_failed_logins' => true,
    'enforce_strong_passwords' => true,
    'limit_login_attempts' => true,
    'security_headers' => true,
    'file_upload_security' => true,
);
update_option('hog_scaffold_security_features', $security_features);
```

### Security Constants

Configure security limits in `inc/security-config.php`:

```php
// Rate limiting
define('HOG_SCAFFOLD_RATE_LIMIT_ATTEMPTS', 5);
define('HOG_SCAFFOLD_RATE_LIMIT_WINDOW', HOUR_IN_SECONDS);

// File upload limits
define('HOG_SCAFFOLD_MAX_UPLOAD_SIZE', 2 * 1024 * 1024); // 2MB
define('HOG_SCAFFOLD_ALLOWED_IMAGE_TYPES', array(
    'image/jpeg', 'image/png', 'image/gif', 'image/webp'
));

// Input validation limits
define('HOG_SCAFFOLD_MAX_NAME_LENGTH', 100);
define('HOG_SCAFFOLD_MAX_MESSAGE_LENGTH', 2000);
```

### Content Security Policy Customization

Customize CSP directives using the filter:

```php
add_filter('hog_scaffold_csp_directives', function($directives) {
    // Add custom domains
    $directives[] = "connect-src 'self' https://api.example.com";
    return $directives;
});
```

## File Structure

```
theme-root/
├── inc/
│   ├── security.php              # Main security functions
│   ├── security-config.php       # Security configuration
│   ├── classes/
│   │   ├── File_Security.php     # File security handler
│   │   └── Login_Security.php    # Login security handler
│   └── .htaccess                 # Directory protection
├── assets/
│   ├── js/
│   │   └── security.js           # Client-side security utilities
│   └── .htaccess                 # Asset directory protection
├── .htaccess                     # Root security rules
├── SECURITY.md                   # This documentation
└── SECURITY_TESTING.md           # Testing guide
```

## Security Classes

### File_Security Class

**Purpose**: Handle secure file operations and uploads

**Key Methods**:

- `validate_file_upload()` - Comprehensive file validation
- `perform_security_scan()` - Content-based security scanning
- `secure_file_upload()` - Safe file upload processing
- `generate_secure_filename()` - Secure filename generation

**Usage**:

```php
use HoGScaffold\Security\File_Security;

// Initialize file security
File_Security::init();

// Validate uploaded file
$validation = File_Security::validate_file_upload($_FILES['file']);
if (is_wp_error($validation)) {
    // Handle validation error
}
```

### Login_Security Class

**Purpose**: Manage login security and authentication

**Key Methods**:

- `handle_failed_login()` - Process failed login attempts
- `check_login_attempts()` - Verify lockout status
- `validate_password_strength()` - Enforce password requirements
- `get_user_ip()` - Accurate IP address detection

**Usage**:

```php
use HoGScaffold\Security\Login_Security;

// Initialize login security
Login_Security::init();

// Check password strength
$strength = Login_Security::check_password_strength($password);
if (is_wp_error($strength)) {
    // Handle weak password
}
```

## Best Practices

### For Developers

1. **Always Validate Input**: Use provided sanitization functions
2. **Verify Nonces**: Include nonce verification in all forms
3. **Escape Output**: Use WordPress escaping functions
4. **Check Capabilities**: Verify user permissions
5. **Log Security Events**: Use `log_security_event()` for important events

### For Site Administrators

1. **Regular Updates**: Keep WordPress, themes, and plugins updated
2. **Strong Passwords**: Enforce strong password policies
3. **Monitor Logs**: Review security logs regularly
4. **Backup Strategy**: Maintain regular, secure backups
5. **SSL/TLS**: Use HTTPS for all communications

### For Content Editors

1. **Safe Uploads**: Only upload files from trusted sources
2. **Link Verification**: Verify external links before adding
3. **User Management**: Remove unused user accounts
4. **Plugin Caution**: Only install necessary plugins

## Troubleshooting

### Common Issues

#### Users Getting Locked Out

**Symptoms**: Legitimate users cannot log in
**Solution**:

```php
// Adjust lockout settings
define('HOG_SCAFFOLD_RATE_LIMIT_ATTEMPTS', 10); // Increase attempts
define('HOG_SCAFFOLD_RATE_LIMIT_WINDOW', 30 * MINUTE_IN_SECONDS); // Reduce window
```

#### CSP Blocking Resources

**Symptoms**: External resources not loading
**Solution**: Update CSP directives in `get_content_security_policy()`

#### File Upload Issues

**Symptoms**: Legitimate files being rejected
**Solution**: Check file size limits and allowed MIME types

#### Performance Impact

**Symptoms**: Slow page loads
**Solution**: Review security header implementation and logging frequency

### Debug Mode

Enable debug logging by adding to `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

Security events will be logged to `/wp-content/debug.log`.

### Security Dashboard

Access the security dashboard widget in WordPress admin:

- Navigate to Dashboard
- View "Security Status" widget
- Monitor user sessions, lockouts, and feature status

## Security Updates

### Monitoring Security

1. **WordPress Security Blog**: Follow WordPress security announcements
2. **Plugin Updates**: Monitor security updates for installed plugins
3. **Theme Updates**: Keep the theme updated with security patches
4. **Security Plugins**: Consider additional security plugins for monitoring

### Incident Response

If a security incident occurs:

1. **Immediate Response**:

   - Change all passwords
   - Update WordPress core and plugins
   - Review user accounts and permissions

2. **Investigation**:

   - Review security logs
   - Identify attack vectors
   - Assess damage and data exposure

3. **Recovery**:

   - Restore from clean backups if necessary
   - Implement additional security measures
   - Monitor for continued threats

4. **Prevention**:
   - Update security configurations
   - Improve monitoring and logging
   - Conduct security training

### Reporting Vulnerabilities

If you discover a security vulnerability:

1. **Do Not** disclose publicly immediately
2. Contact the theme maintainers privately
3. Provide detailed reproduction steps
4. Allow time for patch development
5. Coordinate responsible disclosure

## Security Compliance

This implementation addresses common security frameworks:

- **OWASP Top 10**: Protection against common web vulnerabilities
- **WordPress Security Standards**: Follows WordPress security guidelines
- **PCI DSS**: Relevant controls for payment processing
- **GDPR**: Privacy and data protection considerations

## Additional Resources

- [WordPress Security Handbook](https://developer.wordpress.org/advanced-administration/security/)
- [OWASP Web Security Testing Guide](https://owasp.org/www-project-web-security-testing-guide/)
- [WordPress Security Plugin Directory](https://wordpress.org/plugins/tags/security/)
- [Security Headers Reference](https://securityheaders.com/)

---

**Last Updated**: [Current Date]
**Version**: 1.0
**Maintainer**: HoG Scaffold Development Team
