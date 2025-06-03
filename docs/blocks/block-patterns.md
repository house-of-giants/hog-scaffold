# Block Patterns Documentation

The HoG Scaffold theme includes a comprehensive block pattern system designed to accelerate website development with pre-built, customizable content layouts.

## Overview

Block patterns are pre-designed collections of blocks that users can insert into their content. This theme provides 7 carefully crafted patterns organized into logical categories for easy discovery and use.

## Available Patterns

### 1. Hero Section

**File:** `patterns/hero.php`  
**Category:** Theme Sections  
**Description:** A prominent hero section with heading, description, and call-to-action buttons.

**Features:**

- Responsive cover block using viewport height units
- Center-aligned content with constrained layout
- Typography hierarchy (H1 + large paragraph)
- Dual CTA buttons (fill and outline styles)
- Dark overlay for text readability over background images
- Proper accessibility with ARIA attributes

**Usage:** Perfect for homepage headers, landing pages, or any section requiring prominent visual impact.

### 2. Services Grid

**File:** `patterns/services-grid.php`  
**Category:** Theme Sections  
**Description:** A flexible grid layout for showcasing services or features with icons, titles, and descriptions.

**Features:**

- 6-card responsive grid layout (2 rows × 3 columns)
- Consistent card styling with rounded corners
- Icon + title + description structure
- Proper spacing using WordPress preset variables
- Full-width section with constrained content
- Accessible emoji icons for visual appeal

**Usage:** Ideal for service offerings, feature highlights, or product showcases.

### 3. About Section

**File:** `patterns/about-section.php`  
**Category:** Theme Sections  
**Description:** A compelling about section with image, headline, description, and key features.

**Features:**

- Two-column layout with text and image
- Key statistics with bold highlights
- Rounded image corners and call-to-action
- Proper vertical alignment and spacing
- Responsive design considerations

**Usage:** Perfect for company about pages, founder stories, or team introductions.

### 4. Testimonials Section

**File:** `patterns/testimonials.php`  
**Category:** Theme Sections  
**Description:** Customer testimonials with quotes, names, and company information.

**Features:**

- Three-column testimonial cards layout
- Profile images with rounded corners
- Base-2 background for section distinction
- Quote + attribution structure with consistent styling
- Proper typography hierarchy

**Usage:** Great for building trust through customer feedback and social proof.

### 5. Team Section

**File:** `patterns/team-section.php`  
**Category:** Theme Sections  
**Description:** Meet the team section with member photos, names, roles, and descriptions.

**Features:**

- Four-column team member grid
- Circular profile photos (120px)
- Consistent card styling with base-2 background
- Name, role, and description hierarchy
- Responsive layout that adapts to different screen sizes

**Usage:** Essential for about pages, company profiles, or any team showcase.

### 6. Contact Section

**File:** `patterns/contact-section.php`  
**Category:** Theme Sections  
**Description:** Contact section with form fields, contact information, and call-to-action.

**Features:**

- 60/40 column split with form and contact info
- HTML form with proper field structure
- Accessible form labels and required attributes
- Contact information cards with icons
- Full-width send button with proper styling
- Structured layout for optimal user experience

**Usage:** Perfect for contact pages, lead generation, or support sections.

### 7. FAQ Section

**File:** `patterns/faq-section.php`  
**Category:** Theme Sections  
**Description:** Frequently asked questions section with expandable answers.

**Features:**

- Native HTML `<details>` and `<summary>` elements for accessibility
- 6 comprehensive FAQ items covering common topics
- Consistent card styling with rounded corners
- Base-2 background for section distinction
- Call-to-action section at bottom
- Proper semantic markup for screen readers

**Usage:** Excellent for support pages, product information, or service explanations.

## Pattern Categories

### Theme Sections

**Slug:** `hog-scaffold-sections`  
**Description:** Complete page sections for building layouts  
**Patterns:** All 7 patterns are categorized here

### Content Blocks

**Slug:** `hog-scaffold-content`  
**Description:** Reusable content components  
**Purpose:** Reserved for smaller, more focused content patterns

## Technical Implementation

### Pattern Registration System

**File:** `inc/blocks.php`  
**Function:** `block_patterns_and_categories()`

The registration system includes:

- Automatic pattern category registration
- Dynamic pattern file loading
- Comprehensive metadata for each pattern
- Content extraction that removes PHP headers
- Error handling with file existence checks
- Cleanup of conflicting default WordPress patterns

### Pattern File Structure

Each pattern file follows this structure:

```php
<?php
/**
 * Pattern Name
 *
 * @package HoGScaffold
 */
?>

<!-- WordPress Block Editor markup -->
<!-- wp:group -->
<div class="wp-block-group">
  <!-- Pattern content -->
</div>
<!-- /wp:group -->
```

### Metadata Configuration

Each pattern includes:

- **Title:** User-friendly name displayed in the editor
- **Description:** Helpful explanation of the pattern's purpose
- **Categories:** Organizational structure for pattern discovery
- **Keywords:** Search terms for easy pattern finding
- **Viewport Width:** Set to 1200px for optimal pattern previews

## Customization Guidelines

### Visual Consistency

All patterns maintain consistency through:

- **Spacing:** WordPress preset variables (`var(--wp--preset--spacing-*)`)
- **Typography:** Proper heading hierarchy and font sizes
- **Colors:** Base-2 background for section distinction
- **Border Radius:** Consistent rounded corners (8px)
- **Responsive Design:** Mobile-first approach with flexible layouts

### Content Customization

Users can easily customize patterns by:

1. **Text Content:** Replace placeholder text with actual content
2. **Images:** Swap placeholder images with brand assets
3. **Colors:** Modify background and text colors using WordPress theme settings
4. **Layout:** Adjust column structures and spacing as needed
5. **Content Blocks:** Add, remove, or rearrange individual blocks within patterns

### Developer Customization

Developers can extend patterns by:

1. **Creating New Patterns:** Add new `.php` files to the `/patterns` directory
2. **Modifying Registration:** Update the `$pattern_files` array in `inc/blocks.php`
3. **Adding Categories:** Register new pattern categories for better organization
4. **Custom Styling:** Add theme-specific CSS for pattern enhancement

## Best Practices

### Content Strategy

- **Purpose-Driven:** Each pattern serves a specific content purpose
- **Accessibility:** All patterns include proper semantic markup
- **Performance:** Optimized markup for fast loading
- **SEO-Friendly:** Proper heading structure and semantic elements

### Development Guidelines

- **Semantic Markup:** Use appropriate HTML elements for content structure
- **Accessibility:** Include ARIA labels and proper form attributes
- **Responsive Design:** Test patterns across different device sizes
- **Browser Support:** Ensure compatibility with modern browsers
- **WordPress Standards:** Follow WordPress coding standards and best practices

### User Experience

- **Clear Previews:** Pattern previews accurately represent final output
- **Easy Discovery:** Descriptive names and categories for quick finding
- **Flexible Content:** Patterns adapt to different content lengths
- **Consistent Styling:** Maintains theme design language throughout

## Integration with Theme

### Block Editor Support

Patterns are automatically available in the WordPress block editor under:

- **Pattern Inserter:** Access via the "+" button in the editor
- **Theme Sections Category:** Find all patterns in dedicated category
- **Search Functionality:** Use keywords to quickly locate specific patterns

### Theme Compatibility

- **Editor Styles:** Patterns inherit theme styling in the editor
- **Frontend Rendering:** Seamless integration with theme's CSS
- **Responsive Behavior:** Patterns adapt to theme's responsive design
- **Color Palette:** Automatic integration with theme color settings

### Performance Considerations

- **Lazy Loading:** Large images in patterns support lazy loading
- **Optimized Markup:** Clean, efficient HTML structure
- **CSS Efficiency:** Leverages existing theme styles
- **No JavaScript Dependencies:** Patterns work without additional scripts

## Troubleshooting

### Pattern Not Appearing

1. Check if the pattern file exists in `/patterns` directory
2. Verify pattern is registered in `inc/blocks.php`
3. Ensure WordPress is running version 5.5 or higher
4. Check for PHP errors in WordPress debug log

### Styling Issues

1. Verify theme supports block editor styles
2. Check if editor styles are properly enqueued
3. Ensure pattern markup follows WordPress block standards
4. Test pattern preview viewport width setting

### Content Issues

1. Validate pattern markup in block editor
2. Check for proper block comment syntax
3. Ensure nested blocks are properly structured
4. Test pattern with different content lengths

## Future Enhancements

### Potential Additions

- **Dynamic Content:** Patterns that pull from WordPress data
- **Interactive Elements:** Patterns with JavaScript functionality
- **E-commerce Patterns:** Product showcase and shopping patterns
- **Blog Patterns:** Post layout and archive patterns
- **Form Integrations:** Contact form plugin compatibility

### Planned Improvements

- **Pattern Variations:** Multiple versions of popular patterns
- **Advanced Customization:** More user-controllable options
- **Template Parts:** Integration with WordPress template parts
- **Pattern Kits:** Themed collections of related patterns

## Developer Resources

### WordPress Documentation

- [Block Patterns Handbook](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/)
- [Block Editor Developer Handbook](https://developer.wordpress.org/block-editor/)
- [Theme Developer Handbook](https://developer.wordpress.org/themes/)

### Code Examples

- Pattern registration examples in `inc/blocks.php`
- Complete pattern implementations in `/patterns` directory
- Block markup reference in individual pattern files

### Support and Contribution

- Submit issues via theme repository
- Contribute new patterns following established guidelines
- Follow WordPress coding standards for all contributions
