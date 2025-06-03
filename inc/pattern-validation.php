<?php
/**
 * Block Pattern Validation Utilities
 * 
 * Development utilities for testing and validating block patterns
 * 
 * @package HoGScaffold\Patterns
 */

namespace HoGScaffold\Patterns;

/**
 * Validation utilities setup
 */
function setup()
{
  // Only load in development/staging environments
  if (defined('WP_DEBUG') && WP_DEBUG) {
    add_action('wp_footer', __NAMESPACE__ . '\\add_pattern_debug_info');
    add_action('admin_footer', __NAMESPACE__ . '\\add_admin_pattern_debug');
  }
}

/**
 * Add pattern debug information to frontend
 */
function add_pattern_debug_info()
{
  if (!current_user_can('manage_options')) {
    return;
  }

  $patterns = get_registered_patterns();

  if (empty($patterns)) {
    return;
  }

  echo '<script>';
  echo 'console.group("🎨 Block Patterns Debug Info");';
  echo 'console.log("Registered Patterns:", ' . wp_json_encode($patterns) . ');';
  echo 'console.groupEnd();';
  echo '</script>';
}

/**
 * Add pattern debug information to admin
 */
function add_admin_pattern_debug()
{
  $screen = get_current_screen();

  if (!$screen || !in_array($screen->id, ['post', 'page'], true)) {
    return;
  }

  if (!current_user_can('edit_posts')) {
    return;
  }

  $patterns = get_registered_patterns();
  $categories = get_registered_pattern_categories();

  echo '<script>';
  echo 'console.group("🎨 Block Patterns Admin Debug");';
  echo 'console.log("Registered Patterns:", ' . wp_json_encode($patterns) . ');';
  echo 'console.log("Pattern Categories:", ' . wp_json_encode($categories) . ');';
  echo 'console.groupEnd();';
  echo '</script>';
}

/**
 * Get all registered patterns with metadata
 */
function get_registered_patterns()
{
  $registry = WP_Block_Patterns_Registry::get_instance();
  $patterns = $registry->get_all_registered();

  $theme_patterns = [];

  foreach ($patterns as $pattern) {
    if (strpos($pattern['name'], 'hog-scaffold/') === 0) {
      $theme_patterns[] = [
        'name' => $pattern['name'],
        'title' => $pattern['title'],
        'description' => $pattern['description'] ?? '',
        'categories' => $pattern['categories'] ?? [],
        'keywords' => $pattern['keywords'] ?? [],
        'viewportWidth' => $pattern['viewportWidth'] ?? null,
      ];
    }
  }

  return $theme_patterns;
}

/**
 * Get all registered pattern categories
 */
function get_registered_pattern_categories()
{
  $registry = WP_Block_Pattern_Categories_Registry::get_instance();
  $categories = $registry->get_all_registered();

  $theme_categories = [];

  foreach ($categories as $category) {
    if (strpos($category['name'], 'hog-scaffold-') === 0) {
      $theme_categories[] = [
        'name' => $category['name'],
        'label' => $category['label'],
        'description' => $category['description'] ?? '',
      ];
    }
  }

  return $theme_categories;
}

/**
 * Validate pattern file syntax
 */
function validate_pattern_file($pattern_file)
{
  if (!file_exists($pattern_file)) {
    return [
      'valid' => false,
      'error' => 'Pattern file does not exist'
    ];
  }

  // Check for PHP syntax errors
  $output = shell_exec("php -l " . escapeshellarg($pattern_file) . " 2>&1");

  if (strpos($output, 'No syntax errors detected') === false) {
    return [
      'valid' => false,
      'error' => 'PHP syntax error: ' . $output
    ];
  }

  // Check for required pattern header
  $content = file_get_contents($pattern_file);

  if (!preg_match('/\/\*\*\s*\*\s*Title:/', $content)) {
    return [
      'valid' => false,
      'error' => 'Missing required pattern header with Title'
    ];
  }

  // Check for WordPress block markup
  if (!preg_match('/<!-- wp:/', $content)) {
    return [
      'valid' => false,
      'error' => 'No WordPress block markup found'
    ];
  }

  return [
    'valid' => true,
    'error' => null
  ];
}

/**
 * Get pattern validation report
 */
function get_pattern_validation_report()
{
  $pattern_dir = get_template_directory() . '/patterns/';
  $pattern_files = glob($pattern_dir . '*.php');

  $report = [
    'total_files' => count($pattern_files),
    'valid_files' => 0,
    'invalid_files' => 0,
    'files' => []
  ];

  foreach ($pattern_files as $file) {
    $filename = basename($file);
    $validation = validate_pattern_file($file);

    $report['files'][$filename] = $validation;

    if ($validation['valid']) {
      $report['valid_files']++;
    } else {
      $report['invalid_files']++;
    }
  }

  return $report;
}

/**
 * Add admin menu for pattern validation (development only)
 */
function add_validation_admin_menu()
{
  if (!defined('WP_DEBUG') || !WP_DEBUG) {
    return;
  }

  add_submenu_page(
    'tools.php',
    'Pattern Validation',
    'Pattern Validation',
    'manage_options',
    'pattern-validation',
    __NAMESPACE__ . '\\render_validation_page'
  );
}

/**
 * Render pattern validation admin page
 */
function render_validation_page()
{
  $report = get_pattern_validation_report();
  $patterns = get_registered_patterns();
  $categories = get_registered_pattern_categories();

  ?>
  <div class="wrap">
    <h1>Block Pattern Validation</h1>

    <div class="notice notice-info">
      <p><strong>Development Tool:</strong> This page is only available when WP_DEBUG is enabled.</p>
    </div>

    <h2>Pattern Files Validation</h2>
    <table class="wp-list-table widefat fixed striped">
      <thead>
        <tr>
          <th>File</th>
          <th>Status</th>
          <th>Error</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($report['files'] as $filename => $validation): ?>
          <tr>
            <td><?php echo esc_html($filename); ?></td>
            <td>
              <?php if ($validation['valid']): ?>
                <span style="color: green;">✓ Valid</span>
              <?php else: ?>
                <span style="color: red;">✗ Invalid</span>
              <?php endif; ?>
            </td>
            <td><?php echo esc_html($validation['error'] ?? ''); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h2>Registered Patterns</h2>
    <table class="wp-list-table widefat fixed striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Title</th>
          <th>Categories</th>
          <th>Keywords</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($patterns as $pattern): ?>
          <tr>
            <td><?php echo esc_html($pattern['name']); ?></td>
            <td><?php echo esc_html($pattern['title']); ?></td>
            <td><?php echo esc_html(implode(', ', $pattern['categories'])); ?></td>
            <td><?php echo esc_html(implode(', ', $pattern['keywords'])); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h2>Pattern Categories</h2>
    <table class="wp-list-table widefat fixed striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Label</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($categories as $category): ?>
          <tr>
            <td><?php echo esc_html($category['name']); ?></td>
            <td><?php echo esc_html($category['label']); ?></td>
            <td><?php echo esc_html($category['description']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h2>Summary</h2>
    <ul>
      <li><strong>Total Pattern Files:</strong> <?php echo $report['total_files']; ?></li>
      <li><strong>Valid Files:</strong> <?php echo $report['valid_files']; ?></li>
      <li><strong>Invalid Files:</strong> <?php echo $report['invalid_files']; ?></li>
      <li><strong>Registered Patterns:</strong> <?php echo count($patterns); ?></li>
      <li><strong>Pattern Categories:</strong> <?php echo count($categories); ?></li>
    </ul>
  </div>
  <?php
}

// Initialize validation utilities
add_action('init', __NAMESPACE__ . '\\setup');
add_action('admin_menu', __NAMESPACE__ . '\\add_validation_admin_menu');