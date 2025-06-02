# Security Testing Guide

This document outlines how to test the security features implemented in the HoG Scaffold WordPress theme.

## Overview

The theme includes comprehensive security measures across multiple areas:

- Input sanitization and validation
- Nonce verification system
- File security hardening
- Authentication enhancement
- Security headers implementation
- WordPress core hardening

## Testing Checklist

### 1. Input Sanitization Testing

#### Contact Form Security

- [ ] Test XSS prevention by submitting `<script>alert('xss')</script>` in form fields
- [ ] Verify SQL injection protection with inputs like `'; DROP TABLE wp_users; --`
- [ ] Test maximum length validation for name (100 chars), message (2000 chars)
- [ ] Verify email validation with invalid formats
- [ ] Test phone number sanitization with special characters

#### File Upload Security

- [ ] Attempt to upload PHP files (should be blocked)
- [ ] Test file size limits (default 2MB)
- [ ] Upload images with embedded PHP code in EXIF data
- [ ] Try uploading files with dangerous extensions (.exe, .bat, .sh)
- [ ] Verify MIME type validation

### 2. Nonce Verification Testing

#### AJAX Security

- [ ] Submit forms without nonces (should fail)
- [ ] Use expired nonces (should fail)
- [ ] Test nonce reuse across different actions
- [ ] Verify nonces are properly generated and validated

#### Form Security

- [ ] Test contact form with missing nonce
- [ ] Verify file upload nonce validation
- [ ] Check admin form nonce implementation

### 3. Authentication Enhancement Testing

#### Login Attempt Limiting

- [ ] Make 5 failed login attempts to trigger lockout
- [ ] Verify lockout duration (30 minutes default)
- [ ] Test lockout message display
- [ ] Confirm successful login clears attempts counter

#### Password Strength Validation

- [ ] Test passwords under 8 characters (should fail)
- [ ] Try passwords without uppercase letters
- [ ] Try passwords without lowercase letters
- [ ] Try passwords without numbers
- [ ] Try passwords without special characters
- [ ] Test common passwords from blacklist

#### Login Monitoring

- [ ] Check security logs for failed login attempts
- [ ] Verify successful login logging
- [ ] Test IP address detection accuracy

### 4. File Security Testing

#### Directory Protection

- [ ] Attempt to browse `/inc/` directory (should be blocked)
- [ ] Try accessing `/assets/` directory
- [ ] Verify index.php files exist in protected directories
- [ ] Test .htaccess file effectiveness

#### File Upload Validation

- [ ] Upload legitimate image files (should work)
- [ ] Try uploading renamed PHP files with image extensions
- [ ] Test file content scanning for suspicious patterns
- [ ] Verify secure filename generation

### 5. Security Headers Testing

Use browser developer tools or online tools like [Security Headers](https://securityheaders.com/) to verify:

#### Required Headers

- [ ] `X-Frame-Options: SAMEORIGIN`
- [ ] `X-XSS-Protection: 1; mode=block`
- [ ] `X-Content-Type-Options: nosniff`
- [ ] `Referrer-Policy: strict-origin-when-cross-origin`
- [ ] `Content-Security-Policy` with appropriate directives

#### HTTPS-Only Headers (if SSL enabled)

- [ ] `Strict-Transport-Security` header
- [ ] `Expect-CT` header

#### Cross-Origin Headers

- [ ] `Cross-Origin-Embedder-Policy: require-corp`
- [ ] `Cross-Origin-Opener-Policy: same-origin`
- [ ] `Cross-Origin-Resource-Policy: same-origin`

### 6. WordPress Core Hardening Testing

#### Version Information Hiding

- [ ] Check page source for WordPress version (should be hidden)
- [ ] Verify RSS feeds don't expose version
- [ ] Test admin footer version removal
- [ ] Check script/style URLs for version parameters

#### User Enumeration Prevention

- [ ] Try accessing `/?author=1` (should redirect)
- [ ] Test REST API user endpoints `/wp-json/wp/v2/users`
- [ ] Verify author archive blocking for non-logged users

#### XMLRPC Security

- [ ] Test XMLRPC endpoint availability (should be disabled if configured)
- [ ] Verify pingback method removal
- [ ] Test brute force delay on XMLRPC

#### File Editing Prevention

- [ ] Check if theme/plugin editor is disabled in admin
- [ ] Verify `DISALLOW_FILE_EDIT` constant

### 7. Performance Impact Testing

#### Page Load Speed

- [ ] Measure page load times with security features enabled
- [ ] Test impact of security headers on performance
- [ ] Verify heartbeat modifications don't break functionality

#### Resource Usage

- [ ] Monitor server resource usage with security features
- [ ] Test impact of login attempt tracking
- [ ] Verify file upload processing performance

## Testing Tools

### Browser-Based Testing

- Browser Developer Tools (Network, Security tabs)
- [Security Headers Checker](https://securityheaders.com/)
- [SSL Labs SSL Test](https://www.ssllabs.com/ssltest/)

### Command Line Testing

```bash
# Test security headers
curl -I https://yoursite.com

# Test specific headers
curl -H "X-Forwarded-For: malicious-ip" https://yoursite.com

# Test file upload
curl -X POST -F "file=@test.php" https://yoursite.com/wp-admin/admin-ajax.php
```

### WordPress-Specific Testing

- [WP Security Audit Log](https://wordpress.org/plugins/wp-security-audit-log/)
- [Wordfence Security Scanner](https://wordpress.org/plugins/wordfence/)
- [Security Ninja](https://wordpress.org/plugins/security-ninja/)

## Security Dashboard

The theme includes a security dashboard widget in the WordPress admin that shows:

- Total users and active sessions
- Number of locked accounts
- HTTPS status
- Security feature status

Access this via Dashboard → Security Status widget.

## Common Issues and Solutions

### False Positives

- Legitimate users getting locked out: Adjust `HOG_SCAFFOLD_RATE_LIMIT_ATTEMPTS`
- CSP blocking legitimate resources: Update CSP directives in `get_content_security_policy()`

### Performance Issues

- Slow page loads: Review security header implementation
- High server load: Check login attempt logging frequency

### Compatibility Issues

- Plugin conflicts: Test with security features individually
- Theme conflicts: Verify hooks and filters don't interfere

## Security Configuration

Security features can be configured via the `hog_scaffold_security_features` option:

```php
$security_features = array(
    'disable_xmlrpc' => true,
    'log_failed_logins' => true,
    'enforce_strong_passwords' => true,
    'limit_login_attempts' => true,
    'security_headers' => true,
    'file_upload_security' => true,
);
```

## Reporting Security Issues

If you discover security vulnerabilities during testing:

1. Document the issue with steps to reproduce
2. Assess the severity and potential impact
3. Create a fix or mitigation strategy
4. Test the fix thoroughly
5. Update this documentation if needed

## Regular Security Maintenance

- Review security logs weekly
- Update security configurations as needed
- Test security features after WordPress/plugin updates
- Monitor for new security threats and update accordingly

---

**Note**: This testing guide should be updated as new security features are added or existing ones are modified.
