#!/bin/bash

# Deploy to WPEngine Staging Environment
# Usage: ./scripts/deploy-staging.sh

set -e  # Exit on any error

echo "🚀 Starting deployment to staging environment..."

# Check if we're in the correct directory
if [ ! -f "package.json" ]; then
    echo "❌ Error: package.json not found. Please run this script from the theme root directory."
    exit 1
fi

# Build for staging
echo "📦 Building assets for staging..."
npm run build

# Clear any existing staging package
echo "🧹 Cleaning staging package directory..."
rm -rf staging-package
mkdir -p staging-package

# Create deployable package for staging
echo "📋 Creating staging deployment package..."
cp -R build staging-package/
cp -R inc staging-package/
cp -R parts staging-package/
cp -R patterns staging-package/
cp -R templates staging-package/
cp -R languages staging-package/
cp -R assets/images staging-package/images 2>/dev/null || echo "No images directory found"
cp -R assets/fonts staging-package/fonts 2>/dev/null || echo "No fonts directory found"

# Copy theme files
cp style.css staging-package/
cp functions.php staging-package/
cp index.php staging-package/
cp theme.json staging-package/
cp manifest.json staging-package/
cp screenshot.png staging-package/
cp README.md staging-package/

# Copy any other PHP files that might exist
cp *.php staging-package/ 2>/dev/null || echo "No additional PHP files found"

# Clear staging cache (placeholder for WPEngine API calls)
echo "🧹 Clearing staging cache..."
# Add your WPEngine staging cache clearing commands here
# Example: wp-cli commands or WPEngine API calls

# Deploy to staging environment
echo "📤 Deploying to staging..."
# Add your staging deployment commands here
# This could be:
# - git push to staging branch
# - rsync to staging server
# - WPEngine git deployment
# - FTP/SFTP upload

echo "✅ Staging deployment complete!"
echo "📝 Please test the staging environment before deploying to production."
echo "🔗 Staging URL: [Add your staging URL here]"

# Optional: Open staging site in browser
# open "https://your-staging-site.wpengine.com" 