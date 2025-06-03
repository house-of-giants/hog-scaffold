[Documentation](../README.md) > [Blocks](README.md) > Layout Blocks System

# WordPress Layout Blocks System

A comprehensive system for creating consistent, utility-based layout blocks in WordPress.

## Overview

The Layout Blocks System provides a framework for building WordPress blocks with consistent spacing, backgrounds, and responsive behavior. It includes:

- **Utility Components** - Reusable React components for common controls
- **CSS Utilities** - Standardized classes for spacing, backgrounds, and responsive design
- **Helper Functions** - JavaScript utilities for generating classes and styles
- **Block Templates** - Starting points for new blocks

## Table of Contents

- [System Architecture](#system-architecture)
- [Utility Components](#utility-components)
- [CSS Framework](#css-framework)
- [Helper Functions](#helper-functions)
- [Block Development](#block-development)
- [Customization](#customization)
- [Migration Guide](#migration-guide)

## System Architecture

### Directory Structure

```
inc/blocks/layout/
├── utils/                    # Shared utilities
│   ├── components/          # React components
│   │   ├── SpacingControls.js
│   │   ├── BackgroundControls.js
│   │   └── ResponsiveControls.js
│   ├── helpers/             # JavaScript utilities
│   │   ├── spacing.js
│   │   ├── background.js
│   │   └── responsive.js
│   ├── css/                 # CSS utilities
│   │   ├── spacing.css
│   │   ├── background.css
│   │   └── responsive.css
│   └── index.js            # Main export file
├── _template/              # Block template
├── container/              # Individual blocks
├── spacer/
├── divider/
└── index.js               # Registration file
```

### Design Principles

1. **Consistency** - All blocks use the same spacing scale and design tokens
2. **Modularity** - Components and utilities are reusable across blocks
3. **Accessibility** - ARIA labels and semantic HTML by default
4. **Performance** - CSS classes over inline styles where possible
5. **Developer Experience** - Clear APIs and helpful documentation

## Utility Components

### SpacingControls

Provides consistent margin and padding controls:

```javascript
import { SpacingControls } from "../../utils/index.js";

<SpacingControls
	values={spacing}
	onChange={(value) => setAttributes({ spacing: value })}
	title="Spacing"
	showMargin={true}
	showPadding={true}
	showResponsive={true}
/>;
```

**Props:**

- `values` - Current spacing values object
- `onChange` - Callback function for value changes
- `title` - Panel title (default: "Spacing")
- `showMargin` - Show margin controls (default: true)
- `showPadding` - Show padding controls (default: true)
- `showResponsive` - Show responsive breakpoint tabs (default: false)

**Spacing Object Structure:**

```javascript
{
  margin: {
    top: "medium",
    right: "medium",
    bottom: "medium",
    left: "medium"
  },
  padding: {
    top: "medium",
    right: "medium",
    bottom: "medium",
    left: "medium"
  },
  responsive: {
    tablet: { margin: {...}, padding: {...} },
    mobile: { margin: {...}, padding: {...} }
  }
}
```

### BackgroundControls

Handles background colors, images, and effects:

```javascript
import { BackgroundControls } from "../../utils/index.js";

<BackgroundControls
	values={background}
	onChange={(value) => setAttributes({ background: value })}
	title="Background"
	showColor={true}
	showImage={true}
	showOverlay={true}
/>;
```

**Props:**

- `values` - Current background values object
- `onChange` - Callback function for value changes
- `title` - Panel title (default: "Background")
- `showColor` - Show color picker (default: true)
- `showImage` - Show image upload (default: true)
- `showOverlay` - Show overlay controls (default: true)

**Background Object Structure:**

```javascript
{
  color: "#ffffff",
  image: {
    id: 123,
    url: "image-url.jpg",
    alt: "Alt text"
  },
  overlay: {
    enabled: true,
    color: "#000000",
    opacity: 0.5
  },
  position: "center center",
  size: "cover",
  repeat: "no-repeat"
}
```

### ResponsiveControls

Wrapper for responsive breakpoint controls:

```javascript
import { ResponsiveControls } from "../../utils/index.js";

<ResponsiveControls
	breakpoints={["desktop", "tablet", "mobile"]}
	activeBreakpoint={activeBreakpoint}
	onChange={setActiveBreakpoint}
>
	{/* Your responsive controls here */}
</ResponsiveControls>;
```

## CSS Framework

### Spacing Scale

The system uses a consistent spacing scale based on a 8px grid:

```css
:root {
	--spacing-none: 0;
	--spacing-xs: 0.25rem; /* 4px */
	--spacing-sm: 0.5rem; /* 8px */
	--spacing-md: 1rem; /* 16px */
	--spacing-lg: 1.5rem; /* 24px */
	--spacing-xl: 2rem; /* 32px */
	--spacing-2xl: 3rem; /* 48px */
	--spacing-3xl: 4rem; /* 64px */
	--spacing-4xl: 6rem; /* 96px */
	--spacing-5xl: 8rem; /* 128px */
}
```

### Spacing Classes

Generated classes for margins and padding:

```css
/* Margin classes */
.hog-m-none {
	margin: 0;
}
.hog-m-xs {
	margin: var(--spacing-xs);
}
.hog-m-sm {
	margin: var(--spacing-sm);
}
/* ... continues for all spacing values */

/* Directional margins */
.hog-mt-md {
	margin-top: var(--spacing-md);
}
.hog-mr-lg {
	margin-right: var(--spacing-lg);
}
.hog-mb-xl {
	margin-bottom: var(--spacing-xl);
}
.hog-ml-sm {
	margin-left: var(--spacing-sm);
}

/* Padding classes */
.hog-p-md {
	padding: var(--spacing-md);
}
.hog-pt-lg {
	padding-top: var(--spacing-lg);
}
/* ... continues for all variations */
```

### Responsive Classes

Breakpoint-specific spacing classes:

```css
/* Tablet breakpoint */
@media (max-width: 1024px) {
	.hog-m-md\@tablet {
		margin: var(--spacing-md);
	}
	.hog-p-lg\@tablet {
		padding: var(--spacing-lg);
	}
}

/* Mobile breakpoint */
@media (max-width: 768px) {
	.hog-m-sm\@mobile {
		margin: var(--spacing-sm);
	}
	.hog-p-md\@mobile {
		padding: var(--spacing-md);
	}
}
```

### Background Classes

Utility classes for backgrounds:

```css
.hog-bg-color {
	background-color: var(--background-color);
}
.hog-bg-image {
	background-image: var(--background-image);
}
.hog-bg-overlay::before {
	content: "";
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: var(--overlay-color);
	opacity: var(--overlay-opacity);
}
```

## Helper Functions

### Spacing Helpers

Generate spacing classes and styles:

```javascript
import {
	getSpacingClasses,
	getSpacingStyles,
	mergeSpacingValues,
} from "../../utils/index.js";

// Generate CSS classes
const classes = getSpacingClasses(spacing);
// Result: "hog-mt-lg hog-mb-md hog-p-xl"

// Generate inline styles for complex cases
const styles = getSpacingStyles(spacing);
// Result: { marginTop: "1.5rem", marginBottom: "1rem", padding: "2rem" }

// Merge responsive values
const mergedSpacing = mergeSpacingValues(spacing, "tablet");
```

### Background Helpers

Generate background styles and classes:

```javascript
import {
	getBackgroundStyles,
	getBackgroundClasses,
	processBackgroundImage,
} from "../../utils/index.js";

// Generate background styles
const styles = getBackgroundStyles(background);
// Result: { backgroundColor: "#fff", backgroundImage: "url(...)" }

// Generate CSS classes
const classes = getBackgroundClasses(background);
// Result: "hog-bg-color hog-bg-image hog-bg-overlay"

// Process background image object
const processedImage = processBackgroundImage(background.image);
```

### Responsive Helpers

Handle responsive breakpoints:

```javascript
import {
	getCurrentBreakpoint,
	getResponsiveValue,
	generateResponsiveClasses,
} from "../../utils/index.js";

// Get current breakpoint
const breakpoint = getCurrentBreakpoint();

// Get value for specific breakpoint
const spacing = getResponsiveValue(spacingValues, "tablet");

// Generate responsive classes
const classes = generateResponsiveClasses(values, "spacing");
```

## Block Development

### Creating a New Block

1. **Copy the template:**

   ```bash
   cp -r inc/blocks/layout/_template inc/blocks/layout/my-block
   ```

2. **Update `block.json`:**

   ```json
   {
   	"name": "hog-scaffold/my-block",
   	"title": "My Block",
   	"description": "Description of my block",
   	"category": "hog-scaffold-blocks",
   	"attributes": {
   		"spacing": {
   			"type": "object",
   			"default": {}
   		},
   		"background": {
   			"type": "object",
   			"default": {}
   		}
   	}
   }
   ```

3. **Update the edit component:**

   ```javascript
   import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
   import { PanelBody } from "@wordpress/components";
   import { __ } from "@wordpress/i18n";
   import {
   	SpacingControls,
   	BackgroundControls,
   	getSpacingClasses,
   	getBackgroundStyles,
   } from "../../utils/index.js";

   export default function Edit({ attributes, setAttributes }) {
   	const { spacing, background } = attributes;

   	const blockProps = useBlockProps({
   		className: `hog-my-block ${getSpacingClasses(spacing)}`,
   		style: getBackgroundStyles(background),
   	});

   	return (
   		<>
   			<InspectorControls>
   				<PanelBody title={__("Spacing", "hog-scaffold")}>
   					<SpacingControls
   						values={spacing}
   						onChange={(value) => setAttributes({ spacing: value })}
   					/>
   				</PanelBody>

   				<PanelBody title={__("Background", "hog-scaffold")}>
   					<BackgroundControls
   						values={background}
   						onChange={(value) => setAttributes({ background: value })}
   					/>
   				</PanelBody>
   			</InspectorControls>

   			<div {...blockProps}>{/* Your block content */}</div>
   		</>
   	);
   }
   ```

4. **Add to build system:**
   ```javascript
   // config/webpack.settings.cjs
   'my-block-editor': './inc/blocks/layout/my-block/index.js',
   'my-block-style': './inc/blocks/layout/my-block/style.css',
   ```

### Block Registration

Register blocks in `inc/blocks/layout/index.js`:

```javascript
import "./my-block/index.js";
```

Or register programmatically:

```javascript
import { registerBlockType } from "@wordpress/blocks";
import metadata from "./my-block/block.json";
import Edit from "./my-block/Edit";

registerBlockType(metadata, {
	edit: Edit,
});
```

### Adding Custom Attributes

Extend the standard spacing and background attributes:

```json
{
	"attributes": {
		"spacing": {
			"type": "object",
			"default": {}
		},
		"background": {
			"type": "object",
			"default": {}
		},
		"customAttribute": {
			"type": "string",
			"default": "default-value"
		},
		"enableFeature": {
			"type": "boolean",
			"default": false
		}
	}
}
```

## Customization

### Adding Custom Spacing Values

Extend the spacing scale in CSS:

```css
:root {
	--spacing-custom: 2.5rem; /* Custom spacing value */
}

.hog-m-custom {
	margin: var(--spacing-custom);
}
```

Update the spacing options in JavaScript:

```javascript
// utils/helpers/spacing.js
export const spacingOptions = [
	{ label: "None", value: "none" },
	{ label: "XS", value: "xs" },
	{ label: "Small", value: "sm" },
	{ label: "Medium", value: "md" },
	{ label: "Large", value: "lg" },
	{ label: "XL", value: "xl" },
	{ label: "Custom", value: "custom" }, // Add custom option
];
```

### Custom Background Types

Add support for gradient backgrounds:

```javascript
// utils/components/BackgroundControls.js
import { GradientPicker } from "@wordpress/components";

// Add gradient picker to the component
<GradientPicker
	value={background.gradient}
	onChange={(gradient) => onChange({ ...background, gradient })}
/>;
```

### Extending Utility Components

Create custom control components:

```javascript
// utils/components/CustomControls.js
import { BaseControl, SelectControl } from "@wordpress/components";

export function CustomControls({
	values,
	onChange,
	title = "Custom Settings",
}) {
	return (
		<BaseControl label={title}>
			<SelectControl
				value={values.customProperty}
				options={[
					{ label: "Option 1", value: "option1" },
					{ label: "Option 2", value: "option2" },
				]}
				onChange={(value) => onChange({ ...values, customProperty: value })}
			/>
		</BaseControl>
	);
}
```

## Migration Guide

### From Inline Styles to CSS Classes

**Before:**

```javascript
<div style={{ margin: "1rem", padding: "2rem" }}>
```

**After:**

```javascript
import { getSpacingClasses } from "../../utils/index.js";

const spacing = {
  margin: { top: "md", bottom: "md" },
  padding: { top: "xl", bottom: "xl" }
};

<div className={getSpacingClasses(spacing)}>
```

### From Manual Controls to Utility Components

**Before:**

```javascript
<RangeControl
	label="Margin Top"
	value={marginTop}
	onChange={setMarginTop}
	min={0}
	max={100}
/>
```

**After:**

```javascript
<SpacingControls values={spacing} onChange={setSpacing} title="Spacing" />
```

### Updating Existing Blocks

1. **Add utility attributes to block.json**
2. **Import utility components in edit.js**
3. **Replace manual controls with utility components**
4. **Update className generation**
5. **Test responsive behavior**

## Performance Considerations

### CSS Class Generation

- CSS classes are preferred over inline styles for better performance
- Classes are cached and reused across multiple blocks
- Responsive classes are only loaded when needed

### JavaScript Bundle Size

- Import only the utilities you need
- Use tree shaking to remove unused code
- Consider lazy loading for complex components

### CSS Bundle Optimization

- Utilities are split into separate CSS files
- Only include necessary breakpoints
- Use CSS custom properties for dynamic values

## Browser Support

The Layout Blocks System supports:

- **Modern Browsers:** Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
- **CSS Features:** Custom properties, Grid, Flexbox
- **JavaScript:** ES6+ features with Babel transpilation

## Best Practices

### Block Development

1. **Start with the template** - Use `_template` as a starting point
2. **Follow naming conventions** - Use consistent CSS class prefixes
3. **Test responsive behavior** - Check all breakpoints
4. **Add accessibility features** - Include ARIA labels and semantic HTML
5. **Document custom attributes** - Add clear comments and examples

### CSS Organization

1. **Use utility classes** - Prefer classes over inline styles
2. **Follow the spacing scale** - Don't create custom spacing values unless necessary
3. **Group related styles** - Keep component styles together
4. **Use CSS custom properties** - For dynamic values and theming

### Performance

1. **Minimize inline styles** - Use CSS classes when possible
2. **Optimize images** - Use appropriate formats and sizes
3. **Lazy load resources** - Don't load everything upfront
4. **Test on devices** - Check performance on mobile devices

## Troubleshooting

### Common Issues

**Spacing classes not working:**

- Check if CSS utilities are properly enqueued
- Verify class names match the generated classes
- Ensure spacing values are valid

**Responsive controls not showing:**

- Check if `showResponsive={true}` is set
- Verify breakpoint configuration
- Test on different screen sizes

**Background images not loading:**

- Check image upload permissions
- Verify image URLs are correct
- Test with different image formats

### Debugging Tips

1. **Use browser dev tools** - Inspect generated classes and styles
2. **Check console errors** - Look for JavaScript errors
3. **Verify build process** - Ensure webpack compilation succeeds
4. **Test with default values** - Start with simple configurations

## Contributing

To contribute to the Layout Blocks System:

1. **Follow coding standards** - Use ESLint and Prettier configurations
2. **Add tests** - Include unit tests for new utilities
3. **Update documentation** - Keep this guide current
4. **Test thoroughly** - Check all supported browsers and devices

## Resources

- [WordPress Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [React Documentation](https://reactjs.org/docs)
- [CSS Grid Guide](https://css-tricks.com/snippets/css/complete-guide-grid/)
- [Accessibility Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)

---

## See Also

- [Quick Start Guide](layout-blocks-quick-start.md) - Get started quickly
- [Interactive Block Patterns](interactive-block-patterns.md) - Advanced patterns
- [Block Development Guide](block-development-guide.md) - General block development
- [Customization Guide](../customization/README.md) - Theme customization

---

**[⬅️ Back to Blocks](README.md)** | **[➡️ Next: Interactive Block Patterns](interactive-block-patterns.md)**
