[Documentation](../README.md) > [Deployment](README.md) > Deployment Guide

# WPEngine Deployment Guide

This guide covers deploying the HOG Scaffold WordPress theme to WPEngine hosting environments.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Environment Setup](#environment-setup)
- [Deployment Process](#deployment-process)
- [Staging Deployment](#staging-deployment)
- [Production Deployment](#production-deployment)
- [Cache Management](#cache-management)
- [Rollback Procedures](#rollback-procedures)
- [Troubleshooting](#troubleshooting)
- [Monitoring](#monitoring)

## Prerequisites

### Required Tools

- Node.js (version 18+)
- npm or yarn
- Composer
- Git
- WP-CLI (optional but recommended)

### Required Access

- WPEngine account with API access
- Git repository access
- Staging and production environment URLs

### Environment Variables

Create a `.env` file in your project root with the following variables:

```bash
# WPEngine Configuration
WPENGINE_INSTALL_NAME=your-install-name
WPENGINE_ENV=staging
WPENGINE_API_USER=your-api-user
WPENGINE_API_PASSWORD=your-api-password

# Site URLs
SITE_URL=https://your-site.com
STAGING_URL=https://your-staging-site.wpengine.com
PRODUCTION_URL=https://your-production-site.com

# CDN Configuration (optional)
CDN_URL=https://your-cdn.com
```

## Environment Setup

### 1. Install Dependencies

```bash
# Install Node.js dependencies
npm install

# Install PHP dependencies
composer install
```

### 2. Build Assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

### 3. Configure WPEngine Settings

The theme includes automatic WPEngine configuration in `inc/wpengine-config.php` that handles:

- Cache headers
- Object caching
- CDN integration
- Security headers

## Deployment Process

### Build Process

The deployment scripts automatically:

1. **Run Tests**: Linting and build validation
2. **Build Assets**: Compile and optimize CSS/JS
3. **Create Package**: Copy necessary files to deployment directory
4. **Clear Cache**: Clear WPEngine cache after deployment

### Deployment Commands

```bash
# Deploy to staging
npm run deploy:staging

# Deploy to production
npm run deploy:production
```

## Staging Deployment

### Automated Staging Deployment

```bash
npm run deploy:staging
```

This command:

1. Builds production assets
2. Creates a staging deployment package
3. Deploys to staging environment
4. Clears staging cache

### Manual Staging Deployment

```bash
# Build assets
npm run build

# Run deployment script
chmod +x scripts/deploy-staging.sh
./scripts/deploy-staging.sh
```

### Staging Environment Features

- Debug logging enabled
- Staging banner for logged-in users
- Search engine indexing disabled
- Admin notices indicating staging environment

## Production Deployment

### Pre-Production Checklist

- [ ] Staging environment tested and approved
- [ ] All tests passing (`npm run test`)
- [ ] Assets built for production (`npm run build`)
- [ ] Database backup created
- [ ] Deployment window scheduled
- [ ] Team notified of deployment

### Automated Production Deployment

```bash
npm run deploy:production
```

This command includes safety prompts:

1. Confirms production deployment intent
2. Verifies staging testing completion
3. Runs full test suite
4. Deploys to production
5. Clears production cache

### Manual Production Deployment

```bash
# Build assets
npm run build

# Run deployment script
chmod +x scripts/deploy-production.sh
./scripts/deploy-production.sh
```

### Production Environment Features

- Debug mode disabled
- File editing disabled
- Security headers enabled
- Automatic minor updates enabled
- WordPress version number removed

## Cache Management

### Cache Types

The theme manages multiple cache layers:

1. **Object Cache**: Redis-based (managed by WPEngine)
2. **Page Cache**: Full-page caching
3. **CDN Cache**: Content delivery network cache

### Cache Control

```bash
# Clear all cache
npm run clear:cache

# Clear specific cache types
npm run clear:cache:object
npm run clear:cache:page
npm run clear:cache:cdn

# Warm up cache
npm run cache:warmup
```

### Manual Cache Clearing

From WordPress admin:

- Admin bar → "🧹 Clear Cache"
- Or programmatically: `hog_scaffold_clear_cache()`

### Cache Headers

The theme sets appropriate cache headers:

- Front page: 1 hour
- Posts/pages: 24 hours
- Archive pages: 6 hours
- Admin pages: No cache

## Rollback Procedures

### Quick Rollback

If issues occur after deployment:

1. **Immediate Rollback**:

   ```bash
   # Rollback to previous version via WPEngine control panel
   # Or restore from backup
   ```

2. **Clear All Cache**:

   ```bash
   npm run clear:cache
   ```

3. **Verify Functionality**:
   - Test critical pages
   - Check error logs
   - Monitor performance

### Git-Based Rollback

```bash
# Revert to previous commit
git revert HEAD

# Redeploy
npm run deploy:production
```

### Database Rollback

If database changes were made:

1. Restore database from backup
2. Clear object cache
3. Verify data integrity

## Troubleshooting

### Common Issues

#### Deployment Fails

**Error**: Permission denied during file copy
**Solution**: Check file permissions and directory ownership

```bash
# Fix permissions
chmod -R 755 build/
chmod +x scripts/*.sh
```

#### Cache Not Clearing

**Error**: Cache persists after clearing
**Solution**: Verify API credentials and clear manually

```bash
# Check environment variables
echo $WPENGINE_API_USER
echo $WPENGINE_INSTALL_NAME

# Clear cache manually via WPEngine control panel
```

#### Assets Not Loading

**Error**: CSS/JS files return 404
**Solution**: Verify build process and file paths

```bash
# Rebuild assets
npm run clean
npm run build

# Check file existence
ls -la build/
```

#### Performance Issues

**Error**: Slow page load times
**Solution**: Check cache configuration and CDN

```bash
# Verify cache headers
curl -I https://your-site.com

# Test CDN configuration
curl -I https://your-cdn.com/assets/css/style.css
```

### Log Files

Check these log files for errors:

- **PHP Errors**: `/wp-content/debug.log`
- **Server Errors**: WPEngine control panel → Error Logs
- **Build Errors**: Console output during `npm run build`

### Debug Mode

Enable debug mode for troubleshooting:

```php
// In wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

## Monitoring

### Post-Deployment Monitoring

After each deployment, monitor:

1. **Site Availability**: Verify all pages load correctly
2. **Performance**: Check Core Web Vitals
3. **Error Logs**: Monitor for PHP/JavaScript errors
4. **Cache Hit Rates**: Verify caching is working

### Performance Monitoring

```bash
# Test performance
npm run performance-audit

# Analyze bundles
npm run bundle-analyzer
```

### Health Checks

Set up monitoring for:

- Page response times
- Error rates
- Cache hit ratios
- Database performance

### Automated Monitoring

Consider integrating with:

- Google PageSpeed Insights API
- New Relic
- Datadog
- WordPress.com VIP monitoring

## Best Practices

### Pre-Deployment

1. Always test on staging first
2. Run full test suite
3. Create database backup
4. Notify team of deployment window

### During Deployment

1. Monitor deployment progress
2. Verify each step completes successfully
3. Test critical functionality immediately

### Post-Deployment

1. Clear all cache types
2. Test site functionality
3. Monitor error logs
4. Verify performance metrics

### Rollback Criteria

Rollback immediately if:

- Site becomes unavailable
- Critical functionality breaks
- Performance degrades significantly
- Security issues are discovered

## Support

For deployment issues:

1. Check this documentation
2. Review error logs
3. Contact WPEngine support
4. Escalate to development team

---

**Note**: This deployment process is designed for WPEngine hosting. Adjust scripts and procedures if using different hosting providers.

## See Also

- [Deployment Overview](README.md) - Deployment section overview
- [Build System Guide](../getting-started/build-system.md) - Build configuration and scripts
- [Security Guide](../security/security-guide.md) - Security best practices
- [Troubleshooting](../troubleshooting/README.md) - Common deployment issues

**[⬅️ Back to Documentation Index](../README.md)** | **[➡️ Next: Security](../security/README.md)**
