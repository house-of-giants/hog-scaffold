# House of Giants Scaffold

[![Support Level](https://img.shields.io/badge/support-active-green.svg)](#support-level)

A modern, accessible WordPress theme scaffold built with WCAG 2.1 AA compliance and inclusive design principles.

## Features

- **Accessibility First**: WCAG 2.1 AA compliant with comprehensive accessibility features
- **Modern Build Process**: Webpack-based asset pipeline with PostCSS and Babel
- **Block Theme Ready**: Full Site Editing support with semantic block templates
- **WordPress 6.8+ Optimized**: Modern block registration using manifest-based approach
- **Simplified Architecture**: Streamlined block and pattern registration following WordPress standards
- **Performance Optimized**: Efficient asset loading and modern CSS/JS
- **Developer Friendly**: ESLint, Stylelint, and comprehensive documentation

## Block & Pattern System

This theme uses modern WordPress standards for block and pattern registration:

- ✅ **WordPress 6.8+ Manifest Registration** - Uses `wp_register_block_types_from_metadata_collection()` for optimal performance
- ✅ **Automatic Pattern Discovery** - Patterns in `/patterns/` directory are auto-discovered (WordPress 6.0+)
- ✅ **Backward Compatibility** - Supports WordPress 6.7+ and legacy versions with fallbacks
- ✅ **No Manual Registration** - Eliminates complex registration loops and validation scripts
- ✅ **Standard WordPress APIs** - Follows WordPress core recommendations and best practices

### Block Registration

Blocks are registered using a modern manifest-based approach:
- `blocks-manifest.php` file generated during build process
- Single function call replaces individual block registrations
- Better performance through PHP manifest vs multiple JSON reads
- Automatic dependency extraction and asset enqueueing

### Pattern Registration

Block patterns use WordPress native auto-discovery:
- Place patterns in `/patterns/` directory with proper PHP headers
- WordPress 6.0+ automatically discovers and registers patterns
- WordPress 6.8+ supports subfolder organization
- No manual registration code required

## Accessibility

This theme is built with accessibility as a core principle, including:

- ✅ **Semantic HTML** with proper landmarks and ARIA labels
- ✅ **Keyboard Navigation** with visible focus indicators and skip links
- ✅ **Screen Reader Support** with live regions and descriptive text
- ✅ **Color Contrast** meeting WCAG AA standards (4.5:1 ratio)
- ✅ **Form Accessibility** with proper labels and error handling
- ✅ **Responsive Design** that works at 200% zoom and on all devices

For detailed accessibility information, see [ACCESSIBILITY.md](ACCESSIBILITY.md).

## Dependencies

1. [Node & NPM](https://www.npmjs.com/get-npm) - Build packages and 3rd party dependencies are managed through NPM, so you will need that installed globally.
2. [Webpack](https://webpack.js.org/) - Webpack is used to process the JavaScript, CSS, and other assets.
3. [@wordpress/scripts](https://www.npmjs.com/package/@wordpress/scripts) - Used for generating blocks manifest file.

## Getting Started

### Direct Install

- Clone the repository
- Rename folder theme-scaffold -> your project's name
- If copying files manually to an existing theme directory instead of cloning directly from the repository, make sure to include the following files which may be hidden:

```
.babelrc
.browserslistrc
.editorconfig
.eslintignore
.eslintrc
.gitignore
```

The NPM commands will fail without these files present.

- Do case-sensitive search/replace for the following:

        - HoGScaffold
        - HoG_SCAFFOLD
        - HoG-scaffold
        - HoG_scaffold
        - HoG Scaffold
        - hog-scaffold-blocks
        - https://project-website.tld
        - HOG_SCAFFOLD_VERSION
        - HOG_SCAFFOLD_TEMPLATE_URL
        - HOG_SCAFFOLD_PATH
        - HOG_SCAFFOLD_INC
        - HOG_SCAFFOLD_BLOCK_DIR

- `cd` into the theme folder
- run `npm run start`

## Build Process

The theme uses a hybrid build approach combining WordPress standards with custom optimization:

### Blocks Manifest Generation
```bash
npm run build:blocks-manifest  # Generate blocks-manifest.php using wp-scripts
```

### Full Build Process
```bash
npm run build  # Generate manifest + build all theme assets
```

### Build Configuration

Webpack config files can be found in `config` folder:

- `webpack.dev.js`
- `webpack.shared.js`
- `webpack.prod.js`
- `webpack.settings.js`

In most cases `webpack.settings.js` is the main file which would change from project to project. For example adding or removing entry points for JS and CSS.

## Webpack Integration

This theme leverages Webpack for comprehensive asset optimization, eliminating the need for separate optimization scripts:

### CSS Optimization
Webpack handles all CSS processing through:
- **PostCSS Pipeline**: Transforms modern CSS features and optimizes output
- **CSS Minimization**: Production builds automatically minify CSS with `css-minimizer-webpack-plugin`
- **Critical CSS**: Consider using hosting providers that generate critical CSS or implement via PostCSS plugins
- **Autoprefixing**: Vendor prefixes added based on `.browserslistrc` configuration

### Image Optimization
Built-in image processing includes:
- **Image Minimization**: `image-minimizer-webpack-plugin` optimizes JPEG, PNG, SVG files
- **WebP Generation**: Automatic WebP format generation with fallbacks
- **Responsive Images**: Proper sizing and optimization for different screen densities
- **Lazy Loading**: Integrated with WordPress core lazy loading features

### Performance Reporting
Webpack provides comprehensive performance insights:
- **Bundle Analysis**: Use `npm run build:analyze` to view detailed bundle composition
- **Build Stats**: Generate webpack stats with `npm run build:stats`
- **Performance Budgets**: Configured in `webpack.settings.js` with size limits
- **Asset Optimization**: Automatic code splitting and asset optimization

### JavaScript Processing
Modern JavaScript features handled through:
- **Babel Transpilation**: ES6+ syntax support with WordPress compatibility
- **Code Splitting**: Automatic splitting for optimal loading performance
- **Minification**: Production builds include JavaScript minification
- **Source Maps**: Development builds include source maps for debugging

## Cache Management

The theme includes a simplified cache management utility for development and maintenance:

### Available Commands
```bash
# Clear all cache types
npm run cache:clear

# Clear specific cache types
npm run cache:clear-object     # Object cache (Redis/Memcached)
npm run cache:clear-page       # Page cache (WP Engine, WP Rocket, etc.)
npm run cache:clear-transients # WordPress transients

# Check cache status
npm run cache:status
```

### Supported Cache Types
- **Object Cache**: Redis, Memcached, WP Engine object cache
- **Page Cache**: WP Engine, WP Super Cache, W3 Total Cache, WP Rocket, LiteSpeed Cache
- **WordPress Transients**: Built-in WordPress temporary data storage
- **PHP OPcache**: PHP opcode caching

### Manual Cache Management
You can also run the cache utility directly:
```bash
# Via WP-CLI
wp eval-file scripts/cache-management.php clear [type]
wp eval-file scripts/cache-management.php status
```

## Hosting Recommendations

For optimal performance with this theme, consider hosting providers that offer:

### WordPress-Optimized Hosting
- **WP Engine**: Built-in object caching, automatic image optimization, and global CDN
- **Kinsta**: Google Cloud infrastructure with automatic scaling and security
- **Cloudways**: Flexible cloud hosting with performance optimization tools
- **SiteGround**: WordPress-specific optimizations and built-in caching

### Required Hosting Features
- **PHP 8.1+**: Required for optimal performance and security
- **MySQL 8.0+**: Database performance improvements
- **Object Caching**: Redis or Memcached support for database query caching
- **CDN Integration**: Content delivery network for global performance
- **SSL/TLS**: HTTPS support with automatic certificate management

### Performance Features
- **Critical CSS Generation**: Automatic above-the-fold CSS optimization
- **Image Optimization**: WebP conversion and responsive image generation
- **Gzip/Brotli Compression**: Asset compression for faster loading
- **HTTP/2 Support**: Modern protocol support for improved performance
- **Edge Caching**: Global edge locations for content delivery

### Advanced Features (Recommended)
- **Staging Environments**: Separate environments for testing changes
- **Automated Backups**: Regular, automated backup solutions
- **Security Monitoring**: Malware scanning and intrusion detection
- **Performance Monitoring**: Real-time performance analytics
- **Git Integration**: Direct deployment from Git repositories

## NPM Commands

- `npm run start` (install dependencies and build)
- `npm run watch` (watch for development)
- `npm run build` (generate blocks manifest + build all files)
- `npm run build:blocks-manifest` (generate WordPress 6.8+ blocks manifest)
- `npm run build-release` (build all files for release)
- `npm run dev` (build all files for development)
- `npm run lint-release` (install dependencies and run linting)
- `npm run lint-css` (lint CSS)
- `npm run lint-js` (lint JS)
- `npm run lint` (run all lints)
- `npm run format-js` (format JS using eslint)
- `npm run format` (alias for `npm run format-js`)

### Cache Management Commands
- `npm run cache:clear` (clear all cache types)
- `npm run cache:clear-object` (clear object cache only)
- `npm run cache:clear-page` (clear page cache only)
- `npm run cache:clear-transients` (clear WordPress transients only)
- `npm run cache:status` (show cache status)

### Analysis Commands
- `npm run build:analyze` (analyze bundle composition)
- `npm run build:stats` (generate webpack statistics)
- `npm run bundle-analyzer` (view bundle analysis in browser)

## WordPress Version Support

- **WordPress 6.8+**: Full manifest-based block registration with optimal performance
- **WordPress 6.7**: Fallback to `wp_register_block_metadata_collection()`
- **WordPress 6.0-6.6**: Legacy manual block registration with full functionality
- **Patterns**: Auto-discovery supported in WordPress 6.0+, manual registration for older versions

## Contributing

We don't know everything! We welcome pull requests and spirited, but respectful, debates. Please contribute via [pull requests on GitHub](https://github.com/HoG/theme-scaffold/compare).

1. Fork it!
2. Create your feature branch: `git checkout -b feature/my-new-feature`
3. Commit your changes: `git commit -am 'Added some great feature!'`
4. Push to the branch: `git push origin feature/my-new-feature`
5. Submit a pull request

## Support Level

**Active:** House of Giants is actively working on this, and we expect to continue work for the foreseeable future including keeping tested up to the most recent version of WordPress. Bug reports, feature requests, questions, and pull requests are welcome.
