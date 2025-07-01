<?php
/**
 * Title: Contact Section
 * Slug: hog-scaffold/contact-section
 * Categories: hog-scaffold-sections, text, featured
 * Description: Contact section with company information, statistics, and team image.
 * Keywords: contact, company, team, statistics, features
 */
?>

<!-- wp:group {"metadata":{"categories":["contact"],"patternName":"hog-scaffold/contact-section","name":"Contact Section"},"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide"
  style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)">
  <!-- wp:group {"layout":{"type":"constrained","contentSize":"800px"}} -->
  <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","fontSize":"x-large"} -->
    <h2 class="wp-block-heading has-text-align-center has-x-large-font-size">Get In Touch</h2>
    <!-- /wp:heading -->

    <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}}} -->
    <p class="has-text-align-center" style="margin-bottom:var(--wp--preset--spacing--60)">Ready to start your next
      project? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
    <!-- /wp:paragraph -->
  </div>
  <!-- /wp:group -->

  <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
  <div class="wp-block-columns"><!-- wp:column {"width":"60%"} -->
    <div class="wp-block-column" style="flex-basis:60%">
      <!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}},"fontSize":"large"} -->
      <h3 class="wp-block-heading has-large-font-size" style="margin-bottom:var(--wp--preset--spacing--40)">
        Send us a message
      </h3>
      <!-- /wp:heading -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"width":"40%"} -->
    <div class="wp-block-column" style="flex-basis:40%">
      <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
      <div class="wp-block-group"><!-- wp:heading {"level":3,"fontSize":"large"} -->
        <h3 class="wp-block-heading has-large-font-size">Contact Information</h3>
        <!-- /wp:heading -->

        <!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"backgroundColor":"base-2","layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group has-base-2-background-color has-background"
          style="border-radius:8px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
          <!-- wp:paragraph {"fontSize":"large"} -->
          <p class="has-large-font-size">📧</p>
          <!-- /wp:paragraph -->

          <!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
          <div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
            <p style="font-weight:600">Email</p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph {"fontSize":"small"} -->
            <p class="has-small-font-size">hello@yourcompany.com</p>
            <!-- /wp:paragraph -->
          </div>
          <!-- /wp:group -->
        </div>
        <!-- /wp:group -->

        <!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"backgroundColor":"base-2","layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group has-base-2-background-color has-background"
          style="border-radius:8px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
          <!-- wp:paragraph {"fontSize":"large"} -->
          <p class="has-large-font-size">📞</p>
          <!-- /wp:paragraph -->

          <!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
          <div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
            <p style="font-weight:600">Phone</p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph {"fontSize":"small"} -->
            <p class="has-small-font-size">+1 (555) 123-4567</p>
            <!-- /wp:paragraph -->
          </div>
          <!-- /wp:group -->
        </div>
        <!-- /wp:group -->

        <!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"backgroundColor":"base-2","layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group has-base-2-background-color has-background"
          style="border-radius:8px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
          <!-- wp:paragraph {"fontSize":"large"} -->
          <p class="has-large-font-size">📍</p>
          <!-- /wp:paragraph -->

          <!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
          <div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
            <p style="font-weight:600">Address</p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph {"fontSize":"small"} -->
            <p class="has-small-font-size">123 Business St, Suite 100<br>City, State 12345</p>
            <!-- /wp:paragraph -->
          </div>
          <!-- /wp:group -->
        </div>
        <!-- /wp:group -->

        <!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"backgroundColor":"base-2","layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group has-base-2-background-color has-background"
          style="border-radius:8px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
          <!-- wp:paragraph {"fontSize":"large"} -->
          <p class="has-large-font-size">🕒</p>
          <!-- /wp:paragraph -->

          <!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
          <div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
            <p style="font-weight:600">Hours</p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph {"fontSize":"small"} -->
            <p class="has-small-font-size">Mon-Fri: 9AM-6PM<br>Sat-Sun: Closed</p>
            <!-- /wp:paragraph -->
          </div>
          <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->
  </div>
  <!-- /wp:columns -->
</div>
<!-- /wp:group -->