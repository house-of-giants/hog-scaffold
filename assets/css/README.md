# CSS Architecture Documentation

## Overview

This WordPress theme implements a modern, scalable CSS architecture using WordPress theme variables, BEM methodology, and a component-based approach. The styles are organized into logical modules and build upon WordPress block theme standards.

**Note:** This CSS structure was recently consolidated (Task 22) to eliminate redundancy and enforce consistent WordPress theme variable usage across all stylesheets.

## Architecture Principles

### 1. WordPress Theme Variables System
- All design tokens are defined as CSS custom properties synced with `theme.json`
- Variables follow WordPress naming conventions: `var(--wp--preset--[type]--[name])`
- Semantic colors added for UI states: success, warning, error
- Complete integration with WordPress block editor for real-time preview

### 2. BEM Methodology
- Block Element Modifier naming convention for component classes
- Example: `.card__title--large` (block: card, element: title, modifier: large)
- Provides predictable and maintainable class names

### 3. Mobile-First Responsive Design
- All styles start with mobile base styles
- Media queries progressively enhance for larger screens
- Breakpoints defined in variables for consistency

### 4. WordPress Integration
- Full compatibility with WordPress block themes
- Supports block variations and custom block styles
- Integrates with WordPress color, typography, and spacing systems

## File Organization

```
assets/css/
├── main.css                    # Main entry point (imports all styles)
├── editor-style.css           # Editor styles (imports main.css + editor overrides)
├── global/                     # Global styles and variables
│   ├── variables.css          # WordPress theme variables and custom properties
│   └── base.css              # Reset, typography, foundational styles, WordPress alignment classes
├── components/                # Reusable UI components
│   ├── buttons.css           # Button styles and variants
│   ├── forms.css             # Form controls and layouts
│   ├── navigation.css        # Navigation components
│   ├── cards.css            # Card components and variants
│   ├── skip-link.css        # Accessibility skip link component
│   ├── newsletter-form.css  # Newsletter subscription form component
│   └── security-messages.css # Success/error/validation message component
├── blocks/                   # WordPress block styles
│   ├── core/                # Core WordPress blocks
│   │   ├── group.css        # Group block styles
│   │   ├── columns.css      # Columns block styles
│   │   ├── cover.css        # Cover block styles
│   │   ├── heading.css      # Heading block styles
│   │   ├── paragraph.css    # Paragraph block styles
│   │   ├── list.css         # List block styles
│   │   ├── image.css        # Image block styles
│   │   └── button.css       # Button block styles
│   └── custom/              # Custom block styles
│       ├── hero.css         # Hero block styles
│       ├── service-cards.css # Service cards block styles
│       ├── team-profiles.css # Team profiles block styles
│       ├── testimonials.css # Testimonials block styles
│       └── cta.css          # Call-to-action block styles
├── utilities/               # Utility classes
│   ├── spacing.css         # Margin and padding utilities
│   ├── layout.css          # Display, flexbox, grid utilities
│   ├── typography.css      # Text styling utilities
│   ├── visibility.css      # Show/hide and accessibility utilities
│   └── interactive-blocks.css # Interactive block utilities and patterns
├── templates/              # Template-specific styles
│   ├── header.css          # Header and navigation
│   ├── footer.css          # Footer styles
│   ├── single.css          # Single post/page styles
│   ├── archive.css         # Archive template styles
│   └── page.css            # Static page styles
├── shared/                 # Shared utility styles
├── admin/                  # WordPress admin specific styles
└── print.css              # Print-specific styles
```

## WordPress Theme Variables System

### Color System
All colors use WordPress theme variables from `theme.json`:

```css
/* Primary brand colors */
--wp--preset--color--primary      /* #000000 - Primary brand color */
--wp--preset--color--secondary    /* #ffffff - Secondary brand color */
--wp--preset--color--accent       /* #007cba - Accent/link color */

/* Base colors */
--wp--preset--color--black        /* #000000 */
--wp--preset--color--white        /* #ffffff */

/* Neutral color scale (50-900) */
--wp--preset--color--neutral-50   /* #fafafa - Lightest neutral */
--wp--preset--color--neutral-100  /* #f5f5f5 */
--wp--preset--color--neutral-200  /* #e5e5e5 */
--wp--preset--color--neutral-300  /* #d4d4d4 */
--wp--preset--color--neutral-400  /* #a3a3a3 */
--wp--preset--color--neutral-500  /* #737373 */
--wp--preset--color--neutral-600  /* #525252 */
--wp--preset--color--neutral-700  /* #404040 */
--wp--preset--color--neutral-800  /* #262626 */
--wp--preset--color--neutral-900  /* #171717 - Darkest neutral */

/* Semantic colors (added during consolidation) */
--wp--preset--color--success      /* #00a32a - Success green */
--wp--preset--color--warning      /* #f0b849 - Warning yellow */
--wp--preset--color--error        /* #d63638 - Error red */
```

