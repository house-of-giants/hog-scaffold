#!/bin/bash

# Post-deployment script for WP Engine
# This script runs after deployment to perform cleanup and optimization tasks

set -e

echo "🚀 Running post-deployment script..."

# Get the theme directory
THEME_DIR="/var/www/html/wp-content/themes/hog-scaffold"

# Change to theme directory
cd "$THEME_DIR"

# Clear WordPress caches
echo "🧹 Clearing WordPress caches..."

# Clear object cache if Redis is available
if command -v redis-cli &> /dev/null; then
    echo "Clearing Redis object cache..."
    redis-cli flushall
fi

# Clear opcache if available
if command -v wp &> /dev/null; then
    echo "Clearing OPcache via WP-CLI..."
    wp cache flush
    wp rewrite flush
else
    echo "WP-CLI not available, skipping cache flush"
fi

# Set proper file permissions for WP Engine
echo "🔐 Setting file permissions..."
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

# Make specific scripts executable if they exist
[ -f "scripts/clear-cache.cjs" ] && chmod +x "scripts/clear-cache.cjs"
[ -f "scripts/cache-management.php" ] && chmod +x "scripts/cache-management.php"

# Clean up any development artifacts that might have been deployed
echo "🧽 Cleaning up development artifacts..."
rm -rf node_modules/ 2>/dev/null || true
rm -rf .git/ 2>/dev/null || true
rm -rf .github/ 2>/dev/null || true
rm -rf tests/ 2>/dev/null || true
rm -rf .taskmaster/ 2>/dev/null || true
rm -f package.json 2>/dev/null || true
rm -f package-lock.json 2>/dev/null || true
rm -f composer.json 2>/dev/null || true
rm -f composer.lock 2>/dev/null || true
rm -f .env 2>/dev/null || true
rm -f .env.* 2>/dev/null || true

# Warm up cache by requesting key pages (if WP-CLI is available)
if command -v wp &> /dev/null && command -v curl &> /dev/null; then
    echo "🔥 Warming up cache..."
    
    # Get site URL
    SITE_URL=$(wp option get siteurl 2>/dev/null || echo "")
    
    if [ ! -z "$SITE_URL" ]; then
        echo "Warming up homepage..."
        curl -s "$SITE_URL" > /dev/null || true
        
        echo "Warming up key pages..."
        curl -s "$SITE_URL/about" > /dev/null || true
        curl -s "$SITE_URL/contact" > /dev/null || true
        
        # Warm up recent posts
        POST_URLS=$(wp post list --post_type=post --posts_per_page=5 --field=url 2>/dev/null || echo "")
        for url in $POST_URLS; do
            curl -s "$url" > /dev/null || true
        done
    fi
fi

# Log deployment completion
echo "✅ Post-deployment script completed successfully at $(date)"

# Optional: Send completion notification
if [ ! -z "$SLACK_WEBHOOK_URL" ]; then
    curl -X POST -H 'Content-type: application/json' \
        --data '{"text":"✅ Post-deployment script completed for '$WPE_ENV'"}' \
        "$SLACK_WEBHOOK_URL" || true
fi

echo "🎉 Deployment process complete!" 