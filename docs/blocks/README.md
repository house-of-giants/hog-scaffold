# Blocks

This directory contains documentation related to WordPress blocks, block patterns, and block-based theme development.

## Contents

- Block development guides
- Block pattern creation and testing
- Custom block registration
- Block editor customization
- Block variation examples
- Theme block templates

Navigate back to [Documentation Index](../README.md)

[Documentation](../README.md) > Blocks

# Block Development

This section contains comprehensive guides for developing WordPress blocks with the House of Giants scaffold.

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
  - Theme-specific patterns
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
   npm run build
   ```

### Block Creation Process

1. **Copy template** from `inc/blocks/layout/_template`
2. **Update block.json** with your block metadata
3. **Develop edit and save components** in React
4. **Add webpack entries** for JavaScript and CSS
5. **Test thoroughly** across different scenarios

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
- **Webpack** handles asset compilation and optimization
- **Babel** transpiles modern JavaScript for browser compatibility
- **PostCSS** processes CSS with modern features
- **WordPress Dependency Extraction** manages WordPress and React dependencies

### Theme Integration
- Blocks are automatically registered via PHP
- Styles are properly enqueued through WordPress
- Block categories are organized by functionality
- Custom block inspector controls integrate with theme settings

## Troubleshooting

### Common Issues

**Block not appearing in editor:**
- Check block registration in PHP
- Verify webpack build completed successfully
- Ensure block.json is valid
- Check browser console for JavaScript errors

**Styles not loading:**
- Verify CSS files are being built by webpack
- Check that styles are properly enqueued
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