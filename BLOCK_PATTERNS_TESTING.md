# Block Patterns Testing Guide

This guide outlines the testing procedures for the WordPress block patterns implemented in the HoG Scaffold theme.

## Pattern Overview

The theme includes 7 comprehensive block patterns organized into two categories:

### Theme Sections Category

- **Hero Section** (`hog-scaffold/hero`) - Landing page hero with CTA buttons
- **Services Grid** (`hog-scaffold/services-grid`) - 6-card service showcase
- **About Section** (`hog-scaffold/about-section`) - Company story with image
- **Testimonials** (`hog-scaffold/testimonials`) - Customer feedback cards
- **Team Section** (`hog-scaffold/team-section`) - Team member profiles
- **Contact Section** (`hog-scaffold/contact-section`) - Contact form and info
- **FAQ Section** (`hog-scaffold/faq-section`) - Expandable Q&A accordion

## Testing Checklist

### 1. Pattern Registration Testing

**Verify patterns appear in editor:**

- [ ] Open WordPress admin → Pages → Add New
- [ ] Click the "+" button to open block inserter
- [ ] Navigate to "Patterns" tab
- [ ] Confirm "Theme Sections" category appears
- [ ] Verify all 7 patterns are listed with correct titles and descriptions
- [ ] Check pattern previews load correctly (1200px viewport width)

**Test pattern insertion:**

- [ ] Insert each pattern individually
- [ ] Verify patterns insert without errors
- [ ] Confirm all blocks within patterns are properly structured
- [ ] Test pattern insertion on both posts and pages

### 2. Visual Design Testing

**Layout and Spacing:**

- [ ] Hero Section: Check cover block height (70vh), content centering
- [ ] Services Grid: Verify 3-column layout, card spacing consistency
- [ ] About Section: Test 2-column layout, image alignment
- [ ] Testimonials: Check 3-column testimonial cards, profile images
- [ ] Team Section: Verify 4-column team grid, circular photos
- [ ] Contact Section: Test 60/40 column split, form layout
- [ ] FAQ Section: Check accordion spacing, details/summary structure

**Typography and Colors:**

- [ ] Verify consistent heading hierarchy (H2, H3 usage)
- [ ] Check font sizes match design system
- [ ] Test background colors (base, base-2) display correctly
- [ ] Verify text contrast meets accessibility standards

**Responsive Design:**

- [ ] Test patterns on mobile devices (320px+)
- [ ] Check tablet layout (768px+)
- [ ] Verify desktop display (1200px+)
- [ ] Test column stacking behavior on smaller screens

### 3. Content Editing Testing

**Text Content:**

- [ ] Edit headings in each pattern
- [ ] Modify paragraph text content
- [ ] Test list items in About section
- [ ] Update testimonial quotes and attribution
- [ ] Change team member names and roles

**Images:**

- [ ] Replace hero background image
- [ ] Update about section image
- [ ] Change testimonial profile photos
- [ ] Replace team member photos
- [ ] Test image sizing and cropping

**Interactive Elements:**

- [ ] Test button links and styling
- [ ] Verify contact form fields are editable
- [ ] Check FAQ accordion expand/collapse functionality
- [ ] Test form field placeholders and labels

### 4. Accessibility Testing

**Semantic HTML:**

- [ ] Verify proper heading hierarchy (H1 → H2 → H3)
- [ ] Check list markup in About section
- [ ] Test form labels and field associations
- [ ] Verify details/summary elements in FAQ

**ARIA and Screen Reader Support:**

- [ ] Test with screen reader (NVDA, JAWS, or VoiceOver)
- [ ] Verify image alt text is present and descriptive
- [ ] Check form field labels are properly associated
- [ ] Test keyboard navigation through patterns

**Color Contrast:**

- [ ] Verify text meets WCAG AA standards (4.5:1 ratio)
- [ ] Test button contrast ratios
- [ ] Check link color visibility
- [ ] Verify focus indicators are visible

### 5. Performance Testing

**Loading Speed:**

- [ ] Test pattern insertion speed in editor
- [ ] Verify no JavaScript errors in console
- [ ] Check CSS loading and rendering
- [ ] Test with multiple patterns on same page

**Browser Compatibility:**

- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Test on both desktop and mobile browsers

### 6. Editor Experience Testing

**Block Editor Integration:**

- [ ] Test pattern search functionality
- [ ] Verify pattern categories work correctly
- [ ] Check pattern keywords enable proper search
- [ ] Test pattern preview accuracy

**Content Management:**

- [ ] Test copying patterns between pages
- [ ] Verify patterns work in reusable blocks
- [ ] Check pattern behavior in widget areas
- [ ] Test with different user roles (Editor, Author)

**Customization:**

- [ ] Test color palette integration
- [ ] Verify spacing controls work
- [ ] Check typography options
- [ ] Test custom CSS additions

### 7. Integration Testing

**Theme Compatibility:**

- [ ] Test with theme's color scheme
- [ ] Verify spacing variables work correctly
- [ ] Check font family inheritance
- [ ] Test with theme's button styles

**Plugin Compatibility:**

- [ ] Test with contact form plugins (Contact Form 7, Gravity Forms)
- [ ] Verify with SEO plugins (Yoast, RankMath)
- [ ] Check with caching plugins
- [ ] Test with page builders (if applicable)

## Common Issues and Solutions

### Pattern Not Appearing

- Check if `block_patterns_and_categories()` is called on `init` hook
- Verify pattern file exists in `/patterns/` directory
- Ensure PHP syntax is correct in pattern files

### Styling Issues

- Check if theme's CSS is loading properly
- Verify WordPress preset spacing variables are defined
- Test with theme's editor styles

### Content Extraction Problems

- Verify PHP header removal regex is working
- Check for PHP syntax errors in pattern files
- Ensure proper file encoding (UTF-8)

### Responsive Layout Issues

- Test CSS Grid and Flexbox support
- Verify column block responsive behavior
- Check spacing on different screen sizes

## Testing Environment Setup

### Required Tools

- WordPress 6.0+ with Gutenberg editor
- Browser developer tools
- Screen reader software
- Mobile device or browser emulation
- Performance testing tools (Lighthouse, GTmetrix)

### Test Data

- Sample images (various sizes and formats)
- Test content (different lengths)
- Multiple user accounts with different roles
- Various browser/device combinations

## Reporting Issues

When reporting pattern issues, include:

- WordPress version
- Theme version
- Browser and version
- Device type and screen size
- Steps to reproduce
- Expected vs actual behavior
- Screenshots or screen recordings
- Console errors (if any)

## Success Criteria

Patterns are considered ready for production when:

- [ ] All patterns insert without errors
- [ ] Visual design matches specifications
- [ ] Content is fully editable
- [ ] Accessibility standards are met
- [ ] Performance is acceptable
- [ ] Cross-browser compatibility confirmed
- [ ] Editor experience is intuitive
- [ ] Integration with theme is seamless
