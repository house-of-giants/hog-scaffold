<?php
/**
 * PSR-4 Autoloader for HoG Scaffold theme
 *
 * This file provides custom autoloading functionality in addition to Composer's autoloader.
 *
 * @package HoG_Scaffold
 */

// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}

/**
 * Custom autoloader for theme classes
 *
 * @param string $class_name The fully-qualified class name
 */
function hog_scaffold_autoloader($class_name)
{
  // Project-specific namespace prefix
  $prefix = 'HoGScaffold\\';

  // Base directory for the namespace prefix
  $base_dir = HOG_SCAFFOLD_INC . 'classes/';

  // Does the class use the namespace prefix?
  $len = strlen($prefix);
  if (strncmp($prefix, $class_name, $len) !== 0) {
    // No, move to the next registered autoloader
    return;
  }

  // Get the relative class name
  $relative_class = substr($class_name, $len);

  // Replace the namespace prefix with the base directory, replace namespace
  // separators with directory separators in the relative class name, append
  // with .php
  $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

  // If the file exists, require it
  if (file_exists($file)) {
    require $file;
  }
}

// Register the autoloader
spl_autoload_register('hog_scaffold_autoloader');

/**
 * Load Composer autoloader if available
 */
$composer_autoload = HOG_SCAFFOLD_PATH . 'vendor/autoload.php';
if (file_exists($composer_autoload)) {
  require_once $composer_autoload;
}
