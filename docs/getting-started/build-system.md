[Documentation](../README.md) > [Getting Started](README.md) > Build System

# Build System Documentation

This document explains the build system for the WordPress theme scaffold, including available scripts, configuration, and development workflow.

## Overview

The theme uses a dual-build system:

- **Theme Assets**: Custom Webpack configuration for theme CSS/JS
- **Block Development**: @wordpress/scripts for React block development

## Available Scripts

### Development Scripts

#### `npm run dev`

Builds assets for development with source maps and unminified output.

```bash
npm run dev
```

#### `npm run watch`

Builds assets in development mode and watches for file changes.

```bash
npm run watch
```

#### `npm run serve`

Starts webpack dev server with hot module replacement (if configured).

```bash
npm run serve
```

### Production Scripts

#### `npm run build`

Builds optimized assets for production with minification and optimization.

```bash
npm run build
```

#### `npm run build-clean`

Cleans the dist directory and builds fresh production assets.

```bash
npm run build-clean
```

#### `npm run build-release`

Complete release build including dependency installation and production build.

```bash
npm run build-release
```

#### `npm run build-analyze`

Builds production assets with bundle analysis (if webpack-bundle-analyzer is configured).

```bash
npm run build-analyze
```

#### `npm run build-stats`

Generates webpack stats JSON file for analysis.

```bash
npm run build-stats
```

### Code Quality Scripts

#### `npm run lint`

Runs all linters (CSS, JavaScript, PHP).

```bash
npm run lint
```

#### `npm run lint-css`

Lints CSS files using Stylelint.

```bash
npm run lint-css
```

#### `npm run lint-js`

Lints JavaScript files using ESLint.

```bash
npm run lint-js
```

#### `npm run lint-php`

Lints PHP files using PHP CodeSniffer.

```bash
npm run lint-php
```

#### `npm run format`

Auto-fixes JavaScript and CSS formatting issues.

```bash
npm run format
```

#### `npm run format-js`

Auto-fixes JavaScript formatting using ESLint.

```bash
npm run format-js
```

#### `npm run format-css`

Auto-fixes CSS formatting using Stylelint.

```bash
npm run format-css
```

### WordPress Scripts

#### `npm run wp-start`

Starts @wordpress/scripts development build for blocks.

```bash
npm run wp-start
```

#### `npm run wp-build`

Builds blocks using @wordpress/scripts.

```bash
npm run wp-build
```

#### `npm run wp-format`

Formats code using WordPress standards.

```bash
npm run wp-format
```

### Maintenance Scripts

#### `npm run clean`

Cleans build artifacts and cache.

```bash
npm run clean
```

#### `npm run clean-all`

Complete clean including node_modules and vendor, then reinstalls.

```bash
npm run clean-all
```

#### `npm run test`

Runs linting and build to verify everything works.

```bash
npm run test
```

## Build Configuration

### Webpack Configuration Files

- `config/webpack.shared.js` - Shared configuration for all environments
- `config/webpack.dev.js` - Development-specific configuration
- `config/webpack.prod.js` - Production-specific configuration
- `config/webpack.settings.js` - Entry points and paths configuration
- `webpack.config.js` - Main configuration that routes to appropriate configs

### Asset Processing

#### JavaScript

- **Babel**: Transpiles ES6+ to compatible JavaScript
- **ESLint**: Code quality and style enforcement
- **Minification**: TerserPlugin for production builds
- **Code Splitting**: Vendor and common chunks for better caching

#### CSS

- **PostCSS**: Modern CSS features (nesting, mixins, imports)
- **Stylelint**: CSS code quality and style enforcement
- **Minification**: CssMinimizerPlugin for production builds
- **Autoprefixer**: Automatic vendor prefixes

#### Images

- **Optimization**: ImageminPlugin for PNG/GIF/SVG optimization
- **Modern Formats**: WebP support
- **Inlining**: Small images (< 8KB) automatically inlined
- **Hashing**: Content-based filenames for cache busting

#### Fonts

- **Asset Modules**: Modern webpack asset handling
- **Hashing**: Content-based filenames for cache busting

### Performance Optimizations

#### Production Build Features

- **Minification**: JavaScript and CSS minification
- **Tree Shaking**: Dead code elimination
- **Code Splitting**: Vendor and common chunk separation
- **Caching**: Filesystem caching for faster rebuilds
- **Source Maps**: Disabled in production for smaller bundles
- **Console Removal**: console.log statements removed in production

#### Development Build Features

- **Source Maps**: Detailed source maps for debugging
- **Fast Builds**: Optimized for development speed
- **Hot Reloading**: File watching and automatic rebuilds
- **Caching**: Aggressive caching for faster rebuilds

## File Structure

```
assets/
├── css/                 # CSS source files
│   ├── admin/          # Admin-specific styles
│   ├── frontend/       # Frontend styles
│   └── shared/         # Shared styles
├── js/                 # JavaScript source files
│   ├── admin/          # Admin-specific scripts
│   ├── frontend/       # Frontend scripts
│   └── shared/         # Shared scripts
├── images/             # Image assets
└── fonts/              # Font files

dist/                   # Built assets (generated)
├── css/               # Compiled CSS
├── js/                # Compiled JavaScript
├── images/            # Optimized images
└── fonts/             # Processed fonts

config/                # Build configuration
├── webpack.shared.js  # Shared webpack config
├── webpack.dev.js     # Development config
├── webpack.prod.js    # Production config
└── webpack.settings.js # Entry points and settings
```

## Development Workflow

### Starting Development

1. Install dependencies: `npm install && composer install`
2. Start development build: `npm run watch`
3. Make changes to files in `assets/` directory
4. Built files appear in `dist/` directory

### Code Quality

- Pre-commit hooks automatically run linting
- Use `npm run format` to auto-fix formatting issues
- Use `npm run lint` to check for issues

### Production Deployment

1. Run `npm run build-release` for complete production build
2. Upload theme files to WordPress installation
3. Built assets in `dist/` directory are ready for production

### Block Development

- Use `npm run wp-start` for block development
- Block files should be in appropriate block directories
- @wordpress/scripts handles JSX compilation and WordPress dependencies

## Troubleshooting

### Common Issues

#### Build Fails

- Check Node.js version (requires >= 18.0.0)
- Clear cache: `npm run clean`
- Reinstall dependencies: `npm run clean-all`

#### Linting Errors

- Auto-fix: `npm run format`
- Check specific linter: `npm run lint-css`, `npm run lint-js`, or `npm run lint-php`

#### Performance Issues

- Use `npm run build-stats` to analyze bundle size
- Check webpack cache in `node_modules/.cache/`

### Getting Help

- Check webpack documentation: https://webpack.js.org/
- Check @wordpress/scripts documentation: https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/
- Review configuration files in `config/` directory

## See Also

- [Getting Started Overview](README.md) - Getting started with the theme
- [Customization Guide](../customization/README.md) - Customizing theme functionality
- [Block Development Guide](../blocks/block-development-guide.md) - Creating custom blocks

**[⬅️ Back to Documentation Index](../README.md)** | **[➡️ Next: Customization](../customization/README.md)**