**Usage Examples:**
```css
/* ✅ Correct - Use WordPress theme variables */
.button {
    background: var(--wp--preset--color--accent);
    color: var(--wp--preset--color--white);
}

.success-message {
    color: var(--wp--preset--color--success);
    border-color: var(--wp--preset--color--success);
}

/* ✅ Correct - With fallback values for better compatibility */
.link {
    color: var(--wp--preset--color--accent, #007cba);
}

/* ❌ Incorrect - Don't use hardcoded colors */
.button {
    background: #007cba; /* Use var(--wp--preset--color--accent) instead */
    color: #d63638;      /* Use var(--wp--preset--color--error) instead */
}
```

### Typography Scale
```css
/* Font sizes */
--wp--preset--font-size--2xs      /* 0.512rem */
--wp--preset--font-size--xs       /* 0.64rem */
--wp--preset--font-size--small    /* 0.8rem */
--wp--preset--font-size--base     /* 1rem */
--wp--preset--font-size--medium   /* 1.25rem */
--wp--preset--font-size--large    /* 1.563rem */
--wp--preset--font-size--xl       /* 1.953rem */
--wp--preset--font-size--2xl      /* 2.441rem */
--wp--preset--font-size--3xl      /* 3.052rem */

/* Font families */
--wp--preset--font-family--system /* System fonts */
--wp--preset--font-family--body   /* Body text font */
--wp--preset--font-family--heading /* Heading font */
```

**Usage Examples:**
```css
/* ✅ Correct - Use WordPress font size variables */
.heading {
    font-size: var(--wp--preset--font-size--xl);
    font-family: var(--wp--preset--font-family--heading);
}

.small-text {
    font-size: var(--wp--preset--font-size--small);
}

/* ❌ Incorrect - Don't use hardcoded font sizes */
.heading {
    font-size: 1.953rem; /* Use var(--wp--preset--font-size--xl) instead */
}
```

### Spacing Scale
```css
/* Consistent spacing system */
--wp--preset--spacing--2xs        /* 0.512rem */
--wp--preset--spacing--xs         /* 0.64rem */
--wp--preset--spacing--small      /* 0.8rem */
--wp--preset--spacing--base       /* 1rem */
--wp--preset--spacing--medium     /* 1.25rem */
--wp--preset--spacing--large      /* 1.563rem */
--wp--preset--spacing--xl         /* 1.953rem */
--wp--preset--spacing--2xl        /* 2.441rem */
--wp--preset--spacing--3xl        /* 3.052rem */
```

**Usage Examples:**
```css
/* ✅ Correct - Use WordPress spacing variables */
.card {
    padding: var(--wp--preset--spacing--medium);
    margin-bottom: var(--wp--preset--spacing--base);
}

.newsletter-form {
    gap: var(--wp--preset--spacing--xs);
}

/* ❌ Incorrect - Don't use hardcoded spacing */
.card {
    padding: 1.25rem; /* Use var(--wp--preset--spacing--medium) instead */
    margin-bottom: 16px; /* Use var(--wp--preset--spacing--base) instead */
}
```

### Border Radius & Effects
```css
/* Border radius */
--wp--preset--border-radius--small   /* 4px */
--wp--preset--border-radius--medium  /* 8px */
--wp--preset--border-radius--large   /* 16px */
--wp--preset--border-radius--full    /* 50% */

/* Shadows */
--wp--preset--shadow--small
--wp--preset--shadow--medium
--wp--preset--shadow--large
```

## Consolidation Process & History

### Background
The CSS structure was consolidated in **Task 22** to eliminate redundancy and enforce consistent WordPress theme variable usage. This process removed the duplicate `assets/css/frontend/` folder and moved unique content to appropriate locations.

### What Was Consolidated

**Removed Duplicate Content:**
- `frontend/global/variables.css` - Outdated variables using non-WordPress format
- `frontend/style.css` - Duplicate main entry point
- `frontend/editor-style.css` - Empty file
- `frontend/templates/index.css` - Empty file
- `frontend/layout/index.css` - Empty file

