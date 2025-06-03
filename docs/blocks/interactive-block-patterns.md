[Documentation](../README.md) > [Blocks](README.md) > Interactive Block Patterns

# Interactive Block Development Patterns

This document outlines the established patterns for developing interactive blocks in the HoG Scaffold theme.

## Directory Structure Pattern

```
inc/blocks/interactive-{block-name}/
├── register.php          # Server-side registration and render callback
├── block.json           # Block metadata and configuration
├── index.js            # Editor registration and Edit component
├── view.js             # Frontend JavaScript for interactivity
├── style.css           # Frontend styles
└── editor.css          # Editor-specific styles
```

## Contact Form 7 Integration

When building interactive blocks that need form functionality, consider integrating with Contact Form 7 for robust form handling:

### Why Contact Form 7?

- **Proven Security**: Battle-tested spam protection and security features
- **User Familiarity**: Most WordPress users already know how to use CF7
- **Flexibility**: Supports complex form structures and custom fields
- **Reliability**: Handles email delivery, validation, and error handling
- **Extensibility**: Large ecosystem of add-ons and integrations

### Integration Patterns

#### 1. Embed CF7 Forms in Blocks

```php
// In your block's render callback
function render_contact_block($attributes) {
    $form_id = $attributes['formId'] ?? '';

    if (empty($form_id)) {
        return '<p>Please select a Contact Form 7 form.</p>';
    }

    // Render the CF7 form
    $form_output = do_shortcode("[contact-form-7 id=\"{$form_id}\"]");

    return sprintf(
        '<div %s>%s</div>',
        get_block_wrapper_attributes(['class' => 'contact-form-block']),
        $form_output
    );
}
```

#### 2. Form Selection in Block Editor

```javascript
// In your block's Edit component
import { SelectControl } from "@wordpress/components";
import { useSelect } from "@wordpress/data";

function ContactFormEdit({ attributes, setAttributes }) {
	const { formId } = attributes;

	// Get available CF7 forms
	const forms = useSelect((select) => {
		return select("core").getEntityRecords("postType", "wpcf7_contact_form");
	}, []);

	const formOptions = forms
		? forms.map((form) => ({
				label: form.title.rendered,
				value: form.id,
			}))
		: [];

	return (
		<div>
			<SelectControl
				label="Select Contact Form"
				value={formId}
				options={[{ label: "Select a form...", value: "" }, ...formOptions]}
				onChange={(value) => setAttributes({ formId: value })}
			/>
			{formId && (
				<div
					dangerouslySetInnerHTML={{
						__html: `[contact-form-7 id="${formId}"]`,
					}}
				/>
			)}
		</div>
	);
}
```

#### 3. Enhanced Form Styling

```css
/* Style CF7 forms within your blocks */
.contact-form-block .wpcf7-form {
	display: grid;
	gap: var(--form-spacing, 1rem);
}

.contact-form-block .wpcf7-form-control {
	width: 100%;
	padding: 0.75rem;
	border: 1px solid var(--form-border-color, #ddd);
	border-radius: var(--form-border-radius, 4px);
	font-family: inherit;
}

.contact-form-block .wpcf7-submit {
	background: var(--primary-color, #007cba);
	color: white;
	border: none;
	padding: 0.75rem 1.5rem;
	border-radius: var(--button-border-radius, 4px);
	cursor: pointer;
	transition: background-color 0.2s ease;
}

.contact-form-block .wpcf7-submit:hover {
	background: var(--primary-hover-color, #005a8a);
}

/* Error and success message styling */
.contact-form-block .wpcf7-response-output {
	padding: 1rem;
	border-radius: var(--form-border-radius, 4px);
	margin-top: 1rem;
}

.contact-form-block .wpcf7-mail-sent-ok {
	background: #d4edda;
	color: #155724;
	border: 1px solid #c3e6cb;
}

.contact-form-block .wpcf7-validation-errors {
	background: #f8d7da;
	color: #721c24;
	border: 1px solid #f5c6cb;
}
```

#### 4. JavaScript Integration

```javascript
// Enhance CF7 forms with additional interactivity
document.addEventListener("DOMContentLoaded", function () {
	// Listen for CF7 events
	document.addEventListener("wpcf7mailsent", function (event) {
		// Handle successful form submission
		console.log("Form submitted successfully");

		// Optional: Add custom analytics tracking
		if (typeof gtag !== "undefined") {
			gtag("event", "form_submit", {
				event_category: "contact",
				event_label: "contact_form_7",
			});
		}
	});

	document.addEventListener("wpcf7invalid", function (event) {
		// Handle form validation errors
		console.log("Form validation failed");
	});

	document.addEventListener("wpcf7spam", function (event) {
		// Handle spam detection
		console.log("Spam detected");
	});
});
```

### Best Practices for CF7 Integration

1. **Form Selection UI**: Always provide a user-friendly way to select CF7 forms in the block editor
2. **Fallback Content**: Show helpful messages when no form is selected
3. **Responsive Design**: Ensure forms work well on all device sizes
4. **Accessibility**: Maintain CF7's built-in accessibility features
5. **Performance**: Only load CF7 scripts when forms are present
6. **Validation**: Leverage CF7's validation instead of duplicating it
7. **Styling**: Use CSS custom properties for consistent theming

### Alternative Form Solutions

While Contact Form 7 is recommended, consider these alternatives for specific use cases:

- **Gravity Forms**: For complex forms with conditional logic
- **WPForms**: For drag-and-drop form building
- **Formidable Forms**: For advanced form functionality
- **Custom Forms**: For simple forms with specific requirements

### Security Considerations

When integrating with any form plugin:

- Always validate form IDs before rendering
- Sanitize any custom form attributes
- Use proper nonces for AJAX submissions
- Implement rate limiting for form submissions
- Validate and sanitize all form data server-side

## Block Registration Pattern

### 1. PHP Registration (register.php)

```php
<?php
namespace HoGScaffold\Blocks\{BlockName};
use HoGScaffold\Blocks\Utils\Block_Security;

function register() {
  register_block_type(
    HOG_SCAFFOLD_BLOCK_DIR . '{block-name}/block.json',
    array('render_callback' => __NAMESPACE__ . '\render_{block_name}')
  );
}

function render_{block_name}($attributes, $content, $block) {
  // 1. Extract and sanitize attributes
  // 2. Generate unique block ID
  // 3. Create security nonce
  // 4. Use Block_Security utilities
  // 5. Return server-rendered HTML
}
```

### 2. Block Configuration (block.json)

```json
{
	"apiVersion": 3,
	"name": "hog-scaffold/{block-name}",
	"title": "Block Title",
	"category": "hog-scaffold-blocks",
	"attributes": {
		// Define block attributes with proper types and defaults
	},
	"editorScript": "file:../../../js/{block-name}-editor.js",
	"editorStyle": "file:../../../css/{block-name}-editor-style.css",
	"style": "file:../../../css/{block-name}-style.css",
	"viewScript": "file:../../../js/{block-name}-view.js"
}
```

## Security Patterns

### Client-Side Security (JavaScript)

```javascript
import {
	sanitizeHtml,
	validateInput,
	checkRateLimit,
	isValidNonceFormat,
} from "../utils/interactive/security.js";

// Always validate input before processing
const validation = validateInput(userInput, {
	maxLength: 1000,
	required: true,
	allowHtml: false,
});

// Check rate limits for API calls
if (!checkRateLimit("block_action", 10, 60000)) {
	// Handle rate limit exceeded
}
```

### Server-Side Security (PHP)

```php
use HoGScaffold\Blocks\Utils\Block_Security;

// Sanitize all input data
$sanitized_data = Block_Security::sanitize_input($attributes, 'html');

// Validate with comprehensive rules
$validation = Block_Security::validate_input($data, $rules);

// Create and verify nonces
$nonce = Block_Security::create_nonce('action_name');
$is_valid = Block_Security::verify_nonce($nonce, 'action_name');

// Escape all output
echo Block_Security::escape_output($data, 'html');
```

## Component Patterns

### Editor Component Pattern

```javascript
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

function Edit({ attributes, setAttributes }) {
	// 1. Extract attributes
	// 2. Define state and handlers
	// 3. Validate before saving
	// 4. Render editor interface

	const onAttributeChange = (newValue) => {
		// Validate before setting
		const validation = validateTabs(newValue);
		if (Object.keys(validation).length === 0) {
			setAttributes({ attribute: newValue });
		}
	};

	return <div className="block-editor">{/* Editor interface */}</div>;
}

function Save() {
	// Return null for dynamic blocks using PHP render callback
	return null;
}

registerBlockType("hog-scaffold/block-name", {
	edit: Edit,
	save: Save,
});
```

### Frontend Component Pattern

```javascript
document.addEventListener("DOMContentLoaded", function () {
	const blocks = document.querySelectorAll(".interactive-block");
	blocks.forEach(initializeBlock);
});

function initializeBlock(blockElement) {
	// 1. Get block configuration
	// 2. Set up event listeners
	// 3. Initialize state
	// 4. Handle interactions

	const buttons = blockElement.querySelectorAll(".interactive-button");
	buttons.forEach((button, index) => {
		button.addEventListener("click", () => handleInteraction(index));
		button.addEventListener("keydown", (e) => handleKeyboard(e, index));
	});
}
```

## Validation Patterns

### Comprehensive Validation

```javascript
import { validateForm } from "../utils/interactive/validation.js";

const validationRules = {
	title: {
		required: true,
		minLength: 1,
		maxLength: 100,
		requiredMessage: "Title is required",
	},
	email: {
		type: "email",
		required: true,
	},
	content: {
		required: true,
		custom: (value) => {
			return value.length > 10 || "Content must be at least 10 characters";
		},
	},
};

const { isValid, errors } = validateForm(formData, validationRules);
```

## Accessibility Patterns

### ARIA and Keyboard Support

```javascript
// Tab navigation pattern
function handleKeyboardNavigation(event, currentIndex) {
	let newIndex = currentIndex;

	switch (event.key) {
		case "ArrowRight":
		case "ArrowDown":
			event.preventDefault();
			newIndex = (currentIndex + 1) % totalItems;
			break;
		case "ArrowLeft":
		case "ArrowUp":
			event.preventDefault();
			newIndex = (currentIndex - 1 + totalItems) % totalItems;
			break;
		case "Home":
			event.preventDefault();
			newIndex = 0;
			break;
		case "End":
			event.preventDefault();
			newIndex = totalItems - 1;
			break;
		default:
			return;
	}

	switchToItem(newIndex);
}
```

### ARIA Attributes Pattern

```php
// PHP render callback
echo sprintf(
  '<button class="tab-button" role="tab" aria-selected="%s" aria-controls="panel-%s" id="tab-%s">%s</button>',
  $is_active ? 'true' : 'false',
  esc_attr($panel_id),
  esc_attr($tab_id),
  Block_Security::escape_output($title, 'html')
);
```

## API Integration Patterns

### Secure API Calls

```javascript
import {
	apiRequest,
	withLoading,
	retryRequest,
} from "../utils/interactive/api.js";

async function makeSecureApiCall(data) {
	return withLoading(setLoading, async () => {
		return retryRequest(async () => {
			return apiRequest("endpoint", {
				method: "POST",
				data: sanitizedData,
			});
		});
	});
}
```

## Webpack Integration Pattern

### Webpack Configuration

```javascript
// In webpack.settings.cjs
entries: {
  // Interactive block entries
  '{block-name}-editor': './inc/blocks/{block-name}/index.js',
  '{block-name}-view': './inc/blocks/{block-name}/view.js',
  '{block-name}-style': './inc/blocks/{block-name}/style.css',
  '{block-name}-editor-style': './inc/blocks/{block-name}/editor.css'
}
```

### Block Registration in PHP

```php
// In inc/blocks.php
require_once HOG_SCAFFOLD_BLOCK_DIR . '/{block-name}/register.php';
{BlockNamespace}\register();
```

### Editor Script Import

```javascript
// In inc/blocks/blocks-editor.js
import "./{block-name}/index.js";
```

## File Naming Conventions

- **PHP Files**: `kebab-case` (e.g., `interactive-tabs-block`)
- **JavaScript Files**: `camelCase` for functions, `kebab-case` for files
- **CSS Classes**: `kebab-case` with BEM methodology
- **Block Names**: `hog-scaffold/kebab-case`
- **Namespaces**: `PascalCase` in PHP

## Error Handling Patterns

### Client-Side Error Handling

```javascript
try {
	const result = await apiCall();
	if (!result.success) {
		showErrorMessage(handleApiError(result.error));
	}
} catch (error) {
	console.error("Block error:", error);
	showErrorMessage("An unexpected error occurred");
}
```

### Server-Side Error Handling

```php
try {
  $result = risky_operation();
  if (!$result) {
    Block_Security::log_security_event('operation_failed', $data, 'warning');
    return '';
  }
} catch (Exception $e) {
  Block_Security::log_security_event('exception', ['message' => $e->getMessage()], 'error');
  return '';
}
```

## Performance Patterns

### Lazy Loading

```javascript
// Only initialize when block enters viewport
const observer = new IntersectionObserver((entries) => {
	entries.forEach((entry) => {
		if (entry.isIntersecting) {
			initializeBlock(entry.target);
			observer.unobserve(entry.target);
		}
	});
});
```

### Debounced Interactions

```javascript
import { debounceApiCall } from "../utils/interactive/api.js";

const debouncedSearch = debounceApiCall(searchFunction, 300);
```

## Testing Patterns

### Manual Testing Checklist

1. ✅ Keyboard navigation works
2. ✅ Screen reader compatibility
3. ✅ Input validation functions correctly
4. ✅ Error messages are user-friendly
5. ✅ Rate limiting prevents abuse
6. ✅ Nonce verification works
7. ✅ All data is properly sanitized
8. ✅ Responsive design functions properly

### Security Testing

1. Test with malicious input
2. Verify XSS protection
3. Test CSRF protection
4. Verify proper escaping
5. Test rate limiting
6. Verify file upload security

## Common Pitfalls to Avoid

1. **Never trust client data** - Always validate and sanitize server-side
2. **Don't skip nonce verification** - Always verify nonces for state-changing operations
3. **Avoid inline scripts/styles** - Use proper enqueueing
4. **Don't forget accessibility** - Include ARIA attributes and keyboard support
5. **Rate limit API calls** - Prevent abuse and improve performance
6. **Escape all output** - Use appropriate escaping functions for context
7. **Test with real content** - Don't just test with perfect data

## CSS Integration with Theme Design System

All interactive blocks should use the theme's centralized CSS custom properties rather than defining their own values. This ensures consistency across the theme and makes it easier to maintain design changes.

### Color System Integration

**✅ DO: Use theme color variables**

```css
.interactive-block {
	/* Use theme accent color for primary interactions */
	--block-primary: var(--wp--preset--color--accent);

	/* Use neutral colors from theme palette */
	--block-text: var(--wp--preset--color--neutral-900);
	--block-text-light: var(--wp--preset--color--neutral-500);
	--block-border: var(--wp--preset--color--neutral-200);
	--block-background: var(--wp--preset--color--white);

	/* Create color variations using color-mix for modern browsers */
	--block-primary-hover: color-mix(
		in srgb,
		var(--wp--preset--color--accent) 80%,
		black
	);
	--block-primary-light: color-mix(
		in srgb,
		var(--wp--preset--color--accent) 10%,
		white
	);
}
```

**❌ DON'T: Define hardcoded color values**

```css
.interactive-block {
	/* Avoid hardcoded values that duplicate theme settings */
	--block-primary: #007cba;
	--block-text: #1e1e1e;
	--block-border: #dddddd;
}
```

### Spacing System Integration

**✅ DO: Use theme spacing scale**

```css
.interactive-block {
	/* Map theme spacing to semantic block variables */
	--block-spacing-xs: var(--wp--preset--spacing--2xs); /* 0.512rem */
	--block-spacing-sm: var(--wp--preset--spacing--xs); /* 0.64rem */
	--block-spacing-md: var(--wp--preset--spacing--base); /* 1rem */
	--block-spacing-lg: var(--wp--preset--spacing--medium); /* 1.25rem */
	--block-spacing-xl: var(--wp--preset--spacing--large); /* 1.563rem */

	/* Use semantic spacing variables in styles */
	padding: var(--block-spacing-lg);
	margin-bottom: var(--block-spacing-md);
	gap: var(--block-spacing-sm);
}
```

**❌ DON'T: Create custom spacing values**

```css
.interactive-block {
	/* Avoid custom spacing that doesn't align with theme scale */
	--block-spacing-small: 0.5rem;
	--block-spacing-medium: 1.2rem;
	padding: var(--block-spacing-medium);
}
```

### Typography System Integration

**✅ DO: Use theme typography scale**

```css
.interactive-block {
	/* Use theme font families */
	font-family: var(--wp--preset--font-family--body);
}

.block-heading {
	font-family: var(--wp--preset--font-family--heading);
	font-size: var(--wp--preset--font-size--medium);
}

.block-text {
	font-size: var(--wp--preset--font-size--base);
}

.block-small-text {
	font-size: var(--wp--preset--font-size--small);
}
```

**❌ DON'T: Define custom font sizes**

```css
.interactive-block {
	/* Avoid custom font sizes that don't match theme scale */
	font-size: 1.1rem;
}
```

### Border and Shadow Integration

**✅ DO: Use theme design tokens**

```css
.interactive-block {
	/* Use theme border radius values */
	border-radius: var(--wp--preset--border-radius--small);

	/* Use theme shadow values */
	box-shadow: var(--wp--preset--shadow--medium);

	/* Use theme transition values */
	transition: var(--transition-fast);
}
```

### Dark Mode Support Pattern

**✅ DO: Override theme variables for dark mode**

```css
@media (prefers-color-scheme: dark) {
	.interactive-block {
		/* Adjust theme variables for dark mode */
		--block-primary: color-mix(
			in srgb,
			var(--wp--preset--color--accent) 80%,
			white
		);
		--block-text: var(--wp--preset--color--neutral-50);
		--block-text-light: var(--wp--preset--color--neutral-400);
		--block-background: var(--wp--preset--color--neutral-900);
		--block-border: var(--wp--preset--color--neutral-700);
	}
}
```

### Responsive Design with Theme Breakpoints

**✅ DO: Use consistent breakpoint patterns**

```css
/* Mobile-first approach using theme breakpoint conventions */
@media (max-width: 768px) {
	.interactive-block {
		font-size: var(--wp--preset--font-size--small);
		padding: var(--block-spacing-md);
	}
}

@media (max-width: 480px) {
	.interactive-block {
		padding: var(--block-spacing-sm);
	}
}
```

### CSS Architecture Pattern for Interactive Blocks

```css
/**
 * Block Name - Frontend Styles
 *
 * Uses theme-level CSS custom properties for colors, spacing, and typography
 * to maintain consistency and avoid duplicating design system values.
 */

/* Block component - uses theme design system */
.block-name {
	/* 1. Map theme values to semantic block variables */
	--block-primary: var(--wp--preset--color--accent);
	--block-spacing-md: var(--wp--preset--spacing--base);
	--block-border-radius: var(--wp--preset--border-radius--small);
	--block-transition: var(--transition-fast);

	/* 2. Apply semantic variables to styles */
	background: var(--block-background);
	border-radius: var(--block-border-radius);
	padding: var(--block-spacing-md);
	transition: var(--block-transition);
}

/* Interactive states */
.block-element:hover {
	background: color-mix(in srgb, var(--block-primary) 10%, transparent);
}

.block-element:focus {
	box-shadow: 0 0 0 2px var(--block-primary);
}

/* Component variants */
.block-element--large {
	padding: var(--wp--preset--spacing--large);
	font-size: var(--wp--preset--font-size--medium);
}

/* Responsive adjustments */
@media (max-width: 768px) {
	.block-name {
		padding: var(--wp--preset--spacing--small);
		font-size: var(--wp--preset--font-size--small);
	}
}
```

### Benefits of Theme Integration

1. **Consistency**: All blocks automatically follow the same design system
2. **Maintainability**: Change theme colors/spacing once, affects all blocks
3. **Performance**: Reduces CSS duplication and bundle size
4. **Accessibility**: Inherits theme accessibility improvements
5. **Theme Compatibility**: Works seamlessly with theme customizer changes
6. **Dark Mode**: Automatic dark mode support

### Available Theme Design Tokens

**Colors:**

- `--wp--preset--color--primary`, `--wp--preset--color--secondary`
- `--wp--preset--color--accent`
- `--wp--preset--color--neutral-[50-900]` (neutral color scale)
- `--wp--preset--color--white`, `--wp--preset--color--black`

**Spacing:**

- `--wp--preset--spacing--2xs` through `--wp--preset--spacing--3xl`
- Scale: 0.512rem, 0.64rem, 0.8rem, 1rem, 1.25rem, 1.563rem, 1.953rem, 2.441rem, 3.052rem

**Typography:**

- `--wp--preset--font-size--2xs` through `--wp--preset--font-size--3xl`
- `--wp--preset--font-family--system`, `--wp--preset--font-family--body`, `--wp--preset--font-family--heading`

**Layout:**

- `--wp--preset--border-radius--small|medium|large|full`
- `--wp--preset--shadow--small|medium|large`
- `--transition-fast|base|slow`

**Responsive:**

- `--breakpoint-sm|md|lg|xl|2xl`

For a complete list of available design tokens, see `assets/css/global/variables.css`.

### Interactive Blocks Utilities

The theme includes pre-built utility classes specifically for interactive blocks that automatically use theme design system values. These utilities are available in `assets/css/utilities/interactive-blocks.css` and are included in the main theme CSS.

#### Base Classes

**✅ DO: Use utility classes for common patterns**

```css
/* Use base interactive styles */
.my-custom-block {
	@extend .interactive-block-base; /* Or include the class in HTML */
}

/* Interactive elements like buttons, tabs, etc. */
.my-interactive-button {
	@extend .interactive-element; /* Or include the class in HTML */
}
```

**Available Base Classes:**

- `.interactive-block-base` - Foundation styles for any interactive block
- `.interactive-element` - Base styles for clickable/interactive elements
- `.interactive-element--active` - Active/selected state
- `.interactive-element--error` - Error state styling
- `.interactive-element--success` - Success state styling
- `.interactive-element--loading` - Loading state with spinner

#### Spacing Utilities

**Use theme-consistent spacing:**

```html
<!-- Padding utilities -->
<div class="interactive-padding--md">Content with medium padding</div>
<div class="interactive-padding--lg">Content with large padding</div>

<!-- Margin utilities -->
<div class="interactive-margin--sm">Content with small margin</div>

<!-- Gap utilities for flexbox/grid -->
<div class="interactive-flex interactive-spacing--md">
	<button class="interactive-element">Button 1</button>
	<button class="interactive-element">Button 2</button>
</div>
```

#### Typography Utilities

**Use theme typography scale:**

```html
<h3 class="interactive-text--heading interactive-text--medium">Block Title</h3>
<p class="interactive-text--body interactive-text--base">Block description</p>
<small class="interactive-text--small">Helper text</small>
```

#### Layout Utilities

**Pre-built layout patterns:**

```html
<!-- Flexible layouts -->
<div class="interactive-flex interactive-flex--center">
	<button class="interactive-element">Centered button</button>
</div>

<div class="interactive-flex interactive-flex--column interactive-spacing--sm">
	<div>Item 1</div>
	<div>Item 2</div>
</div>

<!-- Grid layouts -->
<div class="interactive-grid interactive-grid--3">
	<div class="interactive-element">Item 1</div>
	<div class="interactive-element">Item 2</div>
	<div class="interactive-element">Item 3</div>
</div>
```

#### Message Utilities

**Consistent error/success messaging:**

```html
<div class="interactive-message interactive-message--error">
	Something went wrong. Please try again.
</div>

<div class="interactive-message interactive-message--success">
	Your action completed successfully!
</div>

<div class="interactive-message interactive-message--warning">
	Please note this important information.
</div>

<div class="interactive-message interactive-message--info">
	Here's some helpful information.
</div>
```

#### Accessibility Utilities

**Built-in accessibility support:**

```html
<!-- Screen reader only content -->
<span class="interactive-sr-only">Additional context for screen readers</span>

<!-- Focus ring for keyboard navigation -->
<button class="interactive-element interactive-focus-ring">
	Keyboard accessible button
</button>
```

#### Example: Quick Interactive Block

Here's how you can create a new interactive block using the utilities:

```php
// PHP render callback
function render_my_interactive_block($attributes) {
    $items = $attributes['items'] ?? [];

    $output = '<div class="my-interactive-block interactive-block-base interactive-padding--lg">';
    $output .= '<h3 class="interactive-text--heading interactive-text--medium">Choose an option:</h3>';
    $output .= '<div class="interactive-flex interactive-spacing--sm interactive-flex--wrap">';

    foreach ($items as $item) {
        $output .= sprintf(
            '<button class="interactive-element interactive-focus-ring" data-value="%s">%s</button>',
            esc_attr($item['value']),
            esc_html($item['label'])
        );
    }

    $output .= '</div>';
    $output .= '<div class="interactive-message interactive-message--info" style="display: none;" role="alert"></div>';
    $output .= '</div>';

    return $output;
}
```

#### Benefits of Using Utilities

1. **Rapid Development**: No need to write custom CSS for common patterns
2. **Automatic Theme Integration**: All utilities use theme design system values
3. **Consistency**: All interactive blocks look and behave similarly
4. **Accessibility**: Built-in WCAG compliance and keyboard support
5. **Responsive**: Mobile-first responsive design included
6. **Dark Mode**: Automatic dark mode support
7. **Maintainability**: Change theme colors/spacing affects all blocks

#### When to Write Custom CSS

Use utilities for 90% of interactive block styling. Write custom CSS only when:

- Creating unique visual effects not covered by utilities
- Building complex animations
- Implementing brand-specific design elements
- Creating block-specific layout requirements

Even then, always reference theme design tokens:

```css
.my-special-block {
	/* Still use theme values for consistency */
	background: linear-gradient(
		135deg,
		var(--wp--preset--color--accent),
		color-mix(
			in srgb,
			var(--wp--preset--color--accent) 70%,
			var(--wp--preset--color--neutral-900)
		)
	);
	border-radius: var(--wp--preset--border-radius--medium);
	padding: var(--wp--preset--spacing--large);
}
```

This pattern documentation serves as the foundation for all interactive block development in the theme.

---

## See Also

- [Block Development Guide](block-development-guide.md) - General block development
- [Quick Start Guide](layout-blocks-quick-start.md) - Getting started with layout blocks
- [Layout Blocks System](layout-blocks-system.md) - Layout-specific documentation
- [Security Guide](../security/security-guide.md) - Security best practices

---

**[⬅️ Back to Blocks](README.md)** | **[➡️ Next: Layout Blocks Quick Start](layout-blocks-quick-start.md)**
