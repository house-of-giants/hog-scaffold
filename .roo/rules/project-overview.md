---
description: 
globs: 
alwaysApply: true
---
# WordPress Theme Scaffold Project Overview

This is the **House of Giants WordPress Theme Scaffold** - a modern WordPress theme development framework that uses Webpack for asset processing and follows WordPress coding standards.

## Project Structure

### Core Files
- [functions.php](mdc:functions.php) - Main theme functions and WordPress hooks
- [style.css](mdc:style.css) - Theme stylesheet header (actual styles compiled from assets)
- [index.php](mdc:index.php) - Main template file
- [header.php](mdc:header.php) - Theme header template
- [footer.php](mdc:footer.php) - Theme footer template

### Configuration Files
- [package.json](mdc:package.json) - NPM dependencies and build scripts
- [composer.json](mdc:composer.json) - PHP dependencies and autoloading
- [webpack.settings.js](mdc:config/webpack.settings.js) - Main Webpack configuration
- [webpack.shared.js](mdc:config/webpack.shared.js) - Shared Webpack configuration
- [webpack.dev.js](mdc:config/webpack.dev.js) - Development Webpack configuration
- [webpack.prod.js](mdc:config/webpack.prod.js) - Production Webpack configuration

### Asset Directories
- `assets/js/` - JavaScript source files
- `assets/css/` - CSS/PostCSS source files  
- `assets/images/` - Image assets
- `assets/fonts/` - Font files
- `assets/svg/` - SVG icons and graphics

### Template Directories
- `templates/` - Custom page templates
- `partials/` - Reusable template parts
- `includes/` - PHP includes and helper functions

## Development Workflow

1. Run `npm run start` to install dependencies
2. Use `npm run watch` for development with file watching
3. Use `npm run build` for production builds
4. Use `npm run lint` to check code quality

## Key Technologies
- **WordPress Theme Development**
- **Webpack 5** for asset bundling
- **PostCSS** for CSS processing
- **Babel** for JavaScript transpilation
- **ESLint/Stylelint** for code quality
- **PHP CodeSniffer** for PHP standards