**Preserved & Relocated Unique Content:**
- WordPress alignment classes → Moved to `global/base.css`
- Accessibility styles (prefers-reduced-motion) → Moved to `global/base.css`
- Custom media queries → Moved to `global/variables.css`
- Skip link component → New `components/skip-link.css`
- Newsletter form component → New `components/newsletter-form.css`
- Security messages component → New `components/security-messages.css`

**Updated References:**
- Webpack configuration updated to point to new file locations
- All hardcoded colors replaced with WordPress theme variables
- Added semantic colors (success, warning, error) to `theme.json` and variables

### Benefits of Consolidation
- ✅ Eliminated duplicate CSS files and redundant code
- ✅ Enforced consistent WordPress theme variable usage
- ✅ Improved maintainability with single source of truth
- ✅ Better integration with WordPress block editor
- ✅ Reduced build complexity and file size
- ✅ Clear separation of concerns with logical file organization

### Prevention Guidelines
To prevent recreation of duplicate structures:

1. **Always use existing CSS structure** - Check current organization before adding new files
2. **Follow WordPress theme variable standards** - Use `var(--wp--preset--*)` format consistently
3. **Use semantic file organization** - Components go in `components/`, blocks in `blocks/`, etc.
4. **Import order matters** - Follow the order in `main.css` (global → components → blocks → templates → utilities)
5. **Avoid hardcoded values** - Always use theme variables for colors, spacing, typography

## Component Patterns

### Buttons
```css
/* Base button class */
.btn {
    background: var(--wp--preset--color--accent);
    border: none;
    border-radius: var(--wp--preset--border-radius--small);
    color: var(--wp--preset--color--white);
    cursor: pointer;
    font-size: var(--wp--preset--font-size--base);
    padding: var(--wp--preset--spacing--small) var(--wp--preset--spacing--medium);
    transition: var(--transition-fast);
}

/* Size variants */
.btn--small {
    font-size: var(--wp--preset--font-size--small);
    padding: var(--wp--preset--spacing--xs) var(--wp--preset--spacing--small);
}

.btn--large {
    font-size: var(--wp--preset--font-size--medium);
    padding: var(--wp--preset--spacing--medium) var(--wp--preset--spacing--large);
}

/* Style variants */
.btn--primary {
    background: var(--wp--preset--color--primary);
}

.btn--secondary {
    background: var(--wp--preset--color--secondary);
    color: var(--wp--preset--color--primary);
}

.btn--outline {
    background: transparent;
    border: 2px solid var(--wp--preset--color--accent);
    color: var(--wp--preset--color--accent);
}

/* State classes */
.btn:hover {
    background: var(--wp--preset--color--primary);
}

.btn:focus {
    outline: 2px solid var(--wp--preset--color--accent);
    outline-offset: 2px;
}

.btn:disabled {
    background: var(--wp--preset--color--neutral-300);
    color: var(--wp--preset--color--neutral-500);
    cursor: not-allowed;
}
```

### Message Components (Added During Consolidation)
```css
/* Success message */
.success-message {
    background: color-mix(in srgb, var(--wp--preset--color--success) 10%, white);
    border: 1px solid var(--wp--preset--color--success);
    border-radius: var(--wp--preset--border-radius--small);
    color: var(--wp--preset--color--success);
    padding: var(--wp--preset--spacing--base);
}

/* Error message */
.error-message {
    background: color-mix(in srgb, var(--wp--preset--color--error) 10%, white);
    border: 1px solid var(--wp--preset--color--error);
    border-radius: var(--wp--preset--border-radius--small);
    color: var(--wp--preset--color--error);
    padding: var(--wp--preset--spacing--base);
}

/* Warning message */
.warning-message {
    background: color-mix(in srgb, var(--wp--preset--color--warning) 10%, white);
    border: 1px solid var(--wp--preset--color--warning);
    border-radius: var(--wp--preset--border-radius--small);
    color: var(--wp--preset--color--warning);
    padding: var(--wp--preset--spacing--base);
}
```

### Cards
```css
/* Base card */
.card { /* base styles */ }

/* Card elements */
.card__header
.card__body
.card__footer
.card__title
.card__text

/* Card variants */
.card--bordered
.card--elevated
.card--horizontal

/* Specialized cards */
.feature-card
.team-card
.testimonial-card
.pricing-card
```

## Block Styles

### Core Block Overrides
- Enhanced spacing and typography for core blocks
- Custom style variations using `wp_register_block_style()`
- Responsive improvements for mobile devices

### Custom Block Framework
- Consistent styling patterns for custom blocks
- Style variations support
- Integration with global design system

## Utility Classes

