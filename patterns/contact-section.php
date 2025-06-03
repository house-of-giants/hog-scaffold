<?php
/**
 * Title: Contact Section
 * Slug: hog-scaffold/contact-section
 * Categories: contact, featured
 * Description: Contact section with form fields, contact information, and call-to-action.
 * Keywords: contact, form, email, phone, address, get in touch
 */
?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"
  style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)">
  <!-- wp:group {"layout":{"type":"constrained","contentSize":"800px"}} -->
  <div class="wp-block-group">
    <!-- wp:heading {"textAlign":"center","level":2,"fontSize":"x-large"} -->
    <h2 class="wp-block-heading has-text-align-center has-x-large-font-size">Get In Touch</h2>
    <!-- /wp:heading -->

    <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}}} -->
    <p class="has-text-align-center" style="margin-bottom:var(--wp--preset--spacing--60)">Ready to start your next
      project? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
    <!-- /wp:paragraph -->
  </div>
  <!-- /wp:group -->

  <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
  <div class="wp-block-columns" style="gap:var(--wp--preset--spacing--60)">
    <!-- wp:column {"width":"60%"} -->
    <div class="wp-block-column" style="flex-basis:60%">
      <!-- wp:group {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"var:preset|spacing|50","right":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"backgroundColor":"base-2","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-base-2-background-color has-background"
        style="border-radius:12px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
        <!-- wp:heading {"level":3,"fontSize":"large","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}}} -->
        <h3 class="wp-block-heading has-large-font-size" style="margin-bottom:var(--wp--preset--spacing--40)">Send us a
          message</h3>
        <!-- /wp:heading -->

        <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
        <div class="wp-block-columns" style="gap:var(--wp--preset--spacing--30)">
          <!-- wp:column -->
          <div class="wp-block-column">
            <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
            <p style="margin-bottom:var(--wp--preset--spacing--20);font-weight:600">First Name</p>
            <!-- /wp:paragraph -->

            <!-- wp:html -->
            <input type="text" name="first_name" placeholder="Your first name"
              style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 16px;"
              required>
            <!-- /wp:html -->
          </div>
          <!-- /wp:column -->

          <!-- wp:column -->
          <div class="wp-block-column">
            <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
            <p style="margin-bottom:var(--wp--preset--spacing--20);font-weight:600">Last Name</p>
            <!-- /wp:paragraph -->

            <!-- wp:html -->
            <input type="text" name="last_name" placeholder="Your last name"
              style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 16px;"
              required>
            <!-- /wp:html -->
          </div>
          <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->

        <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"},"spacing":{"margin":{"bottom":"var:preset|spacing|20","top":"var:preset|spacing|30"}}}} -->
        <p
          style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--20);font-weight:600">
          Email Address</p>
        <!-- /wp:paragraph -->

        <!-- wp:html -->
        <input type="email" name="email" placeholder="your.email@example.com"
          style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 16px;"
          required>
        <!-- /wp:html -->

        <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"},"spacing":{"margin":{"bottom":"var:preset|spacing|20","top":"var:preset|spacing|30"}}}} -->
        <p
          style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--20);font-weight:600">
          Subject</p>
        <!-- /wp:paragraph -->

        <!-- wp:html -->
        <input type="text" name="subject" placeholder="What's this about?"
          style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 16px;"
          required>
        <!-- /wp:html -->

        <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"},"spacing":{"margin":{"bottom":"var:preset|spacing|20","top":"var:preset|spacing|30"}}}} -->
        <p
          style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--20);font-weight:600">
          Message</p>
        <!-- /wp:paragraph -->

        <!-- wp:html -->
        <textarea name="message" rows="5" placeholder="Tell us about your project..."
          style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 16px; resize: vertical;"
          required></textarea>
        <!-- /wp:html -->

        <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} -->
        <div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--40)">
          <!-- wp:button {"width":100} -->
          <div class="wp-block-button has-custom-width wp-block-button__width-100">
            <a class="wp-block-button__link wp-element-button">Send Message</a>
          </div>
          <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column {"width":"40%"} -->
    <div class="wp-block-column" style="flex-basis:40%">
      <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
      <div class="wp-block-group">
        <!-- wp:heading {"level":3,"fontSize":"large"} -->
        <h3 class="wp-block-heading has-large-font-size">Contact Information</h3>
        <!-- /wp:heading -->

        <!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"backgroundColor":"base-2","layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group has-base-2-background-color has-background"
          style="border-radius:8px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
          <!-- wp:paragraph {"fontSize":"large"} -->
          <p class="has-large-font-size">📧</p>
          <!-- /wp:paragraph -->

          <!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
          <div class="wp-block-group">
            <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
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
          <div class="wp-block-group">
            <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
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
          <div class="wp-block-group">
            <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
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
          <div class="wp-block-group">
            <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
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