---
description: 
globs: 
alwaysApply: true
---
# Development Guidelines & Best Practices

## WordPress Theme Development Standards

### PHP Coding Standards
- Follow [WordPress PHP Coding Standards](mdc:https:/developer.wordpress.org/coding-standards/wordpress-coding-standards/php)
- Use PHP CodeSniffer with the configuration in [.phpcs.xml.dist](mdc:.phpcs.xml.dist)
- All PHP files should follow PSR-4 autoloading standards via [composer.json](mdc:composer.json)
- Prefix all theme functions with `hog_scaffold_` (update during project setup)

### JavaScript Standards
- Follow Airbnb ESLint configuration defined in [.eslintrc](mdc:.eslintrc)
- Use ES6+ features with Babel transpilation
- Source files go in `assets/js/`
- Configure entry points in [webpack.settings.js](mdc:config/webpack.settings.js)

### CSS/PostCSS Standards
- Use PostCSS with modern CSS features
- Follow Stylelint rules in [.stylelintrc.json](mdc:.stylelintrc.json)
- Source files go in `assets/css/`
- Use CSS custom properties and modern layout techniques
- Configure entry points in [webpack.settings.js](mdc:config/webpack.settings.js)

## File Organization

### Template Hierarchy
- Follow [WordPress Template Hierarchy](mdc:https:/developer.wordpress.org/themes/basics/template-hierarchy)
- Place custom templates in `templates/` directory
- Create reusable components in `partials/` directory
- Use [header.php](mdc:header.php) and [footer.php](mdc:footer.php) for common elements

### Asset Management
- All source assets go in `assets/` subdirectories
- Webpack processes and outputs to theme root
- Use [webpack.settings.js](mdc:config/webpack.settings.js) to configure entry points
- Leverage WordPress dependency extraction for external libraries

## Development Commands

### Building Assets
```bash
npm run start     # Install dependencies and build
npm run watch     # Development with file watching
npm run dev       # Development build
npm run build     # Production build
```

### Code Quality
```bash
npm run lint      # Run all linters
npm run lint-css  # Stylelint for CSS
npm run lint-js   # ESLint for JavaScript  
npm run lint-php  # PHP CodeSniffer
npm run format    # Auto-fix JavaScript formatting
```

## WordPress Integration

### Theme Functions
- Use [functions.php](mdc:functions.php) for WordPress hooks and filters
- Enqueue assets properly using `wp_enqueue_script()` and `wp_enqueue_style()`
- Register navigation menus, widget areas, and theme support features
- Follow WordPress security best practices (sanitization, escaping, nonces)

### Template Development
- Use WordPress template tags and conditional functions
- Implement proper accessibility standards
- Ensure responsive design across all devices
- Test with various WordPress content scenarios
