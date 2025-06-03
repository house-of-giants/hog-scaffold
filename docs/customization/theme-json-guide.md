# Theme.json Configuration Guide

This document explains the comprehensive theme.json configuration implemented for the HoG Scaffold WordPress theme.

## Overview

The theme.json file provides a centralized configuration for the WordPress block theme, defining colors, typography, spacing, and block-specific settings. This implementation migrates existing CSS custom properties into WordPress's native theme configuration system.

## Version & Schema

- **Version**: 3 (Latest WordPress theme.json version)
- **Schema**: `https://schemas.wp.org/trunk/theme.json`
- **Appearance Tools**: Enabled for enhanced editor controls

## Design System Migration

### Colors

The color palette migrates and enhances the existing CSS custom properties:

**Original CSS Variables:**

- `--c-black: #000000`
- `--c-white: #ffffff`

**Enhanced theme.json Palette:**

- **Brand Colors**: Primary (black), Secondary (white), Accent (#007cba)
- **Neutral Scale**: 10-step scale from neutral-50 (#fafafa) to neutral-900 (#171717)
- **Gradients**: Primary-accent and neutral-fade gradients

### Typography

Complete migration of the modular typography scale:

**Original CSS Variables:**

```css
--fs-2xs: 0.512rem;
--fs-xs: 0.64rem;
--fs-s: 0.8rem;
--fs-base: 1rem;
--fs-m: 1.25rem;
--fs-l: 1.563rem;
--fs-xl: 1.953rem;
--fs-2xl: 2.441rem;
--fs-3xl: 3.052rem;
```

**theme.json Font Sizes:**

- Maintains exact same scale with semantic naming
- Includes font families: System, Body (sans-serif), Heading (serif)
- Enables all typography controls (weight, style, spacing, etc.)

### Spacing

Spacing system mirrors the typography scale:

**Original CSS Variables:**

```css
--sp-2xs to --sp-3xl (matching font scale)
```

**theme.json Spacing:**

- Same modular scale (0.512rem to 3.052rem)
- Spacing scale configuration with 1.25 increment
- Multiple unit support (px, em, rem, vh, vw, %)

### Layout

**Original CSS Variable:**

```css
--w-base: 1280px;
```

**theme.json Layout:**

- Content Size: 1280px (migrated from --w-base)
- Wide Size: 1400px (enhanced for wider layouts)
- Allow Editing: true

## Block Configuration

### Core Blocks Enhanced

1. **Button Block**

   - Custom colors and gradients
   - Typography scale (small, medium, large)
   - Padding and border radius controls
   - "Outline" variation with transparent background

2. **Heading Block**

   - H1-H6 specific font sizes
   - Custom color controls
   - Semantic hierarchy maintained

3. **Paragraph Block**

   - Custom colors
   - Drop cap support

4. **Quote/List Blocks**
   - Spacing controls
   - Typography controls

## Global Styles

### Element Styles

- **Links**: Accent color, underline, proper hover/focus states
- **Headings (H1-H6)**: Complete hierarchy with heading font family
- **Buttons**: Accent background, rounded corners, accessibility-compliant focus states

### Block Styles

- **Quote**: Neutral text, italic styling, left accent border
- **Code**: Light background, monospace font, rounded corners
- **Preformatted**: Dark theme with light text

## Template Parts & Custom Templates

### Template Parts

- Header
- Footer
- Sidebar

### Custom Templates

- Landing Page
- Portfolio Single

## Modern WordPress Features

### Enabled Controls

- **Border**: Color, radius, style, width
- **Dimensions**: Min-height support
- **Position**: Sticky positioning
- **Shadow**: Natural and deep shadow presets
- **Appearance Tools**: Comprehensive editor controls

## CSS Integration

### Compatibility

The theme.json configuration is designed to work alongside existing CSS:

- Uses CSS custom property references for consistency
- Maintains backward compatibility
- Follows WordPress coding standards
- Provides enhanced editor experience

### Potential Conflicts

Monitor these areas for CSS conflicts:

- Typography scales in existing CSS files
- Color definitions that might override theme.json
- Spacing utilities that duplicate theme.json spacing

## Maintenance

### Adding New Colors

1. Add to `settings.color.palette` array
2. Use kebab-case slug naming
3. Provide human-readable name
4. Update any related gradients

### Modifying Typography

1. Update `settings.typography.fontSizes` array
2. Maintain modular scale consistency
3. Update block-specific font sizes if needed

### Block Customization

1. Add block-specific settings under `settings.blocks`
2. Define styles under `styles.blocks`
3. Test in both editor and frontend

## Testing Checklist

- [ ] Color palette displays correctly in editor
- [ ] Typography settings apply correctly
- [ ] Spacing presets work in block editor
- [ ] CSS custom properties generate correctly
- [ ] Block variations render properly
- [ ] Accessibility standards maintained (WCAG 2.1 AA)
- [ ] No conflicts with existing CSS
- [ ] Editor controls function as expected

## Resources

- [WordPress Theme.json Documentation](https://developer.wordpress.org/themes/advanced-topics/theme-json/)
- [Theme.json Schema](https://schemas.wp.org/trunk/theme.json)
- [Block Editor Handbook](https://developer.wordpress.org/block-editor/)
