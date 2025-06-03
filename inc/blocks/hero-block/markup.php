<?php
/**
 * Hero Block Markup
 *
 * @package HoGScaffold\Blocks\HeroBlock
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
$heading = $attributes['heading'] ?? 'Welcome to our website';
$subheading = $attributes['subheading'] ?? 'Create something amazing with us';
$layout = $attributes['layout'] ?? 'centered';
$background_image = $attributes['backgroundImage'] ?? array();
$background_video = $attributes['backgroundVideo'] ?? '';
$show_overlay = $attributes['showOverlay'] ?? true;
$overlay_opacity = $attributes['overlayOpacity'] ?? 0.4;
$primary_button = $attributes['primaryButton'] ?? array();
$secondary_button = $attributes['secondaryButton'] ?? array();
$min_height = $attributes['minHeight'] ?? '60vh';

// Build CSS classes
$css_classes = array(
  $class_name,
  'is-style-' . $layout,
);

// Add any additional classes from the block
if (!empty($block['className'])) {
  $css_classes[] = $block['className'];
}

$css_class_string = implode(' ', array_filter($css_classes));

// Build inline styles
$inline_styles = array(
  'min-height: ' . esc_attr($min_height),
);

if (!empty($background_image['url'])) {
  $inline_styles[] = 'background-image: url(' . esc_url($background_image['url']) . ')';
}

$inline_style_string = implode('; ', $inline_styles);
?>

<div class="<?php echo esc_attr($css_class_string); ?>" style="<?php echo esc_attr($inline_style_string); ?>"
  role="banner">
  <?php if (!empty($background_image['url'])): ?>
    <div class="wp-block-hero__background"
      style="background-image: url(<?php echo esc_url($background_image['url']); ?>);"></div>
  <?php endif; ?>

  <?php if (!empty($background_video)): ?>
    <video class="wp-block-hero__video" autoplay muted loop
      aria-label="<?php esc_attr_e('Background video', 'hog-scaffold'); ?>">
      <source src="<?php echo esc_url($background_video); ?>" type="video/mp4">
      <?php esc_html_e('Your browser does not support the video tag.', 'hog-scaffold'); ?>
    </video>
  <?php endif; ?>

  <?php if ($show_overlay): ?>
    <div class="wp-block-hero__overlay"
      style="background-color: rgba(0, 0, 0, <?php echo esc_attr($overlay_opacity); ?>);"></div>
  <?php endif; ?>

  <div class="wp-block-hero__content">
    <?php if (!empty($heading)): ?>
      <h1 class="wp-block-hero__title"><?php echo wp_kses_post($heading); ?></h1>
    <?php endif; ?>

    <?php if (!empty($subheading)): ?>
      <p class="wp-block-hero__subtitle"><?php echo wp_kses_post($subheading); ?></p>
    <?php endif; ?>

    <?php if (!empty($primary_button['text']) || !empty($secondary_button['text'])): ?>
      <div class="wp-block-hero__actions">
        <?php if (!empty($primary_button['text'])): ?>
          <a href="<?php echo esc_url($primary_button['url'] ?? '#'); ?>"
            class="wp-block-button__link wp-block-button__link--primary" <?php if (!empty($primary_button['opensInNewTab'])): ?> target="_blank" rel="noopener noreferrer" <?php endif; ?>>
            <?php echo esc_html($primary_button['text']); ?>
          </a>
        <?php endif; ?>

        <?php if (!empty($secondary_button['text'])): ?>
          <a href="<?php echo esc_url($secondary_button['url'] ?? '#'); ?>"
            class="wp-block-button__link wp-block-button__link--secondary" <?php if (!empty($secondary_button['opensInNewTab'])): ?> target="_blank" rel="noopener noreferrer" <?php endif; ?>>
            <?php echo esc_html($secondary_button['text']); ?>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>