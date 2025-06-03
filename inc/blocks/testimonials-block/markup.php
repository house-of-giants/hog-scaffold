<?php
/**
 * Testimonials Block Markup
 *
 * @package HoG Scaffold
 */

// Variables are already sanitized in the render function
?>

<div class="wp-block-testimonials__container" <?php if ($layout === 'carousel' && $auto_rotate): ?>
    data-autorotate="<?php echo esc_attr($auto_rotate ? 'true' : 'false'); ?>"
    data-rotation-speed="<?php echo esc_attr($rotation_speed); ?>" <?php endif; ?>>
  <?php foreach ($testimonials as $index => $testimonial): ?>
    <div class="wp-block-testimonials__testimonial" data-testimonial-index="<?php echo esc_attr($index); ?>">
      <div class="wp-block-testimonials__testimonial-content">
        <?php if ($show_image && !empty($testimonial['image']['url'])): ?>
          <div class="wp-block-testimonials__image">
            <img src="<?php echo esc_url($testimonial['image']['url']); ?>"
              alt="<?php echo esc_attr($testimonial['image']['alt']); ?>" class="wp-block-testimonials__image-photo"
              loading="lazy" />
          </div>
        <?php endif; ?>

        <div class="wp-block-testimonials__content">
          <?php if (!empty($testimonial['quote'])): ?>
            <blockquote class="wp-block-testimonials__quote">
              <?php echo wp_kses_post($testimonial['quote']); ?>
            </blockquote>
          <?php endif; ?>

          <?php if ($show_rating && !empty($testimonial['rating'])): ?>
            <div class="wp-block-testimonials__rating" role="img"
              aria-label="<?php echo esc_attr(sprintf(__('%d out of 5 stars', 'hog-scaffold'), $testimonial['rating'])); ?>">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <span
                  class="wp-block-testimonials__star <?php echo $i <= $testimonial['rating'] ? 'is-filled' : 'is-empty'; ?>"
                  aria-hidden="true">
                  <?php if ($i <= $testimonial['rating']): ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                  <?php else: ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                  <?php endif; ?>
                </span>
              <?php endfor; ?>
            </div>
          <?php endif; ?>

          <div class="wp-block-testimonials__attribution">
            <?php if (!empty($testimonial['author'])): ?>
              <cite class="wp-block-testimonials__author">
                <?php echo esc_html($testimonial['author']); ?>
              </cite>
            <?php endif; ?>

            <?php if ($show_position && !empty($testimonial['position'])): ?>
              <span class="wp-block-testimonials__position">
                <?php echo esc_html($testimonial['position']); ?>
              </span>
            <?php endif; ?>

            <?php if ($show_company && !empty($testimonial['company'])): ?>
              <span class="wp-block-testimonials__company">
                <?php echo esc_html($testimonial['company']); ?>
              </span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <?php if ($layout === 'carousel' && count($testimonials) > 1): ?>
    <div class="wp-block-testimonials__navigation">
      <button class="wp-block-testimonials__nav-button wp-block-testimonials__nav-prev"
        aria-label="<?php esc_attr_e('Previous testimonial', 'hog-scaffold'); ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15,18 9,12 15,6"></polyline>
        </svg>
      </button>
      <button class="wp-block-testimonials__nav-button wp-block-testimonials__nav-next"
        aria-label="<?php esc_attr_e('Next testimonial', 'hog-scaffold'); ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="9,18 15,12 9,6"></polyline>
        </svg>
      </button>

      <div class="wp-block-testimonials__indicators" role="tablist">
        <?php foreach ($testimonials as $index => $testimonial): ?>
          <button class="wp-block-testimonials__indicator <?php echo $index === 0 ? 'is-active' : ''; ?>" role="tab"
            aria-label="<?php echo esc_attr(sprintf(__('Show testimonial %d', 'hog-scaffold'), $index + 1)); ?>"
            data-testimonial="<?php echo esc_attr($index); ?>"></button>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</div>