### Spacing Utilities
```css
.mt-{size}    /* margin-top */
.mb-{size}    /* margin-bottom */
.pt-{size}    /* padding-top */
.pb-{size}    /* padding-bottom */
```

### Layout Utilities
```css
.flex         /* display: flex */
.grid         /* display: grid */
.hidden       /* display: none */
.sr-only      /* screen reader only */
```

### Typography Utilities
```css
.text-{size}     /* font-size */
.font-{weight}   /* font-weight */
.text-{color}    /* color */
.text-center     /* text-align: center */
```

## Performance Optimizations

### CSS Organization
- Modular architecture allows for tree-shaking unused styles
- Critical CSS can be inlined for above-the-fold content
- Non-critical styles can be loaded asynchronously

### Custom Properties Benefits
- Reduced CSS file size through variable reuse
- Dynamic theming capabilities
- Better browser performance than preprocessor variables

### WordPress Integration
- Leverages WordPress dependency extraction for optimal loading
- Supports block editor real-time preview
- Compatible with WordPress caching plugins

## Browser Support

### Target Browsers
- Modern browsers supporting CSS custom properties
- Progressive enhancement for older browsers
- Graceful degradation patterns included

### Feature Detection
```css
@supports (display: grid) {
  /* Grid-specific styles */
}
```

## Development Workflow

### 1. Adding New Components
1. Create component file in `components/` directory
2. Follow BEM naming conventions
3. **Use WordPress theme variables consistently**
4. Add responsive behavior using theme breakpoints
5. Import the component in `main.css`
6. Document component usage in this README

### 2. Creating Block Styles
1. Add styles to appropriate `blocks/` subdirectory
2. Register block style variations in PHP
3. **Use WordPress theme variables for colors, spacing, typography**
4. Test in block editor for real-time preview
5. Ensure responsive behavior

### 3. Adding Utilities
1. Add to appropriate utilities file
2. Follow consistent naming patterns
3. **Use WordPress theme variables for values**
4. Consider responsive variants

### 4. Variable Usage Best Practices
```css
/* ✅ Always use WordPress theme variables */
.component {
    color: var(--wp--preset--color--neutral-700);
    font-size: var(--wp--preset--font-size--base);
    margin: var(--wp--preset--spacing--medium);
}

/* ✅ Use fallbacks for critical styles */
.critical-component {
    background: var(--wp--preset--color--primary, #000000);
}

/* ✅ Use semantic colors for UI states */
.form-field--error {
    border-color: var(--wp--preset--color--error);
}

.notification--success {
    color: var(--wp--preset--color--success);
}

/* ❌ Never use hardcoded values */
.bad-component {
    color: #404040; /* Should be var(--wp--preset--color--neutral-700) */
    font-size: 16px; /* Should be var(--wp--preset--font-size--base) */
    margin: 20px; /* Should be var(--wp--preset--spacing--medium) */
}
```

## Testing Guidelines

### Cross-Browser Testing
- Test in all target browsers
- Verify custom property fallbacks
- Check responsive behavior

### WordPress Integration Testing
- Test in block editor
- Verify theme.json synchronization
- Check block style variations
- Test with various content scenarios

### Performance Testing
- Monitor CSS file sizes
- Test loading performance
- Verify critical CSS extraction

## Maintenance

### Updating Variables
1. Modify values in `global/variables.css`
2. Update corresponding `theme.json` values
3. Test across all components
4. Update documentation if needed

### Adding New Breakpoints
1. Add to variables file
2. Update utility classes if needed
3. Test existing responsive patterns
4. Document new breakpoint usage

### Refactoring Components
1. Maintain BEM naming conventions
2. Preserve public API (CSS classes)
3. Update documentation
4. Test thoroughly across site

## Troubleshooting

### Common Issues
- **Custom properties not working**: Check browser support and fallbacks
- **Styles not applying**: Verify CSS specificity and load order
- **Block editor differences**: Ensure editor styles are included
- **Responsive issues**: Check media query order and mobile-first approach

### Debugging Tips
- Use browser dev tools to inspect custom property values
- Check CSS cascade and specificity
- Verify webpack build output
- Test with WordPress debug mode enabled

## Resources

- [WordPress Block Theme Documentation](https://developer.wordpress.org/themes/block-themes/)
- [CSS Custom Properties Guide](https://developer.mozilla.org/en-US/docs/Web/CSS/--*)
- [BEM Methodology](http://getbem.com/)
- [Modern CSS Architecture](https://www.madebymike.com.au/writing/css-architecture-for-modern-web-applications/) 