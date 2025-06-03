<?php
/**
 * Service Cards Block Markup
 *
 * @package HoGScaffold\Blocks\ServiceCardsBlock
 *
 * @var array $args {
 *     @type string $class_name CSS class name for the block.
 *     @type array  $attributes Block attributes.
 *     @type string $content    Block content.
 *     @type array  $block      Block information.
 * }
 */

$class_name = $args['class_name'] ?? '';
$attributes = $args['attributes'] ?? array();
$content = $args['content'] ?? '';
$block = $args['block'] ?? array();

// Extract attributes with defaults
$cards = $attributes['cards'] ?? array();
$columns = $attributes['columns'] ?? 3;
$layout = $attributes['layout'] ?? 'grid';
$show_icons = $attributes['showIcons'] ?? true;
$card_style = $attributes['cardStyle'] ?? 'default';

// Build CSS classes
$css_classes = array(
  $class_name,
  'is-layout-' . $layout,
  'is-style-' . $card_style,
  'columns-' . $columns,
);

// Add any additional classes from the block
if (!empty($block['className'])) {
  $css_classes[] = $block['className'];
}

$css_class_string = implode(' ', array_filter($css_classes));

// Return early if no cards
if (empty($cards)) {
  return;
}
?>

<div class="<?php echo esc_attr($css_class_string); ?>">
  <div class="wp-block-service-cards__container">
    <?php foreach ($cards as $card): ?>
      <?php
      $card_title = $card['title'] ?? '';
      $card_description = $card['description'] ?? '';
      $card_icon = $card['icon'] ?? '';
      $card_link = $card['link'] ?? array();
      $link_url = $card_link['url'] ?? '#';
      $opens_in_new_tab = $card_link['opensInNewTab'] ?? false;
      ?>

      <div class="wp-block-service-cards__card">
        <?php if ($show_icons && !empty($card_icon)): ?>
          <div class="wp-block-service-cards__icon">
            <?php echo esc_html($card_icon); ?>
          </div>
        <?php endif; ?>

        <div class="wp-block-service-cards__content">
          <?php if (!empty($card_title)): ?>
            <h3 class="wp-block-service-cards__title">
              <?php echo wp_kses_post($card_title); ?>
            </h3>
          <?php endif; ?>

          <?php if (!empty($card_description)): ?>
            <p class="wp-block-service-cards__description">
              <?php echo wp_kses_post($card_description); ?>
            </p>
          <?php endif; ?>

          <?php if (!empty($link_url) && $link_url !== '#'): ?>
            <div class="wp-block-service-cards__link">
              <a href="<?php echo esc_url($link_url); ?>" <?php if ($opens_in_new_tab): ?> target="_blank"
                  rel="noopener noreferrer" <?php endif; ?>>
                <?php esc_html_e('Learn More', 'hog'); ?>
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>