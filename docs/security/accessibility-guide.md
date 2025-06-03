# Accessibility Guide

This WordPress theme scaffold is built with accessibility as a core principle, following WCAG 2.1 AA guidelines to ensure an inclusive experience for all users.

## Overview

The theme includes comprehensive accessibility features including:

- **Semantic HTML structure** with proper landmarks and headings
- **Keyboard navigation support** with visible focus indicators
- **Screen reader optimization** with ARIA labels and live regions
- **Color contrast compliance** meeting WCAG AA standards
- **Responsive design** that works across devices and zoom levels
- **Form accessibility** with proper labels and error handling

## Key Features

### 1. Semantic HTML Structure

The theme uses proper semantic HTML elements:

```html
<header role="banner" aria-label="Site header">
	<main role="main" aria-label="Main content">
		<nav role="navigation" aria-label="Primary navigation">
			<footer role="contentinfo" aria-label="Site footer"></footer>
		</nav>
	</main>
</header>
```

### 2. Keyboard Navigation

- **Tab navigation** through all interactive elements
- **Skip links** to jump to main content
- **Focus indicators** with 2px solid outlines
- **Escape key** support for closing modals
- **Arrow key navigation** where appropriate

### 3. Screen Reader Support

- **ARIA labels** on navigation, forms, and interactive elements
- **Live regions** for dynamic content announcements
- **Screen reader text** for context and instructions
- **Proper heading hierarchy** (H1 → H2 → H3)

### 4. Color and Contrast

All color combinations meet WCAG AA contrast requirements:

- **Text on background**: 4.5:1 minimum ratio
- **Large text**: 3:1 minimum ratio
- **Interactive elements**: Clear visual distinction
- **Status colors**: Accessible error, success, warning states

### 5. Form Accessibility

Forms include comprehensive accessibility features:

- **Associated labels** for all form controls
- **Required field indicators** with `aria-required`
- **Error messages** with `aria-describedby`
- **Fieldsets and legends** for grouped controls
- **Minimum target sizes** (44px) for touch interfaces

## Implementation Details

### CSS Classes

The theme provides utility classes for accessibility:

```css
/* Screen reader only text */
.sr-only, .screen-reader-text

/* High contrast combinations */
.color-primary-on-base
.color-contrast-on-base
.bg-high-contrast

/* Status colors */
.text-error, .bg-error-light
.text-success, .bg-success-light
.text-warning, .bg-warning-light
```

### JavaScript Features

The `AccessibilityEnhancer` class provides:

- **Keyboard navigation detection**
- **Focus management** for modals and dialogs
- **Form validation** with accessible error handling
- **Screen reader announcements**
- **Reduced motion support**

### Color Palette

The theme uses an accessible color system:

```css
--wp--preset--color--primary: #005cee; /* Blue - WCAG AA compliant */
--wp--preset--color--accent: #0052cc; /* Darker blue for hover states */
--wp--preset--color--contrast: #000000; /* Black text */
--wp--preset--color--base: #ffffff; /* White background */
--wp--preset--color--error: #dc2626; /* Accessible red */
--wp--preset--color--success: #00a32a; /* Accessible green */
--wp--preset--color--warning: #ca8a04; /* Accessible amber */
```

## Testing Guidelines

### Automated Testing

Use these tools for accessibility testing:

1. **axe-core** browser extension
2. **WAVE** web accessibility evaluator
3. **Lighthouse** accessibility audit
4. **Pa11y** command-line testing

### Manual Testing

Perform these manual tests:

1. **Keyboard navigation**: Tab through all interactive elements
2. **Screen reader testing**: Use NVDA, JAWS, or VoiceOver
3. **Color contrast**: Verify all text meets contrast requirements
4. **Zoom testing**: Test at 200% zoom level
5. **Mobile testing**: Verify touch target sizes

### Screen Reader Testing

Test with popular screen readers:

- **NVDA** (Windows) - Free
- **JAWS** (Windows) - Commercial
- **VoiceOver** (macOS/iOS) - Built-in
- **TalkBack** (Android) - Built-in

## Development Guidelines

### HTML Best Practices

```html
<!-- Use semantic elements -->
<article>
	,
	<section>
		,
		<nav>
			,
			<aside>
				,
				<header>
					,
					<footer>
						<!-- Provide proper headings -->
						<h1>Page Title</h1>
						<h2>Section Title</h2>
						<h3>Subsection Title</h3>

						<!-- Add ARIA labels where needed -->
						<nav aria-label="Primary navigation">
							<button aria-expanded="false" aria-controls="menu">Menu</button>
						</nav>
					</footer>
				</header>
			</aside>
		</nav>
	</section>
</article>
```

### CSS Best Practices

```css
/* Ensure focus visibility */
:focus-visible {
	outline: 2px solid var(--wp--preset--color--primary);
	outline-offset: 2px;
}

/* Respect reduced motion preferences */
@media (prefers-reduced-motion: reduce) {
	* {
		animation-duration: 0.01ms !important;
		transition-duration: 0.01ms !important;
	}
}

/* Ensure minimum target sizes */
button,
.wp-block-button__link {
	min-height: 44px;
	min-width: 44px;
}
```

### JavaScript Best Practices

```javascript
// Announce changes to screen readers
function announceToScreenReader(message) {
	const liveRegion = document.getElementById("accessibility-announcements");
	if (liveRegion) {
		liveRegion.textContent = message;
	}
}

// Handle keyboard navigation
document.addEventListener("keydown", (e) => {
	if (e.key === "Escape") {
		closeModal();
	}
});
```

## Common Accessibility Issues

### Issues to Avoid

1. **Missing alt text** on images
2. **Poor color contrast** ratios
3. **Keyboard traps** in navigation
4. **Missing form labels**
5. **Inaccessible focus indicators**
6. **Improper heading hierarchy**
7. **Missing ARIA labels** on complex widgets

### Quick Fixes

```html
<!-- Add alt text to images -->
<img src="image.jpg" alt="Descriptive text" />

<!-- Associate labels with form controls -->
<label for="email">Email Address</label>
<input type="email" id="email" name="email" />

<!-- Add ARIA labels to navigation -->
<nav aria-label="Main navigation">
	<!-- Use proper button markup -->
	<button type="button">Click me</button>
	<!-- Not: <div onclick="...">Click me</div> -->
</nav>
```

## Resources

### WCAG Guidelines

- [WCAG 2.1 AA Guidelines](https://www.w3.org/WAI/WCAG21/quickref/?currentsidebar=%23col_overview&levels=aaa)
- [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)

### Testing Tools

- [axe DevTools](https://www.deque.com/axe/devtools/)
- [WAVE Browser Extension](https://wave.webaim.org/extension/)
- [Lighthouse](https://developers.google.com/web/tools/lighthouse)

### WordPress Accessibility

- [WordPress Accessibility Handbook](https://make.wordpress.org/accessibility/handbook/)
- [WordPress Accessibility Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/accessibility/)

## Support

For accessibility questions or issues:

1. Review this documentation
2. Test with automated tools
3. Perform manual testing
4. Consult WCAG guidelines
5. Seek feedback from users with disabilities

Remember: Accessibility is not a one-time implementation but an ongoing commitment to inclusive design.
