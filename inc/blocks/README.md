# WordPress Layout Blocks System

This document describes the custom layout blocks system built for this WordPress theme. The system provides a flexible foundation for creating and organizing content layouts.

## Overview

The layout blocks system consists of:

- **Reusable utility components** for consistent controls across blocks
- **Layout-specific blocks** for organizing content (Container, Spacer, Divider)
- **Comprehensive build system** integration with webpack
- **Consistent naming conventions** and code structure

## Available Blocks

### Container Block (`hog-scaffold/container`)

A flexible container block for organizing and styling content sections.

**Features:**
- Width controls (narrow, standard, wide, full)
- Advanced spacing controls (margin/padding with custom values)
- Background settings (colors, images, overlays)
- Content alignment options
- Minimum height settings
- Block variations for common use cases

**Usage:**
```javascript
// The container block accepts inner blocks
<Container width="standard" contentAlignment="center">
  <Heading>Welcome</Heading>
  <Paragraph>Your content here</Paragraph>
</Container>
```

### Spacer Block (`hog-scaffold/spacer`)

Adds vertical spacing between content sections.

**Features:**
- Preset height options (small, medium, large, xl)
- Custom height values
- Separate mobile/desktop heights
- Block variations for quick insertion

**Usage:**
```javascript
// Simple spacer with medium height
<Spacer height="medium" />

// Custom height with mobile override
<Spacer height="custom" customHeight={80} mobileHeight="medium" />
```

### Divider Block (`hog-scaffold/divider`)

Visual separator lines between content sections.

**Features:**
- Multiple line styles (solid, dashed, dotted, double)
- Customizable width and height
- Color picker integration
- Spacing controls
- Block variations

**Usage:**
```javascript
// Basic divider
<Divider style="solid" width="100" />

// Styled divider with custom color
<Divider style="dashed" width="50" height={2} color="#333" />
```

## Utility System

### SpacingControls Component

Provides consistent spacing controls across all blocks.

**Props:**
- `values` - Current spacing values object
- `onChange` - Callback when values change
- `title` - Panel title (default: "Spacing")
- `showMargin` - Whether to show margin controls (default: true)
- `showPadding` - Whether to show padding controls (default: true)

**Features:**
- Linked spacing mode for synchronized values
- Preset options (small, medium, large, xl, custom)
- Custom pixel values with range controls
- Separate controls for each direction

### BackgroundControls Component

Provides comprehensive background settings.

**Props:**
- `values` - Current background values object
- `onChange` - Callback when values change
- `title` - Panel title (default: "Background")

**Features:**
- Color picker with theme palette integration
- Image upload with media library
- Background size/position/repeat controls
- Overlay system with opacity controls
- WordPress media component integration

### Helper Functions

#### Spacing Helpers (`utils/helpers/spacing.js`)

- `getSpacingClass(property, direction, value)` - Generate CSS class names
- `getSpacingStyles(spacing)` - Generate inline styles for custom values
- `getSpacingClasses(spacing)` - Generate all spacing classes
- `DEFAULT_SPACING` - Default spacing configuration

#### Background Helpers (`utils/helpers/background.js`)

- `getBackgroundStyles(background)` - Generate background CSS styles
- `getOverlayStyles(background)` - Generate overlay styles
- `getBackgroundClasses(background)` - Generate background CSS classes
- `needsRelativePositioning(background)` - Check if relative positioning needed
- `DEFAULT_BACKGROUND` - Default background configuration

## Development Workflow

### Creating New Layout Blocks

1. **Create block directory structure:**
   ```
   inc/blocks/layout/your-block/
   ├── block.json
   ├── edit.js
   ├── save.js
   ├── index.js
   ├── style.css
   └── editor.css (optional)
   ```

2. **Configure block.json:**
   ```json
   {
     "name": "hog-scaffold/your-block",
     "title": "Your Block",
     "category": "hog-scaffold-blocks",
     "editorScript": "file:../../../js/your-block-editor.js",
     "style": "file:../../../css/your-block-style.css"
   }
   ```

3. **Add webpack entries in `config/webpack.settings.cjs`:**
   ```javascript
   'your-block-editor': './inc/blocks/layout/your-block/index.js',
   'your-block-style': './inc/blocks/layout/your-block/style.css',
   ```

