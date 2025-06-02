---
description: 
globs: 
alwaysApply: true
---
# Webpack Asset Pipeline Configuration

## Configuration Files Overview

### Primary Configuration
- [webpack.settings.js](mdc:config/webpack.settings.js) - **Main configuration file** - modify this for most projects
- [webpack.shared.js](mdc:config/webpack.shared.js) - Shared configuration for all environments
- [webpack.dev.js](mdc:config/webpack.dev.js) - Development-specific configuration
- [webpack.prod.js](mdc:config/webpack.prod.js) - Production-specific configuration

### Build Configuration Files
- [postcss.config.js](mdc:postcss.config.js) - PostCSS plugin configuration
- [.babelrc](mdc:.babelrc) - Babel transpilation settings
- [.browserslistrc](mdc:.browserslistrc) - Browser support targets

## Asset Processing

### Entry Points
Configure JavaScript and CSS entry points in [webpack.settings.js](mdc:config/webpack.settings.js):
- Add new entry points for additional JS/CSS bundles
- Entry points determine what gets compiled and output
- WordPress dependency extraction handles external libraries

### CSS Processing Pipeline
1. **PostCSS** processes modern CSS features
2. **Autoprefixer** adds vendor prefixes based on [.browserslistrc](mdc:.browserslistrc)
3. **CSS Nesting** allows nested selectors
4. **CSS Custom Properties** for CSS variables
5. **PostCSS Mixins** for reusable CSS patterns
6. **CSS Minimizer** in production builds

### JavaScript Processing Pipeline  
1. **Babel** transpiles ES6+ to compatible JavaScript
2. **ESLint** enforces code quality rules from [.eslintrc](mdc:.eslintrc)
3. **WordPress Dependency Extraction** handles WordPress and React dependencies
4. **Code splitting** and minification in production

## Asset Output

### Development Mode
- Source maps enabled for debugging
- Hot module replacement for faster development
- Unminified output for easier debugging
- BrowserSync integration for live reload

### Production Mode
- Minified and optimized assets
- No source maps (smaller file sizes)
- Cache-busting file names
- Optimized images and fonts

## WordPress Integration

### Dependency Management
- **WordPress Externals**: WordPress and React libraries are externalized
- **Dependency Extraction**: Generates PHP dependency files automatically
- **Proper Enqueueing**: Assets must be enqueued via WordPress functions

### File Output Structure
```
theme-root/
├── js/           # Compiled JavaScript files
├── css/          # Compiled CSS files  
├── assets/       # Source files (not served directly)
│   ├── js/       # JavaScript source
│   ├── css/      # CSS source
│   ├── images/   # Image assets
│   └── fonts/    # Font files
```

## Common Workflow

### Adding New Assets
1. Create source files in appropriate `assets/` subdirectory
2. Add entry points to [webpack.settings.js](mdc:config/webpack.settings.js)
3. Enqueue in [functions.php](mdc:functions.php) using WordPress functions
4. Run `npm run watch` during development

### Modifying Build Process
- **Entry Points**: Edit [webpack.settings.js](mdc:config/webpack.settings.js)
- **PostCSS Plugins**: Edit [postcss.config.js](mdc:postcss.config.js)
- **Browser Support**: Edit [.browserslistrc](mdc:.browserslistrc)
- **Advanced Changes**: Modify shared/dev/prod webpack configs
