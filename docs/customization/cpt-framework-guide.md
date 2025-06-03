# Custom Post Type Framework Guide

A comprehensive framework for creating block-compatible custom post types in WordPress themes.

## Table of Contents

1. [Overview](#overview)
2. [Quick Start](#quick-start)
3. [Framework Architecture](#framework-architecture)
4. [CPT_Helper Class](#cpt_helper-class)
5. [Meta Fields](#meta-fields)
6. [Taxonomies](#taxonomies)
7. [Block Templates](#block-templates)
8. [Migration Utilities](#migration-utilities)
9. [Admin Interface](#admin-interface)
10. [REST API Integration](#rest-api-integration)
11. [Best Practices](#best-practices)
12. [Troubleshooting](#troubleshooting)

## Overview

The HoG Scaffold CPT Framework provides a modern, block-editor-compatible approach to creating custom post types in WordPress. It features:

- **Fluent API** for clean, readable CPT definitions
- **Block editor integration** with custom templates
- **Meta field management** with REST API exposure
- **Taxonomy association** with automatic label generation
- **Migration utilities** for legacy CPT conversion
- **Admin interface** for management and monitoring
- **Security-first** approach with proper validation

## Quick Start

### 1. Basic CPT Creation

```php
use HoGScaffold\Classes\CPT_Helper;

$portfolio = new CPT_Helper(
    'portfolio',           // Post type key
    'Portfolio Item',      // Singular name
    'Portfolio Items',     // Plural name
    'portfolio',          // URL slug
    array(                // Additional options
        'menu_icon' => 'dashicons-portfolio',
        'supports'  => array( 'title', 'editor', 'thumbnail' )
    )
);
```

### 2. Adding Meta Fields

```php
$portfolio
    ->add_meta_field( 'client_name', array(
        'type'        => 'string',
        'description' => 'Client name for this project'
    ), 'text' )
    ->add_meta_field( 'project_date', array(
        'type'        => 'string',
        'description' => 'Project completion date'
    ), 'date' );
```

### 3. Adding Taxonomies

```php
$portfolio->add_taxonomy(
    'portfolio_category',     // Taxonomy key
    'Portfolio Category',     // Singular name
    'Portfolio Categories',   // Plural name
    'portfolio-category'      // URL slug
);
```

### 4. Setting Block Templates

```php
$portfolio->set_block_template( array(
    array( 'core/heading', array( 'level' => 2 ) ),
    array( 'core/paragraph', array( 'placeholder' => 'Project description...' ) ),
    array( 'core/gallery' )
) );
```

### 5. Enable Admin Interface

```php
$portfolio->add_admin_metabox();
```

## Framework Architecture

### File Structure

```
/inc/
├── classes/
│   ├── CPT_Helper.php           # Main CPT registration framework
│   ├── CPT_Migration.php        # Migration utilities
├── cpt/
│   ├── init.php                 # CPT definitions
│   └── migration-examples.php   # Migration examples
└── cpt.php                      # Main CPT setup file
```

### Integration Points

- **functions.php** - Framework initialization
- **theme.json** - Color and typography integration
- **Block editor** - Template and pattern support
- **REST API** - Custom endpoints and meta exposure
- **Admin interface** - Management dashboard

## CPT_Helper Class

The `CPT_Helper` class is the core of the framework, providing a fluent API for CPT creation.

### Constructor

```php
public function __construct( $post_type, $singular, $plural, $slug, $options = array() )
```

**Parameters:**

- `$post_type` (string) - Post type key (max 20 characters)
- `$singular` (string) - Singular display name
- `$plural` (string) - Plural display name
- `$slug` (string) - URL slug for archives and rewrites
- `$options` (array) - Additional `register_post_type()` arguments

### Method Chaining

All configuration methods return `$this`, enabling method chaining:

```php
$portfolio = new CPT_Helper( 'portfolio', 'Portfolio Item', 'Portfolio Items', 'portfolio' )
    ->add_meta_field( 'client_name', array(), 'text' )
    ->add_taxonomy( 'portfolio_category', 'Category', 'Categories', 'category' )
    ->set_block_template( $template )
    ->add_admin_metabox();
```

### Default Configuration

The framework provides sensible defaults:

```php
$defaults = array(
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'show_in_rest'       => true,
    'query_var'          => true,
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => 20,
    'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
);
```

## Meta Fields

### Adding Meta Fields

```php
$cpt->add_meta_field( $key, $args, $input_type, $options );
```

**Parameters:**

- `$key` (string) - Meta field key
- `$args` (array) - `register_post_meta()` arguments
- `$input_type` (string) - Admin UI input type
- `$options` (array) - Options for select/radio fields

### Input Types

| Type       | Description                 | Example                     |
| ---------- | --------------------------- | --------------------------- |
| `text`     | Single line text input      | Client name, project title  |
| `textarea` | Multi-line text input       | Project description, notes  |
| `url`      | URL input with validation   | Project URL, client website |
| `email`    | Email input with validation | Client email                |
| `date`     | Date picker                 | Project completion date     |
| `select`   | Dropdown selection          | Project status, priority    |
| `checkbox` | Boolean checkbox            | Featured project flag       |

### Meta Field Examples

```php
// Text field
$cpt->add_meta_field( 'client_name', array(
    'type'        => 'string',
    'description' => 'Client or company name',
    'show_in_rest' => true,
), 'text' );

// Select field with options
$cpt->add_meta_field( 'project_status', array(
    'type'        => 'string',
    'description' => 'Current project status',
    'show_in_rest' => true,
), 'select', array(
    'completed'   => 'Completed',
    'in-progress' => 'In Progress',
    'on-hold'     => 'On Hold',
) );

// Checkbox field
$cpt->add_meta_field( 'featured_project', array(
    'type'        => 'boolean',
    'description' => 'Mark as featured project',
    'show_in_rest' => true,
), 'checkbox' );
```

### REST API Exposure

Meta fields with `'show_in_rest' => true` are automatically available in the REST API:

```
GET /wp-json/wp/v2/portfolio/123
{
    "meta": {
        "client_name": "Acme Corp",
        "project_date": "2024-01-15",
        "featured_project": true
    }
}
```

## Taxonomies

### Adding Taxonomies

```php
$cpt->add_taxonomy( $taxonomy, $singular, $plural, $slug, $options );
```

**Parameters:**

- `$taxonomy` (string) - Taxonomy key
- `$singular` (string) - Singular display name
- `$plural` (string) - Plural display name
- `$slug` (string) - URL slug for taxonomy archives
- `$options` (array) - Additional `register_taxonomy()` arguments

### Taxonomy Examples

```php
// Hierarchical taxonomy (like categories)
$portfolio->add_taxonomy( 'portfolio_category', 'Category', 'Categories', 'portfolio-category', array(
    'hierarchical' => true,
    'description'  => 'Categorize portfolio items by type or industry',
) );

// Non-hierarchical taxonomy (like tags)
$portfolio->add_taxonomy( 'portfolio_tag', 'Tag', 'Tags', 'portfolio-tag', array(
    'hierarchical' => false,
    'description'  => 'Tag portfolio items with relevant keywords',
) );
```

### Default Taxonomy Configuration

```php
$defaults = array(
    'hierarchical'      => true,
    'public'            => true,
    'show_ui'           => true,
    'show_admin_column' => true,
    'show_in_nav_menus' => true,
    'show_tagcloud'     => true,
    'show_in_rest'      => true,
);
```

## Block Templates

Block templates provide a structured starting point for content creation.

### Setting Block Templates

```php
$portfolio->set_block_template( array(
    // Simple heading
    array( 'core/heading', array(
        'level' => 2,
        'placeholder' => 'Project Overview'
    ) ),

    // Paragraph with placeholder
    array( 'core/paragraph', array(
        'placeholder' => 'Describe your project...'
    ) ),

    // Gallery block
    array( 'core/gallery', array(
        'linkTo' => 'media'
    ) ),

    // Columns with nested blocks
    array( 'core/columns', array(), array(
        array( 'core/column', array(), array(
            array( 'core/heading', array( 'level' => 3, 'content' => 'Details' ) ),
            array( 'core/list' )
        ) ),
        array( 'core/column', array(), array(
            array( 'core/heading', array( 'level' => 3, 'content' => 'Technologies' ) ),
            array( 'core/paragraph' )
        ) )
    ) )
) );
```

### Block Template Structure

Each block in the template is an array with:

1. **Block name** (string) - e.g., `'core/heading'`
2. **Attributes** (array) - Block-specific settings
3. **Inner blocks** (array, optional) - Nested blocks for containers

### Common Block Types

| Block Type       | Purpose                 | Common Attributes                 |
| ---------------- | ----------------------- | --------------------------------- |
| `core/heading`   | Headings (H1-H6)        | `level`, `content`, `placeholder` |
| `core/paragraph` | Text content            | `placeholder`, `content`          |
| `core/list`      | Bulleted/numbered lists | `ordered`                         |
| `core/gallery`   | Image galleries         | `linkTo`, `columns`               |
| `core/columns`   | Multi-column layouts    | `columns`                         |
| `core/image`     | Single images           | `align`, `caption`                |
| `core/quote`     | Blockquotes             | `citation`                        |

## Migration Utilities

The framework includes comprehensive migration utilities for converting legacy CPTs to block-compatible versions.

### Basic Migration

```php
use HoGScaffold\Classes\CPT_Migration;

// Define meta field to block mapping
$mapping = array(
    'old_description' => array(
        'block_type' => 'core/paragraph',
        'attributes' => array(),
    ),
    'old_features' => array(
        'block_type' => 'core/list',
        'attributes' => array(),
    ),
);

// Create migration instance
$migration = new CPT_Migration( 'legacy_projects', $mapping );

// Run migration
$results = $migration->migrate_all_posts( 25 );
```

### Migration Mapping Types

| Block Type       | Use Case                | Meta Value Format               |
| ---------------- | ----------------------- | ------------------------------- |
| `core/paragraph` | Simple text             | String                          |
| `core/heading`   | Titles, headings        | String                          |
| `core/list`      | Features, bullet points | Array or comma-separated string |
| `core/image`     | Single images           | Attachment ID (integer)         |
| `core/gallery`   | Multiple images         | Array of attachment IDs         |
| `custom`         | Complex transformations | Any format with callback        |

### Custom Migration Callbacks

```php
$mapping = array(
    'complex_data' => array(
        'block_type' => 'custom',
        'callback'   => 'my_custom_converter',
    ),
);

function my_custom_converter( $meta_key, $meta_value, $attributes ) {
    // Transform meta value into block content
    return '<!-- wp:paragraph --><p>' . esc_html( $meta_value ) . '</p><!-- /wp:paragraph -->';
}
```

### Batch Migration with Progress

```php
$results = hog_scaffold_batch_migrate_with_progress( 'portfolio', $mapping, 25 );

echo "Processed: {$results['total_processed']} posts\n";
echo "Successful: {$results['total_successful']}\n";
echo "Failed: {$results['total_failed']}\n";
echo "Duration: {$results['duration']} seconds\n";
```

### Migration Rollback

```php
// Rollback all migrated posts
$results = hog_scaffold_rollback_migration( 'portfolio' );

// Rollback specific posts
$results = hog_scaffold_rollback_migration( 'portfolio', array( 123, 456, 789 ) );
```

### WP-CLI Migration Commands

```bash
# Check migration status
wp hog-scaffold migrate-cpt portfolio --dry-run

# Run migration with custom batch size
wp hog-scaffold migrate-cpt portfolio --batch-size=50

# Validate migration results
wp hog-scaffold validate-migration portfolio
```

## Admin Interface

The framework provides a comprehensive admin interface for CPT management.

### Accessing the Interface

Navigate to **Tools > CPT Management** in the WordPress admin.

### Interface Sections

1. **Overview** - Lists all registered CPTs with statistics
2. **Migration** - Tools for migrating legacy content
3. **Documentation** - Built-in framework documentation

### Custom Admin Columns

The framework automatically adds relevant admin columns:

```php
// Columns are added automatically based on meta fields
// Client | Project Date | Featured | Categories
```

### Sortable Columns

Admin columns are automatically sortable for better management:

```php
// Users can sort by client name, project date, featured status, etc.
```

## REST API Integration

### Automatic Endpoints

CPTs created with the framework automatically get REST API endpoints:

```
GET    /wp-json/wp/v2/portfolio          # List portfolio items
GET    /wp-json/wp/v2/portfolio/123      # Get specific item
POST   /wp-json/wp/v2/portfolio          # Create new item
PUT    /wp-json/wp/v2/portfolio/123      # Update item
DELETE /wp-json/wp/v2/portfolio/123      # Delete item
```

### Custom Endpoints

The framework supports custom REST endpoints:

```php
// Featured portfolio items
GET /wp-json/hog-scaffold/v1/portfolio/featured

// Portfolio by category
GET /wp-json/hog-scaffold/v1/portfolio/category/web-design
```

### Meta Field Exposure

Meta fields with `'show_in_rest' => true` are included in API responses:

```json
{
	"id": 123,
	"title": { "rendered": "Project Title" },
	"meta": {
		"client_name": "Acme Corp",
		"project_date": "2024-01-15",
		"featured_project": true
	}
}
```

## Best Practices

### 1. CPT Naming Conventions

- Use lowercase, underscores for post type keys: `portfolio`, `team_member`
- Keep post type keys under 20 characters
- Use descriptive, user-friendly display names
- Choose SEO-friendly URL slugs

### 2. Meta Field Design

- Use descriptive meta keys: `client_name` not `cn`
- Set appropriate field types for validation
- Include helpful descriptions for admin users
- Enable REST API exposure when needed

### 3. Block Template Strategy

- Start with essential content structure
- Use placeholders to guide content creation
- Group related content in columns/sections
- Keep templates flexible, not overly restrictive

### 4. Taxonomy Organization

- Use hierarchical taxonomies for categories
- Use non-hierarchical taxonomies for tags
- Choose clear, descriptive taxonomy names
- Consider taxonomy relationships and overlap

### 5. Performance Considerations

- Limit meta fields to essential data
- Use appropriate field types for data
- Consider caching for complex queries
- Optimize REST API responses

### 6. Security Best Practices

- Validate and sanitize all input
- Use proper capability checks
- Implement nonce verification
- Escape output appropriately

## Troubleshooting

### Common Issues

#### CPT Not Appearing in Admin

**Problem:** Custom post type doesn't show in admin menu.

**Solutions:**

- Check `'show_ui' => true` in CPT options
- Verify user has appropriate capabilities
- Flush rewrite rules: Settings > Permalinks > Save

#### Meta Fields Not Saving

**Problem:** Meta field values aren't being saved.

**Solutions:**

- Verify nonce implementation in metabox
- Check user capabilities for editing posts
- Ensure proper sanitization in save function
- Check for JavaScript errors in admin

#### Block Template Not Loading

**Problem:** Block template doesn't appear when creating new posts.

**Solutions:**

- Verify template array structure
- Check block names are correct (e.g., `core/heading`)
- Ensure template is set before CPT registration
- Clear any caching plugins

#### REST API Meta Not Showing

**Problem:** Meta fields don't appear in REST API responses.

**Solutions:**

- Set `'show_in_rest' => true` in meta field args
- Verify meta field registration timing
- Check REST API permissions
- Test with authenticated requests

#### Migration Failures

**Problem:** Migration process fails or produces errors.

**Solutions:**

- Check meta field mapping configuration
- Verify source post type exists
- Test with smaller batch sizes
- Review error logs for specific issues
- Ensure adequate server resources

### Debug Mode

Enable WordPress debug mode for detailed error information:

```php
// wp-config.php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

### Getting Help

1. Check the admin documentation tab
2. Review error logs in `/wp-content/debug.log`
3. Test with default WordPress theme
4. Disable other plugins to isolate conflicts
5. Verify server PHP version compatibility

## Advanced Usage

### Custom Post Type Relationships

```php
// Add relationship meta field
$portfolio->add_meta_field( 'related_projects', array(
    'type'        => 'array',
    'description' => 'Related portfolio items',
    'show_in_rest' => array(
        'schema' => array(
            'type'  => 'array',
            'items' => array( 'type' => 'integer' ),
        ),
    ),
), 'select' );
```

### Custom Block Patterns

```php
// Register custom block pattern for portfolio
register_block_pattern( 'hog-scaffold/portfolio-showcase', array(
    'title'       => __( 'Portfolio Showcase', 'hog-scaffold' ),
    'description' => __( 'A showcase layout for portfolio items', 'hog-scaffold' ),
    'content'     => '<!-- wp:columns -->...',
    'categories'  => array( 'portfolio' ),
) );
```

### Integration with Page Builders

The framework is compatible with popular page builders:

- **Elementor** - Meta fields available as dynamic content
- **Beaver Builder** - Custom field modules supported
- **Gutenberg** - Native block editor integration
- **ACF** - Can work alongside ACF fields

---

## Conclusion

The HoG Scaffold CPT Framework provides a comprehensive, modern approach to custom post type development in WordPress. With its focus on block editor compatibility, developer experience, and extensibility, it serves as a solid foundation for any WordPress project requiring custom content types.

For additional support or to contribute to the framework, please refer to the project documentation or contact the development team.
