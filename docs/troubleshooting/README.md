[Documentation](../README.md) > Troubleshooting

# Troubleshooting

This section provides comprehensive guides for diagnosing and resolving common issues encountered during WordPress theme development with the House of Giants scaffold.

## Quick Help

**Experiencing issues?** Start here:

1. **[Common Issues](common-issues.md)** - Solutions to frequently encountered problems
2. **Check build process** - Run `npm run build` and look for errors
3. **Verify WordPress integration** - Check theme appears in admin
4. **Review error logs** - Check browser console and WordPress debug log

## Troubleshooting Guides

### Installation and Setup

- **[Common Issues](common-issues.md)** - Comprehensive solutions guide
  - Node.js version conflicts and npm permission errors
  - Composer installation failures and dependency issues
  - Build system problems and webpack failures
  - WordPress integration and theme recognition issues

### Development Issues

- **[Build System Debugging](common-issues.md#build-system-problems)** - Asset compilation problems
  - Webpack build failures and module resolution
  - CSS compilation issues and PostCSS errors
  - JavaScript compilation and Babel transpilation
  - Asset optimization and performance problems

- **[WordPress Integration](common-issues.md#wordpress-integration-issues)** - Theme and CMS connectivity
  - Theme not appearing in WordPress admin
  - Functions.php errors and PHP issues
  - Database connection problems
  - Plugin compatibility conflicts

### Block Editor and Blocks

- **[Block Development Issues](common-issues.md#block-editor-problems)** - Custom block troubleshooting
  - Custom blocks not loading in editor
  - Block validation errors and content warnings
  - Block patterns not working correctly
  - Editor JavaScript errors and registration issues

### Styling and Frontend

- **[CSS and Styling Problems](common-issues.md#styling-and-css-issues)** - Visual and layout issues
  - Styles not applying or being overridden
  - Theme.json configuration not working
  - Responsive design problems
  - Specificity and cascade conflicts

### Performance and Optimization

- **[Performance Issues](common-issues.md#performance-issues)** - Speed and optimization
  - Slow page load times and high TTFB
  - Large JavaScript bundles and code splitting
  - CSS performance and unused styles
  - Image optimization and caching

### Security and Permissions

- **[Security and Permissions](common-issues.md#security-and-permissions)** - Access and security
  - File permission issues and upload problems
  - Security headers missing
  - Authentication and authorization errors
  - Vulnerability scanning and hardening

## Debug Techniques

### WordPress Debug Mode

Enable comprehensive debugging in your development environment:

```php
// Add to wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);
define('SAVEQUERIES', true);
```

### Build System Debugging

```bash
# Verbose build output
npm run build -- --verbose

# Clear all caches
npm run clean
rm -rf node_modules/.cache

# Check for specific issues
npm audit
npm ls
```

### Browser Development Tools

**Essential browser tools for debugging:**

1. **Elements Panel** - Inspect HTML and CSS
2. **Console Panel** - JavaScript errors and debugging
3. **Network Panel** - Asset loading and AJAX requests
4. **Performance Panel** - Runtime performance analysis
5. **Application Panel** - Storage, caching, and service workers

### Log File Locations

Common log files to monitor:

```bash
# WordPress debug log
tail -f wp-content/debug.log

# PHP error log
tail -f /var/log/php/error.log

# Web server error log
tail -f /var/log/apache2/error.log
# or
tail -f /var/log/nginx/error.log

# npm debug logs
ls ~/.npm/_logs/
```

## Error Message Reference

### Common Error Patterns

| Error Pattern | Likely Cause | Quick Fix |
|---------------|--------------|-----------|
| "Module not found" | Missing dependency or incorrect import | Check package.json and file paths |
| "Permission denied" | File system permissions | Fix file ownership and permissions |
| "Fatal error in functions.php" | PHP syntax error | Check PHP syntax with `php -l` |
| "This block contains unexpected content" | Block validation failure | Check save function consistency |
| "Cannot resolve dependency" | Version conflict | Update package versions |

### WordPress Specific Errors

| Error Message | Cause | Solution |
|---------------|-------|----------|
| "The theme is missing the style.css stylesheet" | Missing or malformed style.css header | Add proper theme header to style.css |
| "Parse error: syntax error in functions.php" | PHP syntax error | Check for missing semicolons, brackets |
| "Fatal error: Call to undefined function" | Missing function or plugin | Check function exists and plugin active |
| "Headers already sent" | Output before header() calls | Remove whitespace/output before PHP tags |

## Prevention Strategies

### Code Quality

- **Use linting tools** - ESLint, Stylelint, PHP CodeSniffer
- **Follow coding standards** - WordPress coding standards
- **Version control** - Commit frequently with meaningful messages
- **Testing** - Test across browsers and devices

### Development Environment

- **Use stable Node.js versions** - LTS releases recommended
- **Keep dependencies updated** - Regular `npm audit` and updates
- **Environment parity** - Match production environment
- **Backup regularly** - Database and file backups

### Documentation

- **Document customizations** - Keep track of changes
- **Error tracking** - Log and categorize issues
- **Solution database** - Build knowledge base
- **Team communication** - Share solutions with team

## Getting Help

### Internal Resources

- **[Installation Guide](../getting-started/installation-guide.md)** - Setup and configuration help
- **[Build System Guide](../getting-started/build-system.md)** - Development workflow support
- **[Security Guide](../security/security-guide.md)** - Security-related troubleshooting
- **[Block Development Guide](../blocks/block-development-guide.md)** - Block creation help

### Community Support

- **GitHub Issues** - Report bugs and request features
- **WordPress Slack** - Real-time community support
- **WordPress Forums** - General WordPress development help
- **Stack Overflow** - Technical programming questions

### Professional Support

- **WordPress VIP** - Enterprise WordPress hosting and support
- **WP Engine** - Managed WordPress hosting with support
- **Freelance Developers** - Hire WordPress specialists
- **Consultancy** - Professional WordPress consulting services

## Information to Provide When Seeking Help

When reporting issues, include:

### System Information
```bash
echo "Node.js: $(node --version)"
echo "npm: $(npm --version)"
echo "PHP: $(php --version)"
echo "WordPress: $(wp core version)"
echo "Theme: $(wp theme list --status=active --field=name)"
```

### Error Details
- **Exact error message** - Copy/paste complete error
- **When it occurs** - Steps to reproduce
- **Expected behavior** - What should happen
- **Environment** - Browser, OS, server details

### Debugging Output
- **Browser console logs** - JavaScript errors
- **WordPress debug log** - PHP errors and warnings
- **Build output** - npm/webpack error messages
- **Network requests** - Failed AJAX or asset requests

## See Also

- **[Common Issues](common-issues.md)** - Complete problem-solving guide
- **[Installation Guide](../getting-started/installation-guide.md)** - Setup troubleshooting
- **[Security Guide](../security/security-guide.md)** - Security issue resolution
- **[Contributing Guidelines](../CONTRIBUTING.md)** - How to report bugs effectively

---

**[⬅️ Back to Documentation Index](../README.md)** | **[➡️ Next: Common Issues](common-issues.md)** 