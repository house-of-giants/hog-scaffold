<?php
/**
 * CTA Block Frontend Markup
 *
 * @package HoGScaffold
 * @var array $attributes Block attributes passed from render function
 */

// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}

// Set default values
$defaults = array(
  'heading' => 'Ready to Get Started?',
  'text' => 'Join thousands of satisfied customers and take the next step towards achieving your goals.',
  'buttonText' => 'Get Started Now',
  'buttonUrl' => '',
  'buttonStyle' => 'primary',
  'buttonSize' => 'medium',
  'buttonOpenInNewTab' => false,
  'layout' => 'centered',
  'backgroundType' => 'color',
  'backgroundColor' => '',
  'backgroundGradient' => '',
  'backgroundImage' => array(
    'url' => '',
    'alt' => '',
    'id' => 0,
  ),
  'backgroundPosition' => 'center center',
  'backgroundSize' => 'cover',
  'backgroundOverlay' => false,
  'backgroundOverlayColor' => 'rgba(0, 0, 0, 0.5)',
  'width' => 'normal',
  'spacing' => 'medium',
);

// Merge with provided attributes
$attributes = wp_parse_args($attributes, $defaults);

// Build CSS classes
$classes = array(
  'wp-block-cta',
  'is-layout-' . esc_attr($attributes['layout']),
  'background-type-' . esc_attr($attributes['backgroundType']),
  'spacing-' . esc_attr($attributes['spacing']),
  'width-' . esc_attr($attributes['width']),
);

// Build inline styles
$styles = array();

if ('color' === $attributes['backgroundType'] && !empty($attributes['backgroundColor'])) {
  $styles[] = 'background-color: ' . esc_attr($attributes['backgroundColor']);
}

if ('gradient' === $attributes['backgroundType'] && !empty($attributes['backgroundGradient'])) {
  $styles[] = 'background: ' . esc_attr($attributes['backgroundGradient']);
}

if ('image' === $attributes['backgroundType'] && !empty($attributes['backgroundImage']['url'])) {
  $styles[] = 'background-image: url(' . esc_url($attributes['backgroundImage']['url']) . ')';
  $styles[] = 'background-position: ' . esc_attr($attributes['backgroundPosition']);
  $styles[] = 'background-size: ' . esc_attr($attributes['backgroundSize']);
  $styles[] = 'background-repeat: no-repeat';
}

// Build button classes
$button_classes = array(
  'wp-block-cta__button',
  'is-style-' . esc_attr($attributes['buttonStyle']),
  'is-size-' . esc_attr($attributes['buttonSize']),
);

// Build button attributes
$button_attrs = array();
if (!empty($attributes['buttonUrl'])) {
  $button_attrs[] = 'href="' . esc_url($attributes['buttonUrl']) . '"';

  if ($attributes['buttonOpenInNewTab']) {
    $button_attrs[] = 'target="_blank"';
    $button_attrs[] = 'rel="noopener noreferrer"';
  }
}

// Render the block
?>
<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" <?php echo !empty($styles) ? ' style="' . esc_attr(implode('; ', $styles)) . '"' : ''; ?> role="complementary"
  aria-labelledby="cta-heading-<?php echo esc_attr(uniqid()); ?>">
  <?php if ($attributes['backgroundOverlay'] && 'image' === $attributes['backgroundType'] && !empty($attributes['backgroundImage']['url'])): ?>
    <div class="wp-block-cta__overlay"
      style="background-color: <?php echo esc_attr($attributes['backgroundOverlayColor']); ?>"></div>
  <?php endif; ?>

  <div class="wp-block-cta__content">
    <div class="wp-block-cta__text-content">
      <?php if (!empty($attributes['heading'])): ?>
        <h2 class="wp-block-cta__heading" id="cta-heading-<?php echo esc_attr(uniqid()); ?>">
          <?php echo wp_kses_post($attributes['heading']); ?>
        </h2>
      <?php endif; ?>

      <?php if (!empty($attributes['text'])): ?>
        <p class="wp-block-cta__text">
          <?php echo wp_kses_post($attributes['text']); ?>
        </p>
      <?php endif; ?>
    </div>

    <?php if (!empty($attributes['buttonText'])): ?>
      <div class="wp-block-cta__button-container">
        <?php if (!empty($attributes['buttonUrl'])): ?>
          <a class="<?php echo esc_attr(implode(' ', $button_classes)); ?>" <?php echo implode(' ', $button_attrs); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
            <?php echo esc_html($attributes['buttonText']); ?>
          </a>
        <?php else: ?>
          <span class="<?php echo esc_attr(implode(' ', $button_classes)); ?>">
            <?php echo esc_html($attributes['buttonText']); ?>
          </span>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>