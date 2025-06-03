<?php
/**
 * Title: About Section
 * Slug: hog-scaffold/about-section
 * Categories: text, featured
 * Description: A compelling about section with image, headline, description, and key features.
 * Keywords: about, company, story, features, image
 */
?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"
  style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)">
  <!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
  <div class="wp-block-columns are-vertically-aligned-center" style="gap:var(--wp--preset--spacing--60)">
    <!-- wp:column {"verticalAlignment":"center"} -->
    <div class="wp-block-column is-vertically-aligned-center">
      <!-- wp:heading {"level":2,"fontSize":"x-large"} -->
      <h2 class="wp-block-heading has-x-large-font-size">About Our Company</h2>
      <!-- /wp:heading -->

      <!-- wp:paragraph {"fontSize":"large","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} -->
      <p class="has-large-font-size" style="margin-bottom:var(--wp--preset--spacing--50)">We're passionate about
        creating digital experiences that make a difference. Our team combines creativity with technical expertise to
        deliver solutions that exceed expectations.</p>
      <!-- /wp:paragraph -->

      <!-- wp:list {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} -->
      <ul style="margin-bottom:var(--wp--preset--spacing--50)">
        <!-- wp:list-item -->
        <li><strong>10+ years</strong> of industry experience</li>
        <!-- /wp:list-item -->

        <!-- wp:list-item -->
        <li><strong>500+ projects</strong> completed successfully</li>
        <!-- /wp:list-item -->

        <!-- wp:list-item -->
        <li><strong>99% client</strong> satisfaction rate</li>
        <!-- /wp:list-item -->

        <!-- wp:list-item -->
        <li><strong>24/7 support</strong> for all our clients</li>
        <!-- /wp:list-item -->
      </ul>
      <!-- /wp:list -->

      <!-- wp:buttons -->
      <div class="wp-block-buttons">
        <!-- wp:button -->
        <div class="wp-block-button">
          <a class="wp-block-button__link wp-element-button">Learn More About Us</a>
        </div>
        <!-- /wp:button -->
      </div>
      <!-- /wp:buttons -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"center"} -->
    <div class="wp-block-column is-vertically-aligned-center">
      <!-- wp:image {"sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"12px"}}} -->
      <figure class="wp-block-image size-large" style="border-radius:12px">
        <img
          src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&amp;auto=format&amp;fit=crop&amp;w=2070&amp;q=80"
          alt="Team collaboration" style="border-radius:12px" />
      </figure>
      <!-- /wp:image -->
    </div>
    <!-- /wp:column -->
  </div>
  <!-- /wp:columns -->
</div>
<!-- /wp:group -->