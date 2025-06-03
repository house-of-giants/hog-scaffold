#!/bin/bash

# Deploy to WPEngine Production Environment
# Usage: ./scripts/deploy-production.sh

set -e  # Exit on any error

echo "🚀 Starting deployment to PRODUCTION environment..."

# Check if we're in the correct directory
if [ ! -f "package.json" ]; then
    echo "❌ Error: package.json not found. Please run this script from the theme root directory."
    exit 1
fi

# Safety check - confirm production deployment
echo "⚠️  You are about to deploy to PRODUCTION environment."
read -p "Are you sure you want to continue? (y/N) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Deployment cancelled."
    exit 1
fi

# Check if staging was tested
echo "⚠️  Have you tested the staging environment?"
read -p "Confirm staging has been tested and approved (y/N) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Please test staging environment first."
    exit 1
fi

# Run tests before deployment
echo "🧪 Running tests..."
npm run test

# Build for production
echo "📦 Building assets for production..."
npm run build

# Clear any existing production package
echo "🧹 Cleaning production package directory..."
rm -rf production-package
mkdir -p production-package

# Create deployable package for production
echo "📋 Creating production deployment package..."
cp -R build production-package/
cp -R inc production-package/
cp -R parts production-package/
cp -R patterns production-package/
cp -R templates production-package/
cp -R languages production-package/
cp -R assets/images production-package/images 2>/dev/null || echo "No images directory found"
cp -R assets/fonts production-package/fonts 2>/dev/null || echo "No fonts directory found"

# Copy theme files
cp style.css production-package/
cp functions.php production-package/
cp index.php production-package/
cp theme.json production-package/
cp manifest.json production-package/
cp screenshot.png production-package/
cp README.md production-package/

# Copy any other PHP files that might exist
cp *.php production-package/ 2>/dev/null || echo "No additional PHP files found"

# Create a backup timestamp
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
echo "📋 Deployment timestamp: $TIMESTAMP"

# Clear production cache
echo "🧹 Clearing production cache..."
# Add your WPEngine production cache clearing commands here
# Example: wp-cli commands or WPEngine API calls

# Deploy to production environment
echo "📤 Deploying to production..."
# Add your production deployment commands here
# This could be:
# - git push to production branch
# - rsync to production server
# - WPEngine git deployment
# - FTP/SFTP upload

# Warm up the cache after deployment
echo "🔥 Warming up production cache..."
# Add cache warming commands here if needed

echo "✅ Production deployment complete!"
echo "📝 Deployment timestamp: $TIMESTAMP"
echo "🔗 Production URL: [Add your production URL here]"
echo "📊 Please monitor performance and error logs."

# Optional: Open production site in browser
# open "https://your-production-site.com"

# Optional: Send deployment notification
# curl -X POST -H 'Content-type: application/json' \
#   --data '{"text":"🚀 Production deployment complete at '$TIMESTAMP'"}' \
#   YOUR_SLACK_WEBHOOK_URL 