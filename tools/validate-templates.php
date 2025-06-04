<?php
/**
 * Template Validation Script
 * 
 * This script validates FSE template files for proper block formatting
 * Usage: php tools/validate-templates.php
 * 
 * @package HoGScaffold
 */

// Validate template files
$template_files = [
  __DIR__ . '/../templates/index.html',
  __DIR__ . '/../templates/single.html',
  __DIR__ . '/../templates/page.html',
  __DIR__ . '/../templates/archive.html',
  __DIR__ . '/../templates/search.html',
  __DIR__ . '/../templates/404.html',
  __DIR__ . '/../parts/header.html',
  __DIR__ . '/../parts/footer.html',
];

echo "Validating FSE Template Files...\n";
echo "================================\n\n";

foreach ($template_files as $file) {
  $filename = basename($file);
  echo "Validating: {$filename}\n";

  if (!file_exists($file)) {
    echo "❌ File not found: {$file}\n";
    continue;
  }

  $content = file_get_contents($file);

  // Check for proper block comment format
  $block_pattern = '/<!-- wp:[\w\/-]+(?:\s+\{[^}]*\})?\s*(?:\/-->|-->)/';
  $matches = preg_match_all($block_pattern, $content, $blocks);

  if ($matches) {
    echo "✅ Found {$matches} valid block comments\n";
  } else {
    echo "⚠️  No valid block comments found\n";
  }

  // Check for template part blocks
  if (strpos($content, 'wp:template-part') !== false) {
    if (strpos($content, '"theme":"hog-scaffold"') !== false) {
      echo "✅ Template part has proper theme attribute\n";
    } else {
      echo "❌ Template part missing theme attribute\n";
    }
  }

  // Check for navigation blocks with ref attributes
  if (strpos($content, 'wp:navigation') !== false) {
    if (strpos($content, '"ref":') !== false) {
      echo "⚠️  Navigation block has ref attribute (may cause validation issues)\n";
    } else {
      echo "✅ Navigation block clean\n";
    }
  }

  echo "\n";
}

echo "Validation complete!\n";
echo "\nIf you see validation issues:\n";
echo "1. Ensure all template-part blocks have theme=\"hog-scaffold\" attribute\n";
echo "2. Remove any ref attributes from navigation blocks\n";
echo "3. Check that block comments are properly formatted\n";
echo "4. Clear WordPress caches and refresh the editor\n";
?>