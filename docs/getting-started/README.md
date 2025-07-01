[Documentation](../README.md) > Getting Started

# Getting Started

Welcome to the House of Giants WordPress Theme Scaffold! This section provides everything you need to get up and running with modern WordPress theme development.

## Quick Start

### Starting a New Project

**Want to use this scaffold for a new project?** Start here:

1. **[New Project Setup Guide](new-project-setup.md)** - Complete guide to customize this scaffold for your project
2. **[Installation Guide](installation-guide.md)** - Set up your development environment
3. **[Build System Guide](build-system.md)** - Understand the development workflow

### Learning the Existing Theme

**Exploring or contributing to this theme?** Follow these steps:

1. **[Installation Guide](installation-guide.md)** - Set up your development environment
2. **[Build System Guide](build-system.md)** - Understand the development workflow
3. **[First Steps Tutorial](#first-steps-tutorial)** - Create your first custom block

## Documentation Contents

### Setup and Installation

- **[New Project Setup Guide](new-project-setup.md)** - Complete guide for using this scaffold to start a new project
  - Project customization and branding
  - Search and replace operations for theme identifiers
  - Configuration file updates
  - Development environment setup
  - WordPress installation and theme activation
  - Asset configuration and build system setup

- **[Installation Guide](installation-guide.md)** - Complete setup instructions for local development
  - Prerequisites and required software
  - Local development environment options (Docker, MAMP, manual)
  - Step-by-step installation process
  - Configuration and verification
  - Troubleshooting common installation issues

- **[Build System Guide](build-system.md)** - Development workflow and asset compilation
  - Available npm scripts and their purposes
  - Webpack configuration and customization
  - Asset processing pipeline (CSS, JavaScript, images)
  - Performance optimization features
  - Development vs. production builds

### Development Workflow

- **[Development Environment Setup](#development-environment-setup)** - Optimize your coding environment
- **[Code Quality Tools](#code-quality-tools)** - Linting, formatting, and testing
- **[Git Workflow](#git-workflow)** - Version control best practices
- **[Debugging Guide](#debugging-guide)** - Debug theme issues effectively

## Development Environment Setup

### Recommended IDE Configuration

**Visual Studio Code** with these extensions:
- **PHP Intelephense** - PHP language support
- **WordPress Snippets** - WordPress development snippets
- **Prettier** - Code formatting
- **ESLint** - JavaScript linting
- **GitLens** - Enhanced Git integration
- **Thunder Client** - API testing

**IDE Settings:**
```json
{
  "editor.formatOnSave": true,
  "editor.codeActionsOnSave": {
    "source.fixAll.eslint": true
  },
  "php.suggest.basic": false,
  "intelephense.stubs": ["wordpress"]
}
```

### Browser Development Tools

**Recommended Extensions:**
- **WordPress Admin Bar Control** - Hide admin bar during development
- **WordPress Developer** - WordPress-specific debugging tools
- **Lighthouse** - Performance and accessibility auditing
- **Vue DevTools** - For Vue.js components (if using)

## Code Quality Tools

### Automated Code Quality

The theme includes several tools to maintain code quality:

```bash
# Run all linters
npm run lint

# Fix auto-fixable issues
npm run format

# Run specific linters
npm run lint-css    # Stylelint for CSS
npm run lint-js     # ESLint for JavaScript  
npm run lint-php    # PHP CodeSniffer
```

### Pre-commit Hooks

Install pre-commit hooks to ensure code quality:

```bash
# Install husky for git hooks
npm run prepare

# Hooks will automatically:
# - Run linters on staged files
# - Format code automatically
# - Run tests before commits
# - Prevent commits with errors
```

### Editor Configuration

The theme includes configuration files for consistent coding:

- **[.editorconfig](../../.editorconfig)** - Basic editor settings
- **[.eslintrc](../../.eslintrc)** - JavaScript linting rules
- **[.stylelintrc.json](../../.stylelintrc.json)** - CSS linting rules
- **[.phpcs.xml.dist](../../.phpcs.xml.dist)** - PHP coding standards

## Git Workflow

### Branch Strategy

```bash
# Main branches
main              # Production-ready code
develop           # Integration branch for features

# Feature branches
feature/new-block        # New block development
feature/performance-opt  # Performance improvements
fix/navigation-bug      # Bug fixes
docs/api-reference      # Documentation updates
```

### Commit Message Format

Follow conventional commit format:

```bash
# Format: type(scope): description
feat(blocks): add new hero block with video background
fix(navigation): resolve mobile menu accessibility issue
docs(readme): update installation instructions
style(css): improve button hover animations
refactor(js): modernize form validation code
```

### Git Aliases for Theme Development

Add these aliases to your `~/.gitconfig`:

```ini
[alias]
    # Theme-specific aliases
    theme-status = status --short
    theme-log = log --oneline --graph --decorate
    theme-diff = diff --name-status
    
    # Quick commands
    co = checkout
    br = branch
    st = status
    cm = commit -m
```

## Debugging Guide

### WordPress Debug Mode

Enable debug mode in your development environment:

```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);
```

### Theme-Specific Debugging

```bash
# View WordPress debug log
tail -f wp-content/debug.log

# Check PHP error log
tail -f /var/log/php/error.log

# Monitor Apache/Nginx error log
tail -f /var/log/apache2/error.log
```

### JavaScript Debugging

```javascript
// Use console methods for debugging
console.log('Debug info:', data);
console.error('Error occurred:', error);
console.table(arrayData);
console.group('Function execution');

// Use debugger statement
function complexFunction() {
    debugger; // Execution will pause here
    // ... function code
}
```

### CSS Debugging

```css
/* Debug layout issues */
* {
    outline: 1px solid red !important;
}

/* Debug specific elements */
.debug-element {
    background: rgba(255, 0, 0, 0.1) !important;
    border: 2px solid red !important;
}
```

## First Steps Tutorial

### 1. Verify Installation

```bash
# Check that everything is working
npm run dev
# Should complete without errors

# Check WordPress admin
# Theme should appear in Appearance > Themes
```

### 2. Explore the Codebase

```bash
# Key directories to understand
ls -la inc/blocks/          # Custom blocks
ls -la assets/css/          # CSS source files
ls -la assets/js/           # JavaScript source files
ls -la templates/           # Block templates
ls -la parts/               # Template parts
ls -la patterns/            # Block patterns
```

### 3. Create Your First Custom Block

Follow the [Block Development Guide](../blocks/block-development-guide.md) to create a custom block:

```bash
# Create new block directory
mkdir inc/blocks/my-first-block
cd inc/blocks/my-first-block

# Create required files
touch block.json index.js edit.js save.js index.css
```

### 4. Test Block Editor Integration

1. Create a new page in WordPress admin
2. Open the block editor
3. Insert your custom blocks
4. Test block patterns
5. Verify responsive design

### 5. Customize the Theme

1. **Colors and Typography:**
   - Edit `theme.json` to customize design tokens
   - Update CSS custom properties

2. **Navigation:**
   - Create navigation menus in WordPress admin
   - Customize navigation styles in CSS

3. **Content Patterns:**
   - Explore existing block patterns
   - Create new patterns for your content needs

## Performance Optimization

### Development Performance

```bash
# Use development build for faster compilation
npm run dev

# Use watch mode for automatic rebuilds
npm run watch

# Analyze bundle size
npm run build-analyze
```

### Theme Performance

- **Critical CSS:** Automatically inlined for above-the-fold content
- **Code Splitting:** JavaScript loaded based on page requirements
- **Image Optimization:** Automatic WebP conversion and lazy loading
- **Caching:** Built-in WP Engine and Redis support

## Learning Resources

### WordPress Development

- **[WordPress Developer Handbook](https://developer.wordpress.org/)** - Official WordPress documentation
- **[Block Editor Handbook](https://developer.wordpress.org/block-editor/)** - Block development guide
- **[WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)** - Code quality standards

### Modern JavaScript

- **[ES6 Features](https://babeljs.io/docs/en/learn)** - Modern JavaScript syntax
- **[React Documentation](https://reactjs.org/docs/getting-started.html)** - For block development
- **[Webpack Documentation](https://webpack.js.org/concepts/)** - Build system understanding

### CSS and Design

- **[CSS Grid Guide](https://css-tricks.com/snippets/css/complete-guide-grid/)** - Modern layout techniques
- **[Flexbox Guide](https://css-tricks.com/snippets/css/a-guide-to-flexbox/)** - Flexible layouts
- **[PostCSS Documentation](https://postcss.org/)** - CSS processing

## Troubleshooting Quick Reference

### Common Issues

| Issue | Solution |
|-------|----------|
| Build fails with Node.js errors | Update Node.js to version 18+ |
| Theme not appearing in WordPress | Check file permissions and theme header |
| CSS changes not reflecting | Clear cache and rebuild assets |
| JavaScript errors in console | Check for missing dependencies |
| Blocks not working in editor | Verify block registration and build process |

### Quick Fixes

```bash
# Clear all caches and rebuild
npm run clean
npm install
npm run build

# Reset WordPress permissions
chmod -R 755 wp-content/themes/hog-scaffold/

# Clear WordPress cache (if using caching plugin)
wp cache flush

# Restart development server
# Stop with Ctrl+C, then:
npm run watch
```

## Getting Help

### Documentation Resources

- **[Block Development Guide](../blocks/block-development-guide.md)** - Custom block creation
- **[Customization Guide](../customization/README.md)** - Theme customization
- **[Security Guide](../security/security-guide.md)** - Security best practices
- **[Deployment Guide](../deployment/deployment-guide.md)** - Production deployment

### Community Support

- **GitHub Issues** - Report bugs and request features
- **WordPress Forums** - General WordPress development help
- **Stack Overflow** - Technical programming questions
- **WordPress Slack** - Real-time community support

## Next Steps

After completing the getting started process:

1. **[Explore Custom Blocks](../blocks/README.md)** - Learn about the pre-built blocks
2. **[Customize the Theme](../customization/README.md)** - Make it your own
3. **[Understand Security](../security/README.md)** - Keep your site secure
4. **[Plan Deployment](../deployment/README.md)** - Get ready for production

---

**[⬅️ Back to Documentation Index](../README.md)** | **[➡️ Next: Installation Guide](installation-guide.md)** 