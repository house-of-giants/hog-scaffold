[Documentation](../README.md) > [Troubleshooting](README.md) > Common Issues

# Common Issues and Solutions

This guide covers the most frequently encountered issues when developing with the House of Giants WordPress Theme Scaffold and their solutions.

## Table of Contents

- [Installation Issues](#installation-issues)
- [Build System Problems](#build-system-problems)
- [WordPress Integration Issues](#wordpress-integration-issues)
- [Block Editor Problems](#block-editor-problems)
- [Performance Issues](#performance-issues)
- [Styling and CSS Issues](#styling-and-css-issues)
- [JavaScript Errors](#javascript-errors)
- [Security and Permissions](#security-and-permissions)

## Installation Issues

### Node.js Version Conflicts

**Symptoms:**

- Build process fails with "unsupported engine" errors
- npm install fails with version warnings
- webpack compilation errors

**Solutions:**

```bash
# Check current Node.js version
node --version

# Install Node Version Manager (nvm)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash

# Install and use Node.js 18+
nvm install 18
nvm use 18
nvm alias default 18

# Verify installation
node --version  # Should show v18.x.x
npm --version   # Should show compatible npm version
```

**Alternative solution for Windows:**

```bash
# Download and install from nodejs.org
# Or use Chocolatey
choco install nodejs --version=18.17.0
```

### npm Permission Errors

**Symptoms:**

- "EACCES: permission denied" during npm install
- Cannot install global packages
- Build scripts fail with permission errors

**Solutions:**

```bash
# Fix npm permissions (macOS/Linux)
sudo chown -R $(whoami) ~/.npm
sudo chown -R $(whoami) /usr/local/lib/node_modules

# Change npm global directory
mkdir ~/.npm-global
npm config set prefix '~/.npm-global'
echo 'export PATH=~/.npm-global/bin:$PATH' >> ~/.profile
source ~/.profile

# Use npx instead of global installs
npx create-react-app my-app  # Instead of npm install -g create-react-app
```

### Composer Installation Failures

**Symptoms:**

- "Your requirements could not be resolved" errors
- PHP version conflicts
- Memory limit exceeded errors

**Solutions:**

```bash
# Update Composer
composer self-update

# Clear Composer cache
composer clear-cache

# Install with memory limit increase
php -d memory_limit=-1 /usr/local/bin/composer install

# Use specific PHP version
/usr/bin/php8.1 /usr/local/bin/composer install

# Skip platform requirements (development only)
composer install --ignore-platform-reqs
```

## Build System Problems

### Webpack Build Failures

**Symptoms:**

- "Module not found" errors
- Build process hangs or crashes
- Asset files not generated

**Solutions:**

```bash
# Clear all caches and rebuild
rm -rf node_modules package-lock.json
npm install
npm run clean
npm run build

# Clear webpack cache specifically
rm -rf node_modules/.cache

# Check for specific module issues
npm ls  # Lists all installed packages
npm audit  # Checks for vulnerabilities

# Debug webpack configuration
npm run build -- --verbose
```

### CSS Compilation Issues

**Symptoms:**

- CSS files not generating
- PostCSS errors
- Stylelint blocking build

**Solutions:**

```bash
# Check PostCSS configuration
cat postcss.config.js

# Verify CSS syntax
npm run lint-css

# Fix CSS formatting issues
npm run format-css

# Build CSS only
npm run build:css

# Debug PostCSS processing
DEBUG=postcss* npm run build
```

### JavaScript Compilation Errors

**Symptoms:**

- ES6+ syntax errors
- Babel transpilation failures
- Missing polyfills

**Solutions:**

```bash
# Check Babel configuration
cat .babelrc

# Verify JavaScript syntax
npm run lint-js

# Fix JavaScript formatting
npm run format-js

# Build JavaScript only
npm run build:js

# Debug Babel processing
BABEL_VERBOSE=true npm run build
```

## WordPress Integration Issues

### Theme Not Appearing in WordPress

**Symptoms:**

- Theme not listed in Appearance > Themes
- "Broken theme" message
- Style.css header missing

**Solutions:**

```bash
# Check theme directory structure
ls -la wp-content/themes/hog-scaffold/

# Verify style.css header
head -20 style.css

# Should contain:
# /*
# Theme Name: House of Giants Scaffold
# Description: Modern WordPress theme scaffold
# Version: 1.0.0
# Author: Your Name
# */

# Check file permissions
chmod -R 755 wp-content/themes/hog-scaffold/
chown -R www-data:www-data wp-content/themes/hog-scaffold/
```

### Functions.php Errors

**Symptoms:**

- White screen of death
- "Fatal error" in functions.php
- Website completely broken

**Solutions:**

```bash
# Check PHP syntax
php -l functions.php

# Check PHP error log
tail -f /var/log/php/error.log

# Common fixes:
# 1. Remove closing ?> tags from PHP files
# 2. Check for duplicate function names
# 3. Verify proper hook usage

# Enable WordPress debug mode
# In wp-config.php:
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### Database Connection Issues

**Symptoms:**

- "Error establishing database connection"
- WordPress installation fails
- Site loads but content missing

**Solutions:**

```php
// Check wp-config.php database settings
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_HOST', 'localhost');

// Test database connection
mysql -u your_username -p your_database_name
# Or with PHP:
<?php
$connection = mysqli_connect('localhost', 'username', 'password', 'database');
if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}
echo 'Connected successfully';
?>
```

## Block Editor Problems

### Custom Blocks Not Loading

**Symptoms:**

- Custom blocks missing from block inserter
- JavaScript errors in block editor
- Blocks not rendering properly

**Solutions:**

```bash
# Check block registration
grep -r "registerBlockType" inc/blocks/

# Verify block.json files
find inc/blocks/ -name "block.json" -exec cat {} \;

# Check WordPress admin for JavaScript errors
# Open browser dev tools in block editor

# Rebuild blocks
npm run build:blocks
npm run wp-build

# Check block asset dependencies
ls -la build/blocks/
```

### Block Validation Errors

**Symptoms:**

- "This block contains unexpected or invalid content" warnings
- Blocks broken after theme updates
- Content loss in block editor

**Solutions:**

```javascript
// Check save function consistency
// In your block's save.js:
export default function save({ attributes }) {
	return (
		<div {...useBlockProps.save()}>
			{/* Ensure this matches exactly what's saved */}
		</div>
	);
}

// Add block deprecation if needed
const deprecated = [
	{
		attributes: {
			/* old attributes */
		},
		save: function ({ attributes }) {
			// Old save function
		},
	},
];

// Register with deprecations
registerBlockType("theme/block-name", {
	// ... other properties
	deprecated,
});
```

### Block Patterns Not Working

**Symptoms:**

- Patterns missing from inserter
- Pattern insertion fails
- Patterns render incorrectly

**Solutions:**

```php
// Check pattern registration in functions.php
function theme_register_block_patterns() {
    register_block_pattern(
        'theme/pattern-name',
        array(
            'title'       => __('Pattern Title', 'textdomain'),
            'description' => __('Pattern description', 'textdomain'),
            'content'     => '<!-- wp:group --><!-- /wp:group -->',
            'categories'  => array('theme'),
        )
    );
}
add_action('init', 'theme_register_block_patterns');

// Verify pattern files exist
ls -la patterns/

// Check pattern syntax
grep -r "wp:" patterns/
```

## Performance Issues

### Slow Page Load Times

**Symptoms:**

- Pages take more than 3 seconds to load
- High Time to First Byte (TTFB)
- Poor Lighthouse scores

**Solutions:**

```bash
# Analyze performance
npm run performance-audit

# Check bundle sizes
npm run build-analyze

# Optimize images
npm run optimize-images

# Enable caching
# In wp-config.php:
define('WP_CACHE', true);

# Use performance plugins:
# - WP Rocket (premium)
# - W3 Total Cache (free)
# - WP Super Cache (free)
```

### Large JavaScript Bundles

**Symptoms:**

- JavaScript files over 250KB
- Slow script loading
- High unused JavaScript in Lighthouse

**Solutions:**

```javascript
// Enable code splitting in webpack.config.js
module.exports = {
	optimization: {
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
};

// Use dynamic imports
const heavyModule = await import("./heavy-module");

// Lazy load components
const LazyComponent = React.lazy(() => import("./LazyComponent"));
```

### CSS Performance Issues

**Symptoms:**

- Large CSS files
- Unused CSS warnings
- Render-blocking stylesheets

**Solutions:**

```bash
# Analyze CSS usage
npm run analyze-css

# Remove unused CSS
npm install --save-dev purgecss
# Configure in webpack or PostCSS

# Implement critical CSS
npm install --save-dev critical
# Inline critical CSS in head

# Use CSS custom properties efficiently
# Avoid repeated declarations
```

## Styling and CSS Issues

### Styles Not Applying

**Symptoms:**

- CSS changes not visible
- Styles overridden by other CSS
- Responsive styles not working

**Solutions:**

```bash
# Clear browser cache
# Use hard refresh: Ctrl+Shift+R (Windows) or Cmd+Shift+R (Mac)

# Check CSS specificity
# Use browser dev tools to inspect elements

# Verify CSS file loading
curl -I http://your-site.com/wp-content/themes/theme-name/build/css/style.css

# Check for CSS syntax errors
npm run lint-css

# Force cache bust
# Add timestamp to CSS enqueue:
wp_enqueue_style('theme-style',
    get_template_directory_uri() . '/build/css/style.css',
    [],
    filemtime(get_template_directory() . '/build/css/style.css')
);
```

### Theme.json Not Working

**Symptoms:**

- Design tokens not applying
- Color palette not available
- Typography settings ignored

**Solutions:**

```json
// Verify theme.json syntax
{
    "$schema": "https://schemas.wp.org/trunk/theme.json",
    "version": 3,
    "settings": {
        "color": {
            "palette": [
                {
                    "name": "Primary",
                    "slug": "primary",
                    "color": "#007cba"
                }
            ]
        }
    }
}

// Check WordPress version compatibility
// theme.json version 3 requires WordPress 6.6+

// Validate JSON syntax
cat theme.json | python -m json.tool
```

### Responsive Design Problems

**Symptoms:**

- Layout breaks on mobile
- Text too small on mobile
- Horizontal scrolling

**Solutions:**

```css
/* Check viewport meta tag in header */
<meta name="viewport" content="width=device-width, initial-scale=1">

/* Use relative units instead of fixed pixels */
.container {
	max-width: 100%;
	padding: 1rem; /* Instead of padding: 20px; */
}

/* Implement proper breakpoints */
@media (max-width: 768px) {
	.grid {
		grid-template-columns: 1fr; /* Stack on mobile */
	}
}

/* Use CSS Grid for complex layouts */
.layout {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
	gap: 1rem;
}
```

## JavaScript Errors

### Console Errors

**Symptoms:**

- JavaScript errors in browser console
- Interactive elements not working
- Form submissions failing

**Solutions:**

```bash
# Check for common issues:

# 1. Missing dependencies
# Verify in package.json and rebuild
npm install
npm run build

# 2. Syntax errors
npm run lint-js

# 3. ES6+ compatibility issues
# Check .babelrc configuration

# 4. jQuery conflicts
# Use jQuery in noConflict mode:
jQuery(document).ready(function($) {
    // Use $ safely here
});
```

### AJAX Requests Failing

**Symptoms:**

- Form submissions not working
- Dynamic content not loading
- 403 Forbidden errors

**Solutions:**

```javascript
// Ensure proper nonce handling
const ajaxData = {
	action: "your_action",
	nonce: wp_ajax_object.nonce,
	data: formData,
};

fetch(wp_ajax_object.ajax_url, {
	method: "POST",
	headers: {
		"Content-Type": "application/x-www-form-urlencoded",
	},
	body: new URLSearchParams(ajaxData),
});

// Register AJAX handler in PHP
add_action("wp_ajax_your_action", "handle_ajax_request");
add_action("wp_ajax_nopriv_your_action", "handle_ajax_request");

function handle_ajax_request() {
	check_ajax_referer("your_nonce", "nonce");
	// Handle request
	wp_die();
}
```

## Security and Permissions

### File Permission Issues

**Symptoms:**

- Cannot upload files
- Theme updates fail
- "Permission denied" errors

**Solutions:**

```bash
# Set correct WordPress permissions
find /path/to/wordpress/ -type d -exec chmod 755 {} \;
find /path/to/wordpress/ -type f -exec chmod 644 {} \;

# wp-config.php should be 600 or 644
chmod 644 wp-config.php

# wp-content should be writable
chmod 755 wp-content/
chmod 755 wp-content/themes/
chmod 755 wp-content/plugins/
chmod 755 wp-content/uploads/

# Set correct ownership (adjust for your server)
chown -R www-data:www-data /path/to/wordpress/
```

### Security Headers Missing

**Symptoms:**

- Security warnings in tools
- Clickjacking vulnerabilities
- XSS attack potential

**Solutions:**

```php
// Add security headers in functions.php
function add_security_headers() {
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
add_action('send_headers', 'add_security_headers');
```

## Getting Additional Help

### Debug Information Collection

When reporting issues, include:

```bash
# System information
echo "Node.js: $(node --version)"
echo "npm: $(npm --version)"
echo "PHP: $(php --version)"
echo "Composer: $(composer --version)"

# WordPress information
wp core version
wp theme list
wp plugin list --status=active

# Build information
npm run build 2>&1 | tee build-log.txt
```

### Log Files to Check

1. **WordPress Debug Log:** `wp-content/debug.log`
2. **PHP Error Log:** `/var/log/php/error.log`
3. **Web Server Error Log:** `/var/log/apache2/error.log` or `/var/log/nginx/error.log`
4. **npm Debug Log:** `~/.npm/_logs/`

### Community Resources

- **GitHub Issues:** Report theme-specific bugs
- **WordPress Forums:** General WordPress help
- **Stack Overflow:** Programming questions
- **WordPress Slack:** Real-time support

## See Also

- [Installation Guide](../getting-started/installation-guide.md) - Setup troubleshooting
- [Build System Guide](../getting-started/build-system.md) - Build process help
- [Security Guide](../security/security-guide.md) - Security issue resolution
- [Performance Optimization](../deployment/deployment-guide.md) - Performance tuning

---

**[⬅️ Back to Troubleshooting](README.md)** | **[➡️ Next: Debug Techniques](debug-techniques.md)**
