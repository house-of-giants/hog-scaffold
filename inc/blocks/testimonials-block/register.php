<?php
/**
 * Testimonials Block
 *
 * @package HoG Scaffold
 */

namespace HoGScaffold\Blocks\TestimonialsBlock;

/**
 * Register the testimonials block
 */
function register_testimonials_block()
{
  register_block_type(
    __DIR__ . '/block.json',
    array(
      'render_callback' => __NAMESPACE__ . '\render_testimonials_block',
    )
  );
}

/**
 * Render the testimonials block
 *
 * @param array $attributes Block attributes.
 * @return string Block HTML.
 */
function render_testimonials_block($attributes)
{
  $testimonials = $attributes['testimonials'] ?? array();
  $layout = $attributes['layout'] ?? 'grid';
  $columns = $attributes['columns'] ?? 2;
  $show_rating = $attributes['showRating'] ?? true;
  $show_image = $attributes['showImage'] ?? true;
  $show_position = $attributes['showPosition'] ?? true;
  $show_company = $attributes['showCompany'] ?? true;
  $quote_style = $attributes['quoteStyle'] ?? 'standard';
  $auto_rotate = $attributes['autoRotate'] ?? false;
  $rotation_speed = $attributes['rotationSpeed'] ?? 5000;

  if (empty($testimonials)) {
    return '';
  }

  // Wrapper attributes
  $wrapper_attributes = get_block_wrapper_attributes(
    array(
      'class' => "is-layout-{$layout} columns-{$columns} quote-style-{$quote_style}",
    )
  );

  ob_start();
  ?>
  <div <?php echo $wrapper_attributes; ?>>
    <?php include __DIR__ . '/markup.php'; ?>
  </div>
  <?php
  return ob_get_clean();
}