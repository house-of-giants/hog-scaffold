# Blocks

This directory contains documentation related to WordPress blocks, block patterns, and block-based theme development.

## Contents

- Block development guides
- Block pattern creation and testing
- Modern block registration (WordPress 6.8+)
- Block editor customization
- Block variation examples
- Theme block templates

Navigate back to [Documentation Index](../README.md)

[Documentation](../README.md) > Blocks

# Block Development

This section contains comprehensive guides for developing WordPress blocks with the House of Giants scaffold using modern WordPress standards.

## Modern Block Registration System

**WordPress 6.8+ Optimized**: This theme uses the latest WordPress block registration standards for optimal performance and maintainability.

### Key Features

- ✅ **Manifest-Based Registration** - Uses `wp_register_block_types_from_metadata_collection()` for better performance
- ✅ **Automatic Asset Handling** - WordPress handles dependency extraction and enqueueing
- ✅ **Simplified Architecture** - No manual registration loops or complex validation scripts
- ✅ **Backward Compatibility** - Supports WordPress 6.7+ and legacy versions with fallbacks
- ✅ **Standard WordPress APIs** - Follows WordPress core recommendations

### How It Works

1. **Build Process** generates `blocks-manifest.php` using `wp-scripts build --blocks-manifest`
2. **Single Function Call** in `inc/blocks.php` registers all blocks at once
3. **WordPress Core** handles asset loading, dependencies, and validation
4. **No Manual Registration** - eliminates complex per-block registration code

## Quick Start

**New to block development?** Start here:

1. **[Layout Blocks Quick Start](layout-blocks-quick-start.md)** - Get up and running in minutes
2. **[Block Development Guide](block-development-guide.md)** - Learn the fundamentals
3. **[Layout Blocks System](layout-blocks-system.md)** - Understand the utility framework

## Documentation Contents

### Getting Started

- **[Layout Blocks Quick Start](layout-blocks-quick-start.md)** - Quick start guide for layout blocks
  - Prerequisites and setup
  - Creating your first block
  - Using utility components
  - Common patterns and troubleshooting

- **[Block Development Guide](block-development-guide.md)** - Comprehensive development guide
  - WordPress block fundamentals
  - Modern registration patterns
  - Custom block creation
  - Testing and validation

### Core Systems

- **[Layout Blocks System](layout-blocks-system.md)** - Complete utility framework documentation
  - System architecture and design principles
  - Utility components (SpacingControls, BackgroundControls)
  - CSS framework and helper functions
  - Block development patterns
  - Customization and migration guides

- **[Interactive Block Patterns](interactive-block-patterns.md)** - Advanced interactive patterns
  - Contact Form 7 integration
  - Security patterns and best practices
  - Interactive block development
  - Form handling and validation

### Patterns and Templates

- **[Block Patterns](block-patterns.md)** - Reusable block compositions
  - Pre-built pattern library
  - WordPress 6.0+ auto-discovery
  - Creating custom patterns
  - Pattern registration and management

- **[Block Patterns Testing](block-patterns-testing.md)** - Testing framework
  - Automated pattern testing
  - Quality assurance workflows
  - Performance validation

## Block Categories

The theme includes several categories of blocks:

### Layout Blocks
Modern utility-based blocks for consistent spacing and design:
- **Container Block** - Flexible content wrapper with spacing controls
- **Spacer Block** - Responsive spacing utility
- **Divider Block** - Section separators with customization options

### Interactive Blocks
Blocks with frontend functionality:
- **Contact Form Integration** - Enhanced Contact Form 7 blocks
- **Dynamic Content** - Blocks that update based on user interaction

### Content Blocks
Specialized content display blocks:
- **Custom Post Type Displays** - Automated content layouts
- **Featured Content** - Highlighted content sections

## Development Workflow

### Setting Up Development Environment

1. **Install dependencies:**
   ```bash
   npm install
   ```

2. **Start development server:**
   ```bash
   npm run watch
   ```

3. **Build for production:**
   ```bash
   npm run build  # Generates blocks manifest + builds all assets
   ```

4. **Generate blocks manifest only:**
   ```bash
   npm run build:blocks-manifest
   ```

### Block Creation Process

1. **Copy template** from `inc/blocks/layout/_template`
2. **Update block.json** with your block metadata
3. **Develop edit and save components** in React
4. **Add webpack entries** for JavaScript and CSS (if using custom webpack)
5. **Run build process** to generate manifest
6. **Test thoroughly** across different scenarios

### Modern Registration Benefits

- **No PHP Registration Code** - Blocks are automatically discovered via manifest
- **Better Performance** - Single PHP file vs multiple JSON reads
- **Automatic Dependencies** - WordPress handles script/style dependencies
- **Simplified Debugging** - Fewer moving parts, clearer error messages

### Code Quality

- **ESLint** - JavaScript code quality and consistency
- **Stylelint** - CSS code quality and formatting
- **Prettier** - Automated code formatting
- **WordPress Standards** - Following WordPress coding conventions

## Best Practices

### Accessibility
- Use semantic HTML elements
- Include proper ARIA labels and roles
- Ensure keyboard navigation works
- Test with screen readers
- Maintain good color contrast

### Performance
- Minimize JavaScript bundle size
- Use CSS classes over inline styles
- Optimize images and assets
- Implement lazy loading where appropriate
- Test on various devices and connection speeds

### Maintainability
- Follow consistent naming conventions
- Document complex functionality
- Use TypeScript for better code reliability
- Write comprehensive tests
- Keep dependencies up to date

## Architecture Overview

### File Structure
```
inc/blocks/
├── blocks-manifest.php  # Generated manifest file (WordPress 6.8+)
├── layout/              # Layout utility blocks
│   ├── utils/          # Shared utilities and components
│   ├── _template/      # Block template for new blocks
│   ├── container/      # Individual block directories
│   ├── spacer/
│   └── divider/
├── interactive/        # Interactive blocks with frontend JS
└── patterns/          # Block patterns and templates
```

### Build System Integration
- **wp-scripts** generates blocks manifest for modern registration
- **Webpack** handles theme asset compilation and optimization
- **Babel** transpiles modern JavaScript for browser compatibility
- **PostCSS** processes CSS with modern features
- **WordPress Dependency Extraction** manages WordPress and React dependencies

### Theme Integration
- **Manifest-based registration** via `wp_register_block_types_from_metadata_collection()`
- **Automatic asset handling** through WordPress core
- **Fallback support** for WordPress 6.7 and legacy versions
- **Block categories** organized by functionality
- **Custom block inspector controls** integrate with theme settings

## WordPress Version Compatibility

### WordPress 6.8+
- Full manifest-based registration with optimal performance
- Uses `wp_register_block_types_from_metadata_collection()`
- Single function call registers all blocks

### WordPress 6.7
- Fallback to `wp_register_block_metadata_collection()`
- Still uses manifest file for better performance

### WordPress 6.0-6.6
- Legacy manual registration with full functionality
- Individual `register_block_type()` calls as fallback

## Troubleshooting

### Common Issues

**Block not appearing in editor:**
- Check that `blocks-manifest.php` was generated during build
- Verify webpack build completed successfully
- Ensure block.json is valid
- Check browser console for JavaScript errors

**Manifest not generated:**
- Run `npm run build:blocks-manifest` manually
- Check that `@wordpress/scripts` is installed
- Verify block.json files are properly formatted
- Ensure build process completed without errors

**Styles not loading:**
- Verify CSS files are being built by webpack
- Check that styles are properly enqueued via block.json
- Clear browser cache
- Validate CSS syntax

**JavaScript errors:**
- Check import paths are correct
- Ensure all dependencies are available
- Verify React component syntax
- Check WordPress version compatibility

### Debugging Tips

1. **Use browser developer tools** to inspect elements and check console
2. **Check webpack build output** for compilation errors
3. **Test with default values** to isolate issues
4. **Use WordPress debug mode** to see PHP errors
5. **Test across different browsers** and devices

## Resources

### WordPress Documentation
- [Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [Block API Reference](https://developer.wordpress.org/block-editor/reference-guides/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)

### React and JavaScript
- [React Documentation](https://reactjs.org/docs)
- [Modern JavaScript Features](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
- [ESLint Configuration](https://eslint.org/docs/user-guide/configuring)

### CSS and Styling
- [PostCSS Plugins](https://postcss.org/)
- [CSS Grid Guide](https://css-tricks.com/snippets/css/complete-guide-grid/)
- [Flexbox Guide](https://css-tricks.com/snippets/css/a-guide-to-flexbox/)

## Contributing

When contributing to block development:

1. **Follow the established patterns** in existing blocks
2. **Use the utility components** for consistent UX
3. **Add comprehensive documentation** for new features
4. **Include tests** for complex functionality
5. **Update this documentation** when adding new patterns

## Support

- **Check the troubleshooting guides** in this section
- **Review existing block code** for implementation examples
- **Test thoroughly** before submitting changes
- **Ask questions** during code review process

---

## Quick Links

- **[🚀 Quick Start](layout-blocks-quick-start.md)** - Get started in minutes
- **[🏗️ Layout System](layout-blocks-system.md)** - Comprehensive utility framework
- **[⚡ Interactive Patterns](interactive-block-patterns.md)** - Advanced interactive development
- **[📋 Development Guide](block-development-guide.md)** - Complete development guide
- **[🎨 Block Patterns](block-patterns.md)** - Reusable pattern library

---

**[⬅️ Back to Documentation](../README.md)** | **[➡️ Next: Getting Started](../getting-started/README.md)** 