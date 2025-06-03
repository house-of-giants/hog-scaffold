# Block Patterns Quick Reference

This directory contains WordPress block patterns for the HoG Scaffold theme. Each pattern is a reusable content layout that users can insert into their pages and posts.

## File Structure

```
patterns/
├── about-section.php      # About section with image and features
├── contact-section.php    # Contact form and information
├── faq-section.php        # FAQ accordion section
├── hero.php               # Hero banner with CTA buttons
├── services-grid.php      # Services/features grid layout
├── team-section.php       # Team member profiles
├── testimonials.php       # Customer testimonials
└── README.md              # This file
```

## Pattern Naming Convention

- Use kebab-case for file names (e.g., `services-grid.php`)
- Pattern slugs are automatically generated as `hog-scaffold/{filename}`
- Each pattern should have a descriptive, unique name

## Adding New Patterns

1. **Create Pattern File:** Add a new `.php` file to this directory
2. **Follow File Structure:**
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
     <!-- Your pattern content -->
   </div>
   <!-- /wp:group -->
   ```

3. **Register in inc/blocks.php:** Add pattern metadata to the `$pattern_files` array:
   ```php
   'your-pattern-name' => array(
     'title' => __('Your Pattern Title', 'hog-scaffold'),
     'description' => __('Pattern description for users', 'hog-scaffold'),
     'categories' => array('hog-scaffold-sections'),
     'keywords' => array('keyword1', 'keyword2', 'keyword3'),
   ),
   ```

## Block Markup Guidelines

### Use WordPress Block Comments
```html
<!-- wp:group {"className":"your-class"} -->
<div class="wp-block-group your-class">
  <!-- wp:heading -->
  <h2>Your Heading</h2>
  <!-- /wp:heading -->
</div>
<!-- /wp:group -->
```

### Common Block Types
- `<!-- wp:group -->` - Container for multiple blocks
- `<!-- wp:columns -->` - Multi-column layouts
- `<!-- wp:heading -->` - Headings (H1-H6)
- `<!-- wp:paragraph -->` - Text paragraphs
- `<!-- wp:image -->` - Images
- `<!-- wp:buttons -->` - Button containers
- `<!-- wp:button -->` - Individual buttons
- `<!-- wp:cover -->` - Hero/cover sections

### Responsive Design
Use WordPress preset variables for consistent spacing:
- `var(--wp--preset--spacing--20)` for small spacing
- `var(--wp--preset--spacing--40)` for medium spacing
- `var(--wp--preset--spacing--60)` for large spacing

## Testing Patterns

1. **Local Development:** Patterns appear in WordPress admin under Patterns
2. **Editor Preview:** Check pattern preview in block inserter
3. **Frontend Rendering:** Verify pattern displays correctly on frontend
4. **Responsive Testing:** Test across different screen sizes
5. **Accessibility:** Validate with screen readers and keyboard navigation

## Pattern Categories

- `hog-scaffold-sections` - Complete page sections
- `hog-scaffold-content` - Smaller content components
- `header` - WordPress core header category
- `featured` - WordPress core featured category
- `text` - WordPress core text category
- `contact` - WordPress core contact category

## Common Issues

### Pattern Not Showing
- Check file exists in `/patterns` directory
- Verify registration in `inc/blocks.php`
- Check for PHP syntax errors

### Styling Problems
- Ensure proper block markup structure
- Use WordPress core block classes
- Test with theme's editor styles

### Content Structure
- Follow proper heading hierarchy (H1 → H2 → H3)
- Use semantic HTML elements
- Include alt text for images
- Ensure form fields have proper labels

## Best Practices

1. **Accessibility First:** Use semantic markup and proper ARIA attributes
2. **Mobile Responsive:** Design for mobile-first, then enhance for larger screens
3. **Performance:** Optimize images and minimize markup complexity
4. **Consistency:** Follow established design patterns from existing theme patterns
5. **Documentation:** Comment complex markup and explain custom classes

## WordPress Resources

- [Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [Block Patterns Guide](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/)
- [Block Markup Reference](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save) 