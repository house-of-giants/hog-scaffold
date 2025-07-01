# Custom Post Types

This theme includes a simplified custom post type system that makes it easy to register and customize CPTs using WordPress best practices.

## Basic Usage

### Registering a Custom Post Type

```php
// In your theme files (typically inc/cpt/definitions.php)
add_action('init', function() {
    hog_scaffold_register_post_type('book', array(
        'labels' => array(
            'name' => __('Books', 'hog-scaffold'),
            'singular_name' => __('Book', 'hog-scaffold'),
        ),
        'menu_icon' => 'dashicons-book-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'has_archive' => true,
    ));
});
```

### Smart Label Generation

The system can automatically generate labels for you:

```php
hog_scaffold_register_post_type('product', array(
    'labels' => hog_scaffold_generate_cpt_labels('Product', 'Products'),
    'menu_icon' => 'dashicons-cart',
));
```

## Adding Meta Fields

### Register Meta Fields

```php
// Register meta fields for your CPT
hog_scaffold_register_post_meta('book', 'isbn', array(
    'type' => 'string',
    'description' => 'Book ISBN number',
    'show_in_rest' => true, // Makes it available in REST API
));

hog_scaffold_register_post_meta('book', 'publication_date', array(
    'type' => 'string',
    'description' => 'Publication date',
));
```

### Add Meta Boxes

```php
// Simple meta box
add_action('add_meta_boxes', function() {
    hog_scaffold_add_simple_meta_box('book', 'book_details', 'Book Details', array(
        'isbn' => array(
            'label' => 'ISBN',
            'type' => 'text',
        ),
        'publication_date' => array(
            'label' => 'Publication Date',
            'type' => 'date',
        ),
        'price' => array(
            'label' => 'Price',
            'type' => 'number',
            'step' => '0.01',
        ),
    ));
});
```

## Adding Taxonomies

```php
add_action('init', function() {
    // Hierarchical taxonomy (like categories)
    hog_scaffold_register_taxonomy('book_genre', 'book', array(
        'labels' => hog_scaffold_generate_cpt_labels('Genre', 'Genres'),
        'hierarchical' => true,
    ));

    // Non-hierarchical taxonomy (like tags)
    hog_scaffold_register_taxonomy('book_tag', 'book', array(
        'labels' => hog_scaffold_generate_cpt_labels('Tag', 'Tags', false),
        'hierarchical' => false,
    ));
});
```

## Customizing Admin Columns

```php
// Add custom columns to the admin list
add_action('init', function() {
    hog_scaffold_add_admin_columns('book', array(
        'isbn' => 'ISBN',
        'publication_date' => 'Published',
        'book_genre' => 'Genre',
    ));

    // Make columns sortable
    hog_scaffold_make_columns_sortable('book', array(
        'publication_date' => 'publication_date',
    ));
});
```

## Query Helpers

### Get Posts by Taxonomy

```php
// Get all books in the "fiction" genre
$fiction_books = hog_scaffold_get_posts_by_taxonomy('book', 'book_genre', 'fiction');

// Get books with multiple taxonomy terms
$books = hog_scaffold_get_posts_by_taxonomy('book', 'book_genre', array('fiction', 'mystery'));
```

### Featured Content Helper

```php
// Add a featured checkbox to your CPT
add_action('add_meta_boxes', function() {
    hog_scaffold_add_featured_meta_box('book');
});

// Query featured books
$featured_books = get_posts(array(
    'post_type' => 'book',
    'meta_key' => '_featured',
    'meta_value' => '1',
    'posts_per_page' => 5,
));
```

## Complete Example

Here's a complete example of registering a "Book" CPT with all features:

```php
// Register the post type
add_action('init', function() {
    hog_scaffold_register_post_type('book', array(
        'labels' => hog_scaffold_generate_cpt_labels('Book', 'Books'),
        'menu_icon' => 'dashicons-book-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'has_archive' => true,
    ));

    // Register taxonomies
    hog_scaffold_register_taxonomy('book_genre', 'book', array(
        'labels' => hog_scaffold_generate_cpt_labels('Genre', 'Genres'),
        'hierarchical' => true,
    ));

    // Register meta fields
    hog_scaffold_register_post_meta('book', 'isbn', array(
        'type' => 'string',
        'show_in_rest' => true,
    ));

    hog_scaffold_register_post_meta('book', 'publication_date', array(
        'type' => 'string',
    ));

    // Add admin columns
    hog_scaffold_add_admin_columns('book', array(
        'isbn' => 'ISBN',
        'publication_date' => 'Published',
        'book_genre' => 'Genre',
    ));

    hog_scaffold_make_columns_sortable('book', array(
        'publication_date' => 'publication_date',
    ));
});

// Add meta boxes
add_action('add_meta_boxes', function() {
    hog_scaffold_add_simple_meta_box('book', 'book_details', 'Book Details', array(
        'isbn' => array(
            'label' => 'ISBN',
            'type' => 'text',
        ),
        'publication_date' => array(
            'label' => 'Publication Date',
            'type' => 'date',
        ),
    ));

    hog_scaffold_add_featured_meta_box('book');
});
```

## Available Meta Box Field Types

- `text` - Standard text input
- `textarea` - Multi-line text
- `date` - Date picker
- `url` - URL input with validation
- `email` - Email input with validation
- `number` - Number input
- `select` - Dropdown select (requires `options` array)
- `checkbox` - Single checkbox
- `radio` - Radio buttons (requires `options` array)

## File Organization

- **CPT Definitions**: `inc/cpt/definitions.php` - Add your CPT registrations here
- **Core Functions**: `inc/cpt/functions.php` - Core registration functions (don't modify)
- **Helper Functions**: `inc/cpt/helpers.php` - Helper utilities (don't modify)

## Templates

WordPress will automatically look for these template files in your theme:

- `single-{post_type}.php` - Single post template
- `archive-{post_type}.php` - Archive template
- `taxonomy-{taxonomy}.php` - Taxonomy archive template

Example: `single-book.php`, `archive-book.php`, `taxonomy-book_genre.php`
