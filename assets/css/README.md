# CSS Architecture Documentation

## Overview

This WordPress theme implements a modern, scalable CSS architecture using custom properties, BEM methodology, and a component-based approach. The styles are organized into logical modules and build upon WordPress block theme standards.

## Architecture Principles

### 1. Custom Properties System
- All design tokens are defined as CSS custom properties in `global/variables.css`
- Properties are synced with `theme.json` for WordPress block editor compatibility
- Variables follow WordPress naming conventions: `var(--wp--preset--[type]--[name])`

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
├── main.css                    # Main entry point
├── global/                     # Global styles and variables
│   ├── variables.css          # Design tokens and custom properties
│   └── base.css              # Reset, typography, and foundational styles
├── components/                # Reusable UI components
│   ├── buttons.css           # Button styles and variants
│   ├── forms.css             # Form controls and layouts
│   ├── navigation.css        # Navigation components
│   └── cards.css            # Card components and variants
├── blocks/                   # WordPress block styles
│   ├── core/                # Core WordPress blocks
│   │   ├── group.css        # Group block styles
│   │   ├── columns.css      # Columns block styles
│   │   └── button.css       # Button block styles
│   └── custom/              # Custom block styles
│       ├── hero.css         # Hero block styles
│       └── testimonial.css  # Testimonial block styles
├── utilities/               # Utility classes
│   ├── spacing.css         # Margin and padding utilities
│   ├── layout.css          # Display, flexbox, grid utilities
│   ├── typography.css      # Text styling utilities
│   └── visibility.css      # Show/hide and accessibility utilities
└── templates/              # Template-specific styles
    ├── header.css          # Header and navigation
    ├── footer.css          # Footer styles
    ├── home.css            # Homepage specific styles
    ├── blog.css            # Blog archive styles
    ├── single.css          # Single post/page styles
    ├── archive.css         # Archive template styles
    └── page.css            # Static page styles
```

## Variable System

### Color System
```css
/* Primary brand colors */
--wp--preset--color--primary
--wp--preset--color--secondary  
--wp--preset--color--accent

/* Neutral color scale */
--wp--preset--color--neutral-50 to --wp--preset--color--neutral-900

/* Semantic colors */
--wp--preset--color--success
--wp--preset--color--warning
--wp--preset--color--error
```

### Typography Scale
```css
/* Font sizes */
--wp--preset--font-size--xs to --wp--preset--font-size--5xl

/* Font families */
--wp--preset--font-family--body
--wp--preset--font-family--heading
```

### Spacing Scale
```css
/* Consistent spacing system */
--wp--preset--spacing--xs to --wp--preset--spacing--3xl
```

## Component Patterns

### Buttons
```css
/* Base button class */
.btn { /* base styles */ }

/* Size variants */
.btn--small
.btn--large

/* Style variants */
.btn--primary
.btn--secondary
.btn--outline

/* State classes */
.btn:hover
.btn:focus
.btn:disabled
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
3. Use existing custom properties
4. Add responsive behavior
5. Document component usage

### 2. Creating Block Styles
1. Add styles to appropriate `blocks/` subdirectory
2. Register block style variations in PHP
3. Test in block editor
4. Ensure responsive behavior

### 3. Adding Utilities
1. Add to appropriate utilities file
2. Follow consistent naming patterns
3. Use custom properties for values
4. Consider responsive variants

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