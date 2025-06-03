[Documentation](../README.md) > [Blocks](README.md) > Block Development Guide

# WordPress Block Development Guide - HoG Scaffold

This guide documents the modern WordPress block development standards and practices implemented in the HoG Scaffold theme, following 2025 WordPress best practices.

## Table of Contents

1. [Project Structure](#project-structure)
2. [Block Metadata Structure](#block-metadata-structure)
3. [Modern JavaScript Implementation](#modern-javascript-implementation)
4. [Block Variations](#block-variations)
5. [Internationalization](#internationalization)
6. [CSS Organization](#css-organization)
7. [Build Process](#build-process)
8. [Development Workflow](#development-workflow)

## Project Structure

All blocks are located in the `inc/blocks/` directory with a consistent structure:

```
inc/blocks/
├── hero-block/
│   ├── block.json          # Block metadata (API v3)
│   ├── index.js           # Block registration
│   ├── edit.js            # Editor component
│   ├── save.js            # Save component
│   ├── index.css          # Block styles
│   ├── markup.php         # PHP render template
│   └── register.php       # PHP registration
├── cta-block/
├── service-cards-block/
├── testimonials-block/
├── team-profiles-block/
└── example-block/
```

## Block Metadata Structure

All blocks use **API Version 3** with comprehensive metadata defined in `block.json`:

### Required Properties

```json
{
	"$schema": "https://schemas.wp.org/trunk/block.json",
	"apiVersion": 3,
	"name": "hog-scaffold/block-name",
	"version": "1.0.0",
	"title": "Block Title",
	"category": "hog-scaffold",
	"description": "Block description",
	"textdomain": "hog-scaffold",
	"supports": {
		// Modern supports configuration
	}
}
```

### Modern Supports Configuration

All blocks include comprehensive supports for WordPress 6.0+ features:

```json
"supports": {
  "html": false,
  "anchor": true,
  "align": ["left", "center", "right", "wide", "full"],
  "color": {
    "gradients": true,
    "background": true,
    "text": true,
    "link": true
  },
  "typography": {
    "fontSize": true,
    "lineHeight": true,
    "__experimentalFontFamily": true,
    "__experimentalFontWeight": true,
    "__experimentalFontStyle": true,
    "__experimentalTextTransform": true,
    "__experimentalTextDecoration": true,
    "__experimentalLetterSpacing": true,
    "__experimentalDefaultControls": {
      "fontSize": true,
      "lineHeight": true
    }
  },
  "spacing": {
    "padding": true,
    "margin": true,
    "blockGap": true,
    "__experimentalDefaultControls": {
      "padding": true,
      "margin": true
    }
  },
  "position": {
    "sticky": true
  },
  "dimensions": {
    "minHeight": true
  },
  "__experimentalBorder": {
    "color": true,
    "radius": true,
    "style": true,
    "width": true
  },
  "lock": true,
  "reusable": true,
  "ariaLabel": true
}
```

## Modern JavaScript Implementation

### Block Registration

All blocks use clean, modern registration with metadata from block.json:

```javascript
import { registerBlockType } from "@wordpress/blocks";
import edit from "./edit.js";
import save from "./save.js";
import metadata from "./block.json";

registerBlockType(metadata.name, {
	edit,
	save,
});
```

### Component Structure

All blocks use modern functional components with hooks:

```javascript
import { __ } from "@wordpress/i18n";
import {
	useBlockProps,
	RichText,
	InspectorControls,
} from "@wordpress/block-editor";
import { PanelBody, SelectControl } from "@wordpress/components";

const BlockEdit = ({ attributes, setAttributes }) => {
	const { title, layout } = attributes;

	const blockProps = useBlockProps({
		className: `wp-block-name is-style-${layout}`,
	});

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Settings", "hog-scaffold")}>
					{/* Controls */}
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>{/* Block content */}</div>
		</>
	);
};

export default BlockEdit;
```

### Modern JavaScript Features Used

- **ES6+ syntax**: Arrow functions, destructuring, template literals
- **Hooks**: useState, useBlockProps from WordPress
- **Import/Export**: ES6 modules with explicit .js extensions
- **Modern React patterns**: Functional components, props destructuring

## Block Variations

Block variations provide users with different starting configurations. Defined in block.json:

```json
"variations": [
  {
    "name": "minimal",
    "title": "Minimal Hero",
    "description": "A clean, minimal hero section",
    "attributes": {
      "layout": "minimal",
      "minHeight": "40vh"
    },
    "scope": ["inserter", "transform"]
  },
  {
    "name": "split",
    "title": "Split Layout Hero",
    "description": "Hero with split content layout",
    "attributes": {
      "layout": "split"
    },
    "scope": ["inserter"]
  }
]
```

### Implemented Variations

- **Hero Block**: minimal, split
- **CTA Block**: minimal, gradient, split
- **Testimonials**: carousel, minimal, cards
- **Service Cards**: minimal, shadow, list
- **Team Profiles**: minimal, carousel, list
- **Example Block**: simple, advanced

## Internationalization

### JavaScript Internationalization

All user-facing strings use the `__()` function with the correct textdomain:

```javascript
import { __ } from "@wordpress/i18n";

// Correct usage
placeholder={__("Enter heading...", "hog-scaffold")}
label={__("Layout Settings", "hog-scaffold")}
```

### PHP Internationalization

PHP files use WordPress i18n functions:

```php
<?php
echo esc_html(__('Default title', 'hog-scaffold'));
printf(
  esc_html(__('%d out of 5 stars', 'hog-scaffold')),
  $rating
);
?>
```

### Translation Files

- **POT file**: `languages/hog-scaffold.pot` contains all translatable strings
- **Translation workflow**: Use POEdit or similar tools to create language-specific PO files
- **Textdomain**: `hog-scaffold` used consistently across all files

## CSS Organization

### Block-Specific CSS

Each block has its own CSS file (`index.css`) with:

- **Editor styles**: For the block editor
- **Variation styles**: Support for all defined variations
- **Responsive design**: Mobile-first approach
- **Modern CSS**: CSS custom properties, Grid, Flexbox

### CSS Class Structure

```css
/* Block base styles */
.wp-block-hero {
	/* Base styles */
}

/* Variation styles */
.wp-block-hero.is-style-minimal {
	/* Minimal variation styles */
}

.wp-block-hero.is-style-split {
	/* Split variation styles */
}

/* Responsive styles */
@media (max-width: 768px) {
	/* Mobile styles */
}
```

### Layout Variations

Service cards block demonstrates multiple layout support:

```css
.wp-block-service-cards.layout-grid .wp-block-service-cards__grid {
	grid-template-columns: repeat(var(--columns, 3), 1fr);
}

.wp-block-service-cards.layout-list .wp-block-service-cards__grid {
	grid-template-columns: 1fr;
}

.wp-block-service-cards.layout-carousel .wp-block-service-cards__grid {
	display: flex;
	overflow-x: auto;
	scroll-snap-type: x mandatory;
}
```

## Build Process

### Webpack Configuration

Modern JavaScript support without TypeScript complexity:

- **Babel presets**: @babel/preset-env, @babel/preset-react
- **JSX support**: Automatic JSX runtime
- **Modern features**: ES6+, async/await, optional chaining
- **Polyfills**: core-js 3 for compatibility

### Build Commands

```bash
npm run dev      # Development build
npm run build    # Production build
npm run watch    # Development with file watching
npm run lint     # Code quality checks
```

### Asset Handling

- **JavaScript**: Bundled and optimized
- **CSS**: PostCSS processing with autoprefixer
- **Dependencies**: WordPress externals handled automatically
- **Caching**: Filesystem cache for faster builds

## Development Workflow

### Creating a New Block

1. **Create directory structure**:

   ```bash
   mkdir inc/blocks/my-new-block
   cd inc/blocks/my-new-block
   ```

2. **Create block.json** with modern metadata structure

3. **Create JavaScript files**:

   - `index.js` - Registration
   - `edit.js` - Editor component
   - `save.js` - Save component

4. **Create CSS file**: `index.css` with styles

5. **Create PHP files**:

   - `register.php` - Server-side registration
   - `markup.php` - Render template

6. **Register block** in main theme functions

### Development Best Practices

1. **Use modern JavaScript**: ES6+, functional components, hooks
2. **Implement variations**: Provide multiple starting configurations
3. **Add comprehensive supports**: Enable WordPress features
4. **Include internationalization**: All strings must be translatable
5. **Write responsive CSS**: Mobile-first approach
6. **Test thoroughly**: All variations and responsive breakpoints
7. **Follow naming conventions**: Consistent class names and structure

### Code Quality

- **ESLint**: Modern JavaScript linting rules
- **Stylelint**: CSS linting and formatting
- **WordPress standards**: Follow WordPress coding standards
- **Accessibility**: Ensure blocks are accessible (ARIA labels, keyboard navigation)

## Accessibility Considerations

All blocks implement accessibility features:

- **ARIA labels**: Descriptive labels for interactive elements
- **Keyboard navigation**: All controls accessible via keyboard
- **Color contrast**: Adequate contrast ratios
- **Screen reader support**: Semantic HTML structure

Example accessibility implementation:

```javascript
// ARIA labels in controls
<SelectControl
  label={__("Layout", "hog-scaffold")}
  value={layout}
  options={layoutOptions}
  onChange={(value) => setAttributes({ layout: value })}
/>

// Semantic HTML in output
<div {...blockProps} role="banner" aria-label={__("Hero section", "hog-scaffold")}>
  <h1 className="wp-block-hero__title">{title}</h1>
</div>
```

## Performance Optimization

### JavaScript Optimization

- **Tree shaking**: Unused code eliminated
- **Code splitting**: Separate bundles for editor and frontend
- **Dependency extraction**: WordPress dependencies externalized
- **Caching**: Build-time caching for faster development

### CSS Optimization

- **PostCSS**: Modern CSS feature support
- **Autoprefixer**: Automatic vendor prefixes
- **Minification**: Production CSS minified
- **Critical CSS**: Block-specific styles loaded only when needed

## Testing Checklist

When developing or updating blocks:

- [ ] Block registers correctly in editor
- [ ] All variations work as expected
- [ ] Responsive design functions properly
- [ ] Internationalization strings are translatable
- [ ] Accessibility features work correctly
- [ ] Build process completes without errors
- [ ] CSS styles apply correctly
- [ ] JavaScript functionality works in editor and frontend
- [ ] Block supports work as expected
- [ ] Performance is acceptable

This guide represents the current state of block development in the HoG Scaffold theme, following WordPress 6.0+ best practices and modern development standards.

## See Also

- [Blocks Overview](README.md) - Overview of block development
- [Block Patterns Documentation](block-patterns.md) - Available block patterns
- [Block Patterns Testing](block-patterns-testing.md) - Testing procedures
- [Build System Guide](../getting-started/build-system.md) - Development workflow

**[⬅️ Back to Documentation Index](../README.md)** | **[➡️ Next: Block Patterns](block-patterns.md)**
