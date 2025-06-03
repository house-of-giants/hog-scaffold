<?php
/**
 * Register Interactive Tabs Block
 *
 * @package HoGScaffold\Blocks\InteractiveTabs
 */

namespace HoGScaffold\Blocks\InteractiveTabs;

// Import security utilities
use HoGScaffold\Blocks\Utils\Block_Security;

/**
 * Register the Interactive Tabs block
 *
 * @return void
 */
function register()
{
  // Register the block using block.json
  register_block_type(
    HOG_SCAFFOLD_BLOCK_DIR . 'interactive-tabs-block/block.json',
    array(
      'render_callback' => __NAMESPACE__ . '\render_interactive_tabs',
    )
  );
}

/**
 * Render callback for the Interactive Tabs block
 *
 * @param array  $attributes Block attributes.
 * @param string $content    Block content.
 * @param object $block      Block object.
 * @return string Rendered block HTML.
 */
function render_interactive_tabs($attributes, $content, $block)
{
  $tabs = $attributes['tabs'] ?? array();
  $active_tab = $attributes['activeTab'] ?? 0;
  $block_id = 'interactive-tabs-' . wp_unique_id();

  if (empty($tabs)) {
    return '';
  }

  // Sanitize and validate tab data using security utilities
  $sanitized_tabs = array();
  foreach ($tabs as $tab) {
    $sanitized_tab = array(
      'title' => Block_Security::sanitize_input($tab['title'] ?? '', 'text'),
      'content' => Block_Security::sanitize_input($tab['content'] ?? '', 'html')
    );

    // Skip empty tabs
    if (!empty($sanitized_tab['title']) && !empty($sanitized_tab['content'])) {
      $sanitized_tabs[] = $sanitized_tab;
    }
  }

  if (empty($sanitized_tabs)) {
    return '';
  }

  // Ensure active tab is within bounds
  $active_tab = max(0, min($active_tab, count($sanitized_tabs) - 1));

  // Generate nonce for security
  $nonce = Block_Security::create_nonce('interactive_tabs_' . $block_id);

  // Log security event (only in debug mode)
  Block_Security::log_security_event(
    'interactive_tabs_render',
    array(
      'block_id' => $block_id,
      'tab_count' => count($sanitized_tabs),
      'active_tab' => $active_tab
    ),
    'info'
  );

  $wrapper_attributes = get_block_wrapper_attributes(
    array(
      'class' => 'interactive-tabs-block',
      'data-block-id' => esc_attr($block_id),
      'data-nonce' => esc_attr($nonce),
    )
  );

  ob_start();
  ?>
  <div <?php echo $wrapper_attributes; ?>>
    <div class="tabs-navigation" role="tablist">
      <?php foreach ($sanitized_tabs as $index => $tab): ?>
        <button class="tab-button <?php echo $index === $active_tab ? 'active' : ''; ?>" role="tab"
          aria-selected="<?php echo $index === $active_tab ? 'true' : 'false'; ?>"
          aria-controls="tabpanel-<?php echo esc_attr($block_id); ?>-<?php echo esc_attr($index); ?>"
          id="tab-<?php echo esc_attr($block_id); ?>-<?php echo esc_attr($index); ?>"
          data-tab-index="<?php echo esc_attr($index); ?>">
          <?php echo Block_Security::escape_output($tab['title'], 'html'); ?>
        </button>
      <?php endforeach; ?>
    </div>

    <div class="tabs-content">
      <?php foreach ($sanitized_tabs as $index => $tab): ?>
        <div class="tab-panel <?php echo $index === $active_tab ? 'active' : ''; ?>" role="tabpanel"
          id="tabpanel-<?php echo esc_attr($block_id); ?>-<?php echo esc_attr($index); ?>"
          aria-labelledby="tab-<?php echo esc_attr($block_id); ?>-<?php echo esc_attr($index); ?>" <?php echo $index !== $active_tab ? 'hidden' : ''; ?>>
          <div class="tab-content">
            <?php echo $tab['content']; // Already sanitized with wp_kses_post via Block_Security ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php
  return ob_get_clean();
}