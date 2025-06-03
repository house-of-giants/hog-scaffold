[Documentation](../README.md) > [Refactoring Process](README.md) > Architectural Changes

# Architectural Changes

This document provides a detailed technical overview of the architectural changes made during the transformation from a classic WordPress theme to a modern block theme.

## Table of Contents

- [Overview](#overview)
- [Theme Structure Transformation](#theme-structure-transformation)
- [Template System Changes](#template-system-changes)
- [CSS Architecture Evolution](#css-architecture-evolution)
- [JavaScript Modernization](#javascript-modernization)
- [Build System Implementation](#build-system-implementation)
- [Security Enhancements](#security-enhancements)
- [Performance Optimizations](#performance-optimizations)

## Overview

The architectural refactoring involved a complete transformation of the theme's technical foundation while maintaining functional compatibility. The changes represent a shift from traditional PHP-based theme development to modern block-based development patterns.

### Key Architectural Principles

- **Block-First Development**: All components designed for the block editor
- **Modern Build Pipeline**: Webpack-based asset processing
- **Component Architecture**: Modular, reusable code organization
- **Performance-Oriented**: Optimized loading and execution patterns
- **Security-Hardened**: Built-in security best practices
- **Accessibility-First**: WCAG 2.1 AA compliance throughout

## Theme Structure Transformation

### Before: Classic Theme Structure

```
classic-theme/
├── header.php                 # PHP header template
├── footer.php                 # PHP footer template
├── index.php                  # Main template file
├── single.php                 # Single post template
├── page.php                   # Page template
├── archive.php                # Archive template
├── functions.php              # Theme functions
├── style.css                  # Main stylesheet
├── js/
│   └── main.js               # jQuery-based scripts
├── css/
│   ├── admin.css             # Admin styles
│   └── blocks.css            # Basic block styles
└── images/                   # Static images
```

### After: Block Theme Structure

```
block-theme/
├── theme.json                # Theme configuration and design system
├── functions.php             # Core theme setup
├── style.css                 # Theme metadata only
├── parts/                    # Template parts
│   ├── header.html           # Block-based header
│   ├── footer.html           # Block-based footer
│   └── navigation.html       # Navigation template part
├── templates/                # Block templates
│   ├── index.html            # Main template
│   ├── single.html           # Single post template
│   ├── page.html             # Page template
│   ├── archive.html          # Archive template
│   └── 404.html              # 404 template
├── patterns/                 # Block patterns
│   ├── hero-section.php      # Hero pattern
│   ├── service-cards.php     # Service cards pattern
│   └── team-profiles.php     # Team profiles pattern
├── inc/                      # PHP includes
│   ├── classes/              # Object-oriented classes
│   ├── blocks/               # Custom block registration
│   ├── security.php          # Security hardening
│   └── customizer.php        # Theme customizer
├── assets/                   # Source assets
│   ├── css/                  # CSS source files
│   ├── js/                   # JavaScript source files
│   └── images/               # Image assets
├── build/                    # Compiled assets
│   ├── css/                  # Compiled CSS
│   ├── js/                   # Compiled JavaScript
│   └── images/               # Optimized images
└── config/                   # Build configuration
    ├── webpack.shared.js     # Shared webpack config
    ├── webpack.dev.js        # Development config
    └── webpack.prod.js       # Production config
```

## Template System Changes

### Template Hierarchy Evolution

#### Classic PHP Templates

```php
<?php get_header(); ?>

<main id="main" class="site-main">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
```

#### Modern Block Templates

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group">
	<!-- wp:post-title {"level":1} /-->
	<!-- wp:post-content /-->
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### Template Part Conversion

#### Header Template Part

**Before (header.php):**

```php
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header">
    <div class="site-branding">
        <?php the_custom_logo(); ?>
        <h1 class="site-title"><?php bloginfo('name'); ?></h1>
    </div>
    <nav class="main-navigation">
        <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
    </nav>
</header>
```

**After (parts/header.html):**

```html
<!-- wp:group {"tagName":"header","className":"site-header","layout":{"type":"flex","justifyContent":"space-between"}} -->
<header class="wp-block-group site-header">
	<!-- wp:group {"className":"site-branding","layout":{"type":"flex"}} -->
	<div class="wp-block-group site-branding">
		<!-- wp:site-logo /-->
		<!-- wp:site-title /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:navigation {"menuId":2,"overlayMenu":"mobile"} /-->
</header>
<!-- /wp:group -->
```

## CSS Architecture Evolution

### Design Token System

#### Before: Traditional CSS

```css
/* style.css */
:root {
	--primary-color: #007cba;
	--secondary-color: #005177;
	--text-color: #333;
	--font-size-base: 16px;
	--font-size-large: 20px;
}

.button {
	background-color: var(--primary-color);
	color: white;
	font-size: var(--font-size-base);
	padding: 12px 24px;
	border-radius: 4px;
}
```

#### After: theme.json + CSS

**theme.json:**

```json
{
	"version": 3,
	"settings": {
		"color": {
			"palette": [
				{
					"name": "Primary",
					"slug": "primary",
					"color": "#007cba"
				},
				{
					"name": "Secondary",
					"slug": "secondary",
					"color": "#005177"
				}
			]
		},
		"typography": {
			"fontSizes": [
				{
					"name": "Small",
					"slug": "small",
					"size": "0.875rem"
				},
				{
					"name": "Medium",
					"slug": "medium",
					"size": "1rem"
				}
			]
		},
		"spacing": {
			"spacingSizes": [
				{
					"name": "Small",
					"slug": "30",
					"size": "0.5rem"
				}
			]
		}
	}
}
```

**CSS:**

```css
/* Uses design tokens from theme.json */
.wp-block-button__link {
	background-color: var(--wp--preset--color--primary);
	color: var(--wp--preset--color--white);
	font-size: var(--wp--preset--font-size--medium);
	padding: var(--wp--preset--spacing--30) var(--wp--preset--spacing--50);
}
```

### CSS Organization Structure

```
assets/css/
├── admin/
│   ├── admin-styles.css      # WordPress admin customizations
│   └── block-editor.css      # Block editor specific styles
├── components/
│   ├── buttons.css           # Button component styles
│   ├── forms.css             # Form component styles
│   ├── navigation.css        # Navigation component styles
│   └── cards.css             # Card component styles
├── layouts/
│   ├── grid.css              # Grid layout utilities
│   ├── flexbox.css           # Flexbox utilities
│   └── containers.css        # Container layouts
├── base/
│   ├── reset.css             # CSS reset/normalize
│   ├── typography.css        # Base typography
│   └── variables.css         # CSS custom properties
└── blocks/
    ├── hero-block.css        # Hero block styles
    ├── service-cards.css     # Service cards block
    └── testimonials.css      # Testimonials block
```

## JavaScript Modernization

### From jQuery to Modern JavaScript

#### Before: jQuery Implementation

```javascript
// assets/js/main.js
jQuery(document).ready(function ($) {
	// Mobile menu toggle
	$(".menu-toggle").on("click", function () {
		$(".main-navigation").toggleClass("toggled");
		$(this).attr(
			"aria-expanded",
			$(this).attr("aria-expanded") === "false" ? "true" : "false"
		);
	});

	// Smooth scrolling
	$('a[href*="#"]').on("click", function (e) {
		e.preventDefault();
		$("html, body").animate(
			{
				scrollTop: $($(this).attr("href")).offset().top,
			},
			500
		);
	});

	// Form validation
	$("#contact-form").on("submit", function (e) {
		var isValid = true;
		$(this)
			.find("input[required]")
			.each(function () {
				if (!$(this).val()) {
					isValid = false;
					$(this).addClass("error");
				}
			});

		if (!isValid) {
			e.preventDefault();
		}
	});
});
```

#### After: Modern JavaScript

```javascript
// assets/js/navigation.js
class NavigationHandler {
	constructor() {
		this.init();
	}

	init() {
		this.bindEvents();
		this.setupAccessibility();
	}

	bindEvents() {
		const toggleButton = document.querySelector(".menu-toggle");
		const navigation = document.querySelector(".main-navigation");

		toggleButton?.addEventListener("click", () => {
			this.toggleMobileMenu(toggleButton, navigation);
		});

		// Handle escape key
		document.addEventListener("keydown", (e) => {
			if (e.key === "Escape" && navigation?.classList.contains("toggled")) {
				this.closeMobileMenu(toggleButton, navigation);
			}
		});
	}

	toggleMobileMenu(button, nav) {
		const isExpanded = button.getAttribute("aria-expanded") === "true";

		nav.classList.toggle("toggled");
		button.setAttribute("aria-expanded", !isExpanded);

		// Focus management
		if (!isExpanded) {
			nav.querySelector("a")?.focus();
		}
	}

	setupAccessibility() {
		// Add skip links, focus indicators, etc.
		this.addSkipLinks();
		this.enhanceFocusIndicators();
	}
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
	new NavigationHandler();
});
```

### Module System Implementation

```javascript
// assets/js/modules/api-client.js
export class ApiClient {
	constructor(baseUrl, options = {}) {
		this.baseUrl = baseUrl;
		this.options = {
			timeout: 5000,
			retries: 3,
			...options,
		};
	}

	async request(endpoint, options = {}) {
		const url = `${this.baseUrl}${endpoint}`;
		const config = {
			headers: {
				"Content-Type": "application/json",
				"X-WP-Nonce": window.wpApiSettings?.nonce,
			},
			...options,
		};

		try {
			const response = await fetch(url, config);

			if (!response.ok) {
				throw new Error(`HTTP ${response.status}: ${response.statusText}`);
			}

			return await response.json();
		} catch (error) {
			console.error("API request failed:", error);
			throw error;
		}
	}
}

// assets/js/modules/form-handler.js
export class FormHandler {
	constructor(formSelector, options = {}) {
		this.form = document.querySelector(formSelector);
		this.options = {
			validateOnBlur: true,
			showSuccessMessage: true,
			...options,
		};

		if (this.form) {
			this.init();
		}
	}

	init() {
		this.bindEvents();
		this.setupValidation();
	}

	async handleSubmit(event) {
		event.preventDefault();

		if (!this.validateForm()) {
			return;
		}

		try {
			this.setLoading(true);
			const formData = new FormData(this.form);
			const response = await this.submitForm(formData);

			this.handleSuccess(response);
		} catch (error) {
			this.handleError(error);
		} finally {
			this.setLoading(false);
		}
	}
}
```

## Build System Implementation

### Webpack Configuration

#### Development Configuration

```javascript
// config/webpack.dev.js
const path = require("path");
const { merge } = require("webpack-merge");
const shared = require("./webpack.shared.js");

module.exports = merge(shared, {
	mode: "development",
	devtool: "eval-source-map",

	optimization: {
		minimize: false,
	},

	module: {
		rules: [
			{
				test: /\.css$/,
				use: [
					"style-loader",
					{
						loader: "css-loader",
						options: {
							sourceMap: true,
						},
					},
					{
						loader: "postcss-loader",
						options: {
							sourceMap: true,
						},
					},
				],
			},
		],
	},

	devServer: {
		contentBase: path.resolve(__dirname, "../build"),
		hot: true,
		overlay: true,
		stats: "minimal",
	},
});
```

#### Production Configuration

```javascript
// config/webpack.prod.js
const { merge } = require("webpack-merge");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const shared = require("./webpack.shared.js");

module.exports = merge(shared, {
	mode: "production",
	devtool: false,

	optimization: {
		minimize: true,
		minimizer: [
			new TerserPlugin({
				terserOptions: {
					compress: {
						drop_console: true,
					},
				},
			}),
			new CssMinimizerPlugin(),
		],
		splitChunks: {
			chunks: "all",
			cacheGroups: {
				vendor: {
					test: /[\\/]node_modules[\\/]/,
					name: "vendors",
					chunks: "all",
				},
			},
		},
	},

	plugins: [
		new MiniCssExtractPlugin({
			filename: "css/[name].[contenthash].css",
		}),
	],
});
```

## Security Enhancements

### Input Validation System

```php
// inc/security.php
class SecurityManager {

    public static function sanitize_text_input($input, $options = []) {
        $defaults = [
            'max_length' => 255,
            'allow_html' => false,
            'trim_whitespace' => true
        ];

        $options = wp_parse_args($options, $defaults);

        if ($options['trim_whitespace']) {
            $input = trim($input);
        }

        if (!$options['allow_html']) {
            $input = wp_strip_all_tags($input);
        }

        if (strlen($input) > $options['max_length']) {
            $input = substr($input, 0, $options['max_length']);
        }

        return sanitize_text_field($input);
    }

    public static function verify_nonce($action, $nonce_field = '_wpnonce') {
        if (!isset($_POST[$nonce_field])) {
            return false;
        }

        return wp_verify_nonce($_POST[$nonce_field], $action);
    }

    public static function set_security_headers() {
        if (!headers_sent()) {
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: SAMEORIGIN');
            header('X-XSS-Protection: 1; mode=block');
            header('Referrer-Policy: strict-origin-when-cross-origin');

            if (is_ssl()) {
                header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
            }
        }
    }
}
```

### File Upload Security

```php
// inc/classes/File_Security.php
class File_Security {

    private static $allowed_types = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp'
    ];

    public static function validate_file_upload($file) {
        $validation_errors = [];

        // Check file size
        if ($file['size'] > wp_max_upload_size()) {
            $validation_errors[] = 'File size exceeds maximum allowed size.';
        }

        // Validate MIME type
        $file_type = wp_check_filetype_and_ext(
            $file['tmp_name'],
            $file['name']
        );

        if (!in_array($file_type['type'], self::$allowed_types)) {
            $validation_errors[] = 'File type not allowed.';
        }

        // Content validation
        if (!self::scan_file_content($file['tmp_name'])) {
            $validation_errors[] = 'File content validation failed.';
        }

        return empty($validation_errors) ? true : $validation_errors;
    }

    private static function scan_file_content($file_path) {
        $content = file_get_contents($file_path);

        // Basic malware patterns
        $suspicious_patterns = [
            '/<\?php/',
            '/eval\s*\(/',
            '/base64_decode/',
            '/shell_exec/',
            '/system\s*\(/',
            '/exec\s*\(/'
        ];

        foreach ($suspicious_patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return false;
            }
        }

        return true;
    }
}
```

## Performance Optimizations

### Asset Loading Strategy

```php
// inc/performance.php
class Performance_Manager {

    public static function init() {
        add_action('wp_enqueue_scripts', [self::class, 'enqueue_assets']);
        add_action('wp_head', [self::class, 'add_resource_hints'], 2);
        add_filter('script_loader_tag', [self::class, 'add_async_defer'], 10, 3);
    }

    public static function enqueue_assets() {
        // Critical CSS inline
        $critical_css = self::get_critical_css();
        if ($critical_css) {
            wp_add_inline_style('theme-style', $critical_css);
        }

        // Conditional loading
        if (is_front_page()) {
            wp_enqueue_script('hero-interactions',
                get_template_directory_uri() . '/build/js/hero.js',
                [], filemtime(get_template_directory() . '/build/js/hero.js'), true
            );
        }

        if (is_page_template('templates/contact.html')) {
            wp_enqueue_script('form-validation',
                get_template_directory_uri() . '/build/js/forms.js',
                [], filemtime(get_template_directory() . '/build/js/forms.js'), true
            );
        }
    }

    public static function add_resource_hints() {
        // Preload critical assets
        echo '<link rel="preload" href="' . get_template_directory_uri() . '/build/css/critical.css" as="style">';

        // DNS prefetch for external resources
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
        echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">';
    }

    public static function add_async_defer($tag, $handle, $src) {
        // Defer non-critical scripts
        $defer_scripts = ['theme-main', 'contact-form'];

        if (in_array($handle, $defer_scripts)) {
            return str_replace('<script ', '<script defer ', $tag);
        }

        return $tag;
    }
}
```

### Database Query Optimization

```php
// inc/optimization.php
class Query_Optimizer {

    public static function init() {
        add_action('pre_get_posts', [self::class, 'optimize_queries']);
        add_filter('posts_clauses', [self::class, 'optimize_meta_queries'], 10, 2);
    }

    public static function optimize_queries($query) {
        if (!is_admin() && $query->is_main_query()) {

            // Limit post meta queries
            if ($query->is_home() || $query->is_archive()) {
                $query->set('meta_query', []);
                $query->set('no_found_rows', true);
            }

            // Optimize pagination
            if ($query->is_paged()) {
                $query->set('posts_per_page', 10);
            }
        }
    }

    public static function optimize_meta_queries($clauses, $query) {
        global $wpdb;

        // Add indexes for commonly queried meta
        if (strpos($clauses['where'], 'meta_key') !== false) {
            $clauses['join'] .= " USE INDEX (meta_key_value)";
        }

        return $clauses;
    }
}
```

## Migration Compatibility

### Backward Compatibility Layer

```php
// inc/compatibility.php
class Theme_Compatibility {

    public static function init() {
        // Support for classic theme functions
        add_action('after_setup_theme', [self::class, 'classic_theme_support']);

        // Legacy hook compatibility
        add_action('init', [self::class, 'legacy_hook_compatibility']);
    }

    public static function classic_theme_support() {
        // Maintain support for classic theme features
        add_theme_support('post-thumbnails');
        add_theme_support('custom-logo');
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption'
        ]);

        // Register legacy navigation menus
        register_nav_menus([
            'primary' => __('Primary Menu', 'hog-scaffold'),
            'footer' => __('Footer Menu', 'hog-scaffold')
        ]);
    }

    public static function legacy_hook_compatibility() {
        // Provide compatibility for common theme hooks
        if (!has_action('wp_head', 'wp_generator')) {
            add_action('wp_head', 'wp_generator');
        }

        // Support for legacy customizer options
        if (get_theme_mod('legacy_option')) {
            self::migrate_legacy_options();
        }
    }
}
```

---

## See Also

- [Methodology](methodology.md) - Overall refactoring approach
- [Git History Preservation](git-history.md) - Version control strategies
- [Migration Strategy](migration-strategy.md) - Step-by-step migration process

---

**[⬅️ Back to Refactoring Process](README.md)** | **[➡️ Next: Migration Strategy](migration-strategy.md)**