4. **Import in layout index:**
   ```javascript
   // inc/blocks/layout/index.js
   import './your-block/index.js';
   ```

### Using Utility Components

```javascript
import { SpacingControls, BackgroundControls } from '../../utils/index.js';
import { 
  getSpacingClasses, 
  getSpacingStyles,
  getBackgroundStyles 
} from '../../utils/index.js';

// In your edit component
<SpacingControls
  values={spacing}
  onChange={(value) => setAttributes({ spacing: value })}
/>

<BackgroundControls
  values={background}
  onChange={(value) => setAttributes({ background: value })}
/>
```

### CSS Utility Classes

The system provides CSS utility classes for common spacing and background patterns:

**Spacing Classes:**
- `.margin-{direction}-{size}` (e.g., `.margin-top-medium`)
- `.padding-{direction}-{size}` (e.g., `.padding-left-large`)

**Background Classes:**
- `.has-background-image`
- `.background-size-{value}` (cover, contain, auto)
- `.background-position-{value}` (center, top-left, etc.)
- `.background-repeat-{value}` (no-repeat, repeat, etc.)

## Build System

### Build Commands

- `npm run build` - Production build
- `npm run watch` - Development with file watching
- `npm run dev` - Development build

### Webpack Configuration

The build system automatically:
- Compiles JavaScript with Babel and React support
- Processes CSS with PostCSS
- Extracts WordPress dependencies
- Generates optimized production bundles
- Provides source maps for development

### File Structure After Build

```
dist/
├── js/
│   ├── layout-container-editor.js
│   ├── layout-spacer-editor.js
│   └── layout-divider-editor.js
└── css/
    ├── layout-container-style.css
    ├── layout-container-editor-style.css
    ├── layout-spacer-style.css
    └── layout-divider-style.css
```

## Best Practices

### Block Development

1. **Use TypeScript-style JSDoc comments** for better IDE support
2. **Implement proper internationalization** with `__()` function
3. **Follow WordPress coding standards** for consistency
4. **Use semantic CSS class names** with BEM methodology
5. **Implement accessibility features** (ARIA labels, keyboard navigation)

### Styling

1. **Use CSS custom properties** for theme integration
2. **Implement responsive design** with mobile-first approach
3. **Leverage utility classes** for common patterns
4. **Provide visual editor feedback** with distinct editor styles

### Performance

1. **Minimize bundle sizes** by importing only needed utilities
2. **Use CSS-in-JS sparingly** - prefer CSS classes where possible
3. **Implement proper caching** through webpack configuration
4. **Optimize images and assets** in the build process

## Testing

### Manual Testing Checklist

- [ ] Block renders correctly in editor
- [ ] Block saves properly to database
- [ ] Frontend output matches editor preview
- [ ] All controls work as expected
- [ ] Responsive behavior functions correctly
- [ ] Accessibility features work properly
- [ ] Block variations insert correctly

### Browser Testing

Test blocks across:
- Chrome/Chromium browsers
- Firefox
- Safari
- Edge
- Mobile browsers (iOS Safari, Chrome Mobile)

## Troubleshooting

### Common Issues

**Build fails with "Module not found" error:**
- Verify webpack entry points in `config/webpack.settings.cjs`
- Check file paths in block.json files
- Ensure imports use correct relative paths

**Block doesn't appear in inserter:**
- Verify block registration in `index.js`
- Check block.json syntax and required fields
- Ensure block is imported in layout/index.js

**Styles not loading:**
- Check CSS file paths in block.json
- Verify webpack CSS entries
- Ensure CSS files are valid

**Controls not working:**
- Verify import paths for utility components
- Check attribute definitions in block.json
- Ensure proper state management in edit component

## Contributing

When contributing new blocks or features:

1. Follow the established naming conventions
2. Update this documentation
3. Add appropriate tests
4. Ensure accessibility compliance
5. Test across supported browsers
6. Update changelog if applicable

## Version History

- **v1.0.0** - Initial layout blocks system with Container, Spacer, and Divider blocks
- Comprehensive utility system with reusable components
- Webpack build integration
- Complete documentation and examples 