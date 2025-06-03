[Documentation](../README.md) > [Getting Started](README.md) > Installation Guide

# Installation Guide

This guide walks you through setting up the House of Giants WordPress Theme Scaffold for local development and production deployment.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Local Development Setup](#local-development-setup)
- [Installing Dependencies](#installing-dependencies)
- [Configuration](#configuration)
- [First Build](#first-build)
- [WordPress Setup](#wordpress-setup)
- [Verification](#verification)
- [Troubleshooting](#troubleshooting)

## Prerequisites

Before installing the theme, ensure you have the following tools installed:

### Required Software

#### Node.js (Version 18.0.0 or higher)

```bash
# Check your Node.js version
node --version

# Check npm version
npm --version
```

**Installation:**

- **macOS**: `brew install node` or download from [nodejs.org](https://nodejs.org/)
- **Windows**: Download installer from [nodejs.org](https://nodejs.org/)
- **Linux**: Use your package manager or [NodeSource repository](https://github.com/nodesource/distributions)

#### PHP (Version 8.0 or higher)

```bash
# Check PHP version
php --version
```

**Installation:**

- **macOS**: `brew install php` or use MAMP/XAMPP
- **Windows**: Use XAMPP, WAMP, or install directly
- **Linux**: `sudo apt install php` (Ubuntu/Debian) or equivalent

#### Composer

```bash
# Check Composer version
composer --version
```

**Installation:**

- Download from [getcomposer.org](https://getcomposer.org/)
- Follow platform-specific installation instructions

#### Git

```bash
# Check Git version
git --version
```

**Installation:**

- **macOS**: `brew install git` or Xcode Command Line Tools
- **Windows**: Download from [git-scm.com](https://git-scm.com/)
- **Linux**: Use your package manager

### Development Environment Options

Choose one of the following development environments:

#### Option 1: Local WordPress Installation

- MAMP/XAMPP/WAMP for local server
- Direct WordPress installation
- Manual database management

#### Option 2: Docker (Recommended)

- Docker Desktop installed
- docker-compose available
- Isolated development environment

#### Option 3: WordPress VIP Local Development

- VIP CLI tools installed
- WordPress VIP environment setup

## Local Development Setup

### Method 1: Manual WordPress Setup

1. **Download WordPress**

   ```bash
   # Download latest WordPress
   wget https://wordpress.org/latest.zip
   unzip latest.zip
   cd wordpress
   ```

2. **Database Setup**
   Create a MySQL database for your development site:

   ```sql
   CREATE DATABASE hog_scaffold_dev;
   CREATE USER 'hog_user'@'localhost' IDENTIFIED BY 'secure_password';
   GRANT ALL PRIVILEGES ON hog_scaffold_dev.* TO 'hog_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

3. **WordPress Configuration**

   ```bash
   # Copy sample config
   cp wp-config-sample.php wp-config.php

   # Edit wp-config.php with your database details
   ```

### Method 2: Docker Setup (Recommended)

1. **Create docker-compose.yml**

   ```yaml
   version: "3.8"

   services:
     wordpress:
       image: wordpress:latest
       ports:
         - "8080:80"
       environment:
         WORDPRESS_DB_HOST: db:3306
         WORDPRESS_DB_USER: wordpress
         WORDPRESS_DB_PASSWORD: wordpress
         WORDPRESS_DB_NAME: wordpress
       volumes:
         - ./wp-content/themes/hog-scaffold:/var/www/html/wp-content/themes/hog-scaffold
       depends_on:
         - db

     db:
       image: mysql:8.0
       environment:
         MYSQL_DATABASE: wordpress
         MYSQL_USER: wordpress
         MYSQL_PASSWORD: wordpress
         MYSQL_ROOT_PASSWORD: rootpassword
       volumes:
         - db_data:/var/lib/mysql

   volumes:
     db_data:
   ```

2. **Start Development Environment**

   ```bash
   # Start containers
   docker-compose up -d

   # Check container status
   docker-compose ps
   ```

## Installing Dependencies

### 1. Clone Theme Repository

```bash
# Clone the theme repository
git clone https://github.com/your-org/hog-scaffold.git
cd hog-scaffold

# Or if using as a WordPress theme
cd wp-content/themes/
git clone https://github.com/your-org/hog-scaffold.git
cd hog-scaffold
```

### 2. Install Node.js Dependencies

```bash
# Install all npm dependencies
npm install

# Alternative: Use exact versions from lockfile
npm ci
```

**Expected output:**

```
added 1247 packages, and audited 1248 packages in 45s
found 0 vulnerabilities
```

### 3. Install PHP Dependencies

```bash
# Install Composer dependencies
composer install

# Alternative: Use exact versions from lockfile
composer install --no-dev --optimize-autoloader
```

**Expected output:**

```
Loading composer repositories with package information
Installing dependencies (including require-dev) from lock file
Package operations: 15 installs, 0 updates, 0 removals
```

### 4. Verify Installation

```bash
# Check that all tools are available
npm --version
composer --version
php --version

# Verify theme dependencies
ls -la node_modules/
ls -la vendor/
```

## Configuration

### 1. Environment Variables

Create a `.env` file in the theme root:

```bash
# Copy example environment file
cp .env.example .env

# Edit with your specific settings
nano .env
```

**Example .env configuration:**

```env
# Development Configuration
NODE_ENV=development
WP_DEBUG=true
WP_DEBUG_LOG=true

# Database Configuration (if using local setup)
DB_NAME=hog_scaffold_dev
DB_USER=hog_user
DB_PASSWORD=secure_password
DB_HOST=localhost

# API Keys (optional for development)
GOOGLE_FONTS_API_KEY=your_api_key_here
ANALYTICS_ID=UA-XXXXXXXXX-X

# Build Configuration
BUILD_ANALYZER=false
SOURCE_MAPS=true
```

### 2. Theme Configuration

The theme uses `theme.json` for design system configuration. Review and customize:

```bash
# Review theme configuration
cat theme.json
```

**Key configuration areas:**

- Color palette
- Typography scale
- Spacing system
- Layout settings
- Block supports

### 3. Build Configuration

Review webpack settings:

```bash
# Check webpack configuration
ls -la config/
cat config/webpack.settings.js
```

**Customize build settings** if needed:

- Entry points for CSS/JS
- Asset output paths
- Development server settings
- Optimization options

## First Build

### 1. Development Build

```bash
# Run development build
npm run dev

# Expected output
> hog-scaffold@1.0.0 dev
> webpack --mode development --config config/webpack.dev.js

webpack 5.88.0 compiled successfully in 2847 ms
```

### 2. Watch Mode for Development

```bash
# Start development with file watching
npm run watch

# This will:
# - Build assets in development mode
# - Watch for file changes
# - Rebuild automatically when files change
# - Provide live reload (if configured)
```

### 3. Production Build

```bash
# Build optimized assets for production
npm run build

# Expected output shows minified, optimized files
```

### 4. Verify Build Output

```bash
# Check that assets were built
ls -la build/css/
ls -la build/js/
ls -la build/images/

# Verify file sizes are reasonable
du -sh build/*
```

## WordPress Setup

### 1. Activate Theme

1. **Via WordPress Admin:**

   - Navigate to Appearance > Themes
   - Find "House of Giants Scaffold"
   - Click "Activate"

2. **Via WP-CLI (if available):**

   ```bash
   # Activate the theme
   wp theme activate hog-scaffold

   # Verify activation
   wp theme list
   ```

### 2. Initial Theme Setup

The theme will automatically:

- Register navigation menus
- Set up custom post types
- Configure theme supports
- Initialize security features

### 3. Import Demo Content (Optional)

```bash
# If demo content is available
wp import demo-content.xml

# Or use the WordPress admin:
# Tools > Import > WordPress > Choose file
```

### 4. Configure Theme Options

1. **Customizer Setup:**

   - Navigate to Appearance > Customize
   - Configure site identity, colors, typography
   - Set up navigation menus
   - Configure widgets

2. **Block Editor Setup:**
   - Create a new page/post
   - Verify custom blocks are available
   - Test block patterns functionality

## Verification

### 1. Development Environment Check

```bash
# Run development server
npm run watch

# In another terminal, check the site
curl -I http://localhost:8080
```

**Expected response:**

```
HTTP/1.1 200 OK
Content-Type: text/html; charset=UTF-8
```

### 2. Asset Loading Verification

1. **Check CSS Loading:**

   - View page source
   - Verify CSS files are loading from `/build/css/`
   - Check for proper cache-busting filenames

2. **Check JavaScript Loading:**
   - Open browser developer tools
   - Verify JS files load without errors
   - Check for proper functionality (navigation, forms, etc.)

### 3. Block Editor Functionality

1. **Test Custom Blocks:**

   - Create new page/post
   - Insert each custom block type
   - Verify blocks render correctly
   - Test block variations

2. **Test Block Patterns:**
   - Open block inserter
   - Navigate to "Patterns" tab
   - Insert theme patterns
   - Verify proper rendering

### 4. Performance Check

```bash
# Run performance audit
npm run performance-audit

# Or use Lighthouse in browser dev tools
```

**Target metrics:**

- Performance: 90+
- Accessibility: 100
- Best Practices: 90+
- SEO: 90+

## Troubleshooting

### Common Installation Issues

#### Node.js Version Conflicts

**Problem:** Build fails with Node.js version errors
**Solution:**

```bash
# Use Node Version Manager (nvm)
nvm install 18
nvm use 18

# Or update Node.js directly
npm install -g n
n stable
```

#### Permission Errors

**Problem:** npm install fails with permission errors
**Solution:**

```bash
# Fix npm permissions (macOS/Linux)
sudo chown -R $(whoami) ~/.npm

# Or use npx instead of global installs
npx create-react-app instead of npm install -g create-react-app
```

#### Composer Dependencies Fail

**Problem:** Composer install fails with version conflicts
**Solution:**

```bash
# Clear Composer cache
composer clear-cache

# Update Composer
composer self-update

# Install with specific PHP version
/usr/bin/php7.4 /usr/local/bin/composer install
```

#### Build Process Fails

**Problem:** webpack build fails with module errors
**Solution:**

```bash
# Clear node_modules and reinstall
rm -rf node_modules package-lock.json
npm install

# Clear webpack cache
rm -rf node_modules/.cache

# Check for specific error messages and resolve dependencies
```

#### WordPress Theme Not Recognized

**Problem:** Theme doesn't appear in WordPress admin
**Solution:**

```bash
# Check theme structure
ls -la wp-content/themes/hog-scaffold/

# Verify style.css has proper theme header
head -20 style.css

# Check file permissions
chmod -R 755 wp-content/themes/hog-scaffold/
```

### Getting Help

If you encounter issues not covered here:

1. **Check the logs:**

   ```bash
   # Check npm debug log
   npm config get cache
   ls ~/.npm/_logs/

   # Check PHP error log
   tail -f /path/to/php/error.log

   # Check WordPress debug log
   tail -f wp-content/debug.log
   ```

2. **Review documentation:**

   - [Build System Guide](build-system.md)
   - [Troubleshooting Guide](../troubleshooting/README.md)
   - [Security Guide](../security/security-guide.md)

3. **Community support:**
   - Check GitHub issues
   - Review WordPress.org forums
   - Consult Stack Overflow

## Next Steps

After successful installation:

1. **Review the [Build System Guide](build-system.md)** for development workflow
2. **Explore [Block Development Guide](../blocks/block-development-guide.md)** for custom blocks
3. **Check [Customization guides](../customization/README.md)** for theme customization
4. **Set up [Deployment workflow](../deployment/deployment-guide.md)** for production

## See Also

- [Getting Started Overview](README.md) - Overview of getting started resources
- [Build System Guide](build-system.md) - Development workflow and build process
- [Theme Features Overview](../theme-features-overview.md) - Complete feature overview
- [Troubleshooting](../troubleshooting/README.md) - Common issues and solutions

---

**[⬅️ Back to Getting Started](README.md)** | **[➡️ Next: Build System](build-system.md)**
