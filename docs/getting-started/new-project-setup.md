# New Project Setup Guide

Welcome to the House of Giants WordPress Theme Scaffold! This guide will walk you through the complete process of setting up a new WordPress project using this scaffold.

## 🎯 Overview

This scaffold provides a modern WordPress theme foundation with:

- WordPress 6.0+ Full Site Editing support
- Modern build system with Webpack 5
- Accessibility-first development (WCAG 2.1 AA)
- Security hardening and performance optimization
- Block-based architecture with custom blocks and patterns

## 📋 Prerequisites

Before starting, ensure you have:

- Node.js 16+ and npm installed
- PHP 8.0+ and Composer installed
- Git for version control
- Local WordPress development environment (Local, XAMPP, Docker, etc.)

## 🚀 Getting Started

### Step 1: Clone and Setup Repository

```bash
# Clone the scaffold (replace with your repository URL)
git clone https://github.com/your-org/hog-scaffold.git your-new-theme-name
cd your-new-theme-name

# Remove the existing git history and start fresh
rm -rf .git
git init
git add .
git commit -m "Initial commit: WordPress theme scaffold"
```

### Step 2: Project Customization

#### Required Search & Replace Operations

Perform these **case-sensitive** search and replace operations across all files in your project:

| Find                          | Replace With                   | Usage                              |
| ----------------------------- | ------------------------------ | ---------------------------------- |
| `HoGScaffold`                 | `YourThemeName`                | PascalCase for class names         |
| `HoG_SCAFFOLD`                | `YOUR_THEME_NAME`              | SCREAMING_SNAKE_CASE for constants |
| `HoG-scaffold`                | `your-theme-name`              | kebab-case for CSS, file names     |
| `HoG_scaffold`                | `your_theme_name`              | snake_case for function names      |
| `HoG Scaffold`                | `Your Theme Name`              | Title Case for display names       |
| `hog-scaffold-blocks`         | `your-theme-blocks`            | Block namespace                    |
| `https://project-website.tld` | `https://your-project-url.com` | Project URL                        |

#### Key Constants to Update

Search for and update these constants in your files:

- `HOG_SCAFFOLD_VERSION` → `YOUR_THEME_VERSION`
- `HOG_SCAFFOLD_TEMPLATE_URL` → `YOUR_THEME_TEMPLATE_URL`
- `HOG_SCAFFOLD_PATH` → `YOUR_THEME_PATH`
- `HOG_SCAFFOLD_INC` → `YOUR_THEME_INC`
- `HOG_SCAFFOLD_BLOCK_DIR` → `YOUR_THEME_BLOCK_DIR`

### Step 3: Update Configuration Files

#### Update package.json

```json
{
	"name": "your-theme-name",
	"version": "1.0.0",
	"description": "Your custom WordPress theme description",
	"repository": {
		"type": "git",
		"url": "https://github.com/your-username/your-theme-name.git"
	},
	"author": "Your Name <your.email@example.com>",
	"license": "GPL-2.0-or-later",
	"homepage": "https://your-website.com"
}
```

#### Update composer.json

```json
{
	"name": "your-org/your-theme-name",
	"description": "Your custom WordPress theme description",
	"type": "wordpress-theme",
	"license": "GPL-2.0-or-later",
	"authors": [
		{
			"name": "Your Name",
			"email": "your.email@example.com",
			"homepage": "https://your-website.com"
		}
	]
}
```

#### Update style.css Theme Header

```css
/*
Theme Name: Your Theme Name
Theme URI: https://your-website.com
Description: Your custom WordPress theme description
Author: Your Name
Author URI: https://your-website.com
Version: 1.0.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: your-theme-name
Domain Path: /languages
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 8.0
Tags: block-theme, full-site-editing, accessibility-ready, responsive
*/
```

### Step 4: Development Environment Setup

#### Install Dependencies

```bash
# Install Node.js dependencies
npm install

# Install PHP dependencies
composer install

# Initial build
npm run build
```

#### Development Commands

```bash
# Start development with file watching
npm run watch

# Development build
npm run dev

# Production build
npm run build

# Code quality checks
npm run lint
npm run lint:fix
```

### Step 5: WordPress Installation

#### Local Development Setup

1. **Create WordPress Site**: Set up a new WordPress site in your local development environment
2. **Install Theme**: Copy your theme folder to `/wp-content/themes/`
3. **Activate Theme**: Go to WordPress Admin → Appearance → Themes and activate your theme
4. **Import Sample Content** (optional): Use WordPress Importer to import sample content for testing

#### Docker Development (Optional)

If using the included Docker setup:

```bash
# Start Docker containers
docker-compose up -d

# Access WordPress at http://localhost:8080
# Access phpMyAdmin at http://localhost:8081
```

### Step 6: Theme Configuration

#### Enable WordPress Features

The theme automatically enables these WordPress features:

- Post thumbnails and featured images
- Custom navigation menus
- HTML5 markup support
- Custom logo support
- Block editor styles
- Wide and full width block alignments

#### Register Navigation Menus

In `functions.php`, customize navigation menus as needed:

```php
register_nav_menus(array(
    'primary' => __('Primary Navigation', 'your-theme-name'),
    'footer' => __('Footer Navigation', 'your-theme-name'),
    'social' => __('Social Links', 'your-theme-name')
));
```

#### Configure theme.json

Update `theme.json` with your design system:

- Color palette
- Typography scales
- Layout settings
- Block configurations

### Step 7: Asset Configuration

#### Update Webpack Entry Points

In `config/webpack.settings.cjs`, configure your asset entry points:

```javascript
const settings = {
	// JavaScript entry points
	entries: {
		"js/app": "./assets/js/frontend/frontend.js",
		"js/admin": "./assets/js/admin/admin.js",
		"css/style": "./assets/css/main.css",
		"css/admin-style": "./assets/css/admin/admin-style.css",
	},
};
```

#### Customize Asset Processing

- **CSS**: Add your styles to `assets/css/`
- **JavaScript**: Add your scripts to `assets/js/`
- **Images**: Place images in `assets/images/`
- **Fonts**: Add fonts to `assets/fonts/`

### Step 8: Customization

#### Create Custom Blocks (Optional)

Follow the [Block Development Guide](../blocks/block-development-guide.md) to create custom blocks:

```bash
# Create a new block
npx @wordpress/create-block your-block-name --template @wordpress/create-block-tutorial-template
```

#### Add Custom Post Types (Optional)

Use the built-in CPT framework in `inc/cpt/` to add custom post types. See the [CPT Framework Guide](../customization/cpt-framework-guide.md).

#### Customize Block Patterns

Add your own block patterns to the `patterns/` directory. See [Block Patterns Documentation](../blocks/block-patterns.md).

### Step 9: Testing & Quality Assurance

#### Run Code Quality Checks

```bash
# Check all code quality rules
npm run lint

# Fix auto-fixable issues
npm run lint:fix

# Run PHP CodeSniffer
npm run lint:php
```

#### Test Accessibility

- Use browser developer tools accessibility audits
- Test with screen readers
- Validate keyboard navigation
- Check color contrast ratios

#### Test Performance

- Run Lighthouse audits
- Test on various devices and connection speeds
- Optimize images and assets

### Step 10: Version Control Setup

#### Create .gitignore

Ensure your `.gitignore` includes:

```gitignore
# Build outputs
/js/
/css/
*.map

# Dependencies
node_modules/
vendor/

# Environment files
.env
.env.local

# IDE files
.vscode/
.idea/

# OS files
.DS_Store
Thumbs.db
```

#### Initial Commit

```bash
git add .
git commit -m "feat: initial project setup with custom branding

- Updated all theme identifiers and branding
- Configured build system and dependencies
- Set up development environment
- Ready for custom development"
```

## 🎨 Next Steps

After completing the basic setup:

1. **Design System**: Define your color palette, typography, and spacing in `theme.json`
2. **Custom Blocks**: Create project-specific blocks using the block development guide
3. **Content Templates**: Customize the block templates in `templates/`
4. **Navigation**: Set up navigation menus and customize the header/footer
5. **Performance**: Optimize images, implement lazy loading, and configure caching

## 📚 Additional Resources

- [Build System Guide](build-system.md) - Detailed webpack configuration
- [Theme Features Overview](../theme-features-overview.md) - Complete feature list
- [Block Development Guide](../blocks/block-development-guide.md) - Custom block creation
- [Security Guide](../security/security-guide.md) - WordPress security best practices
- [Deployment Guide](../deployment/deployment-guide.md) - Production deployment

## 🆘 Troubleshooting

### Common Issues

**Build fails with missing dependencies**

```bash
# Clear caches and reinstall
rm -rf node_modules package-lock.json
npm install
```

**Theme doesn't appear in WordPress admin**

- Check that `style.css` has the proper theme header
- Verify file permissions on the theme directory
- Ensure all search/replace operations were completed

**Assets not loading**

- Run `npm run build` to compile assets
- Check that webpack entry points match your file structure
- Verify asset paths in `functions.php`

For more troubleshooting help, see [Troubleshooting Guide](../troubleshooting/README.md).

---

**Congratulations!** You now have a fully customized WordPress theme ready for development. Start building your custom features and enjoy the modern development experience this scaffold provides.
