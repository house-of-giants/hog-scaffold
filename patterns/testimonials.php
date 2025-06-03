<?php
/**
 * Title: Testimonials Section
 * Slug: hog-scaffold/testimonials
 * Categories: text, featured
 * Description: Customer testimonials with quotes, names, and company information.
 * Keywords: testimonials, reviews, quotes, customers, feedback
 */
?>

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"backgroundColor":"base-2","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-base-2-background-color has-background"
  style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50)">
  <!-- wp:group {"layout":{"type":"constrained","contentSize":"800px"}} -->
  <div class="wp-block-group">
    <!-- wp:heading {"textAlign":"center","level":2,"fontSize":"x-large"} -->
    <h2 class="wp-block-heading has-text-align-center has-x-large-font-size">What Our Clients Say</h2>
    <!-- /wp:heading -->

    <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}}} -->
    <p class="has-text-align-center" style="margin-bottom:var(--wp--preset--spacing--60)">Don't just take our word for
      it. Here's what our satisfied clients have to say about our work.</p>
    <!-- /wp:paragraph -->
  </div>
  <!-- /wp:group -->

  <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
  <div class="wp-block-columns" style="gap:var(--wp--preset--spacing--50)">
    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:group {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"var:preset|spacing|50","right":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-base-background-color has-background"
        style="border-radius:12px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
        <!-- wp:paragraph {"fontSize":"large","style":{"typography":{"lineHeight":"1.6"}}} -->
        <p class="has-large-font-size" style="line-height:1.6">"Working with this team was an absolute pleasure. They
          delivered exactly what we needed, on time and within budget. The attention to detail was exceptional."</p>
        <!-- /wp:paragraph -->

        <!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--40)">
          <!-- wp:image {"width":"60px","height":"60px","sizeSlug":"thumbnail","linkDestination":"none","style":{"border":{"radius":"50px"}}} -->
          <figure class="wp-block-image size-thumbnail is-resized" style="border-radius:50px">
            <img
              src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&amp;auto=format&amp;fit=crop&amp;w=150&amp;q=80"
              alt="Sarah Johnson" style="border-radius:50px;width:60px;height:60px" />
          </figure>
          <!-- /wp:image -->

          <!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
          <div class="wp-block-group">
            <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
            <p style="font-weight:600">Sarah Johnson</p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph {"fontSize":"small"} -->
            <p class="has-small-font-size">CEO, TechStart Inc.</p>
            <!-- /wp:paragraph -->
          </div>
          <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:group {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"var:preset|spacing|50","right":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-base-background-color has-background"
        style="border-radius:12px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
        <!-- wp:paragraph {"fontSize":"large","style":{"typography":{"lineHeight":"1.6"}}} -->
        <p class="has-large-font-size" style="line-height:1.6">"The team's expertise and professionalism impressed us
          from day one. Our new website has increased conversions by 40% and we couldn't be happier."</p>
        <!-- /wp:paragraph -->

        <!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--40)">
          <!-- wp:image {"width":"60px","height":"60px","sizeSlug":"thumbnail","linkDestination":"none","style":{"border":{"radius":"50px"}}} -->
          <figure class="wp-block-image size-thumbnail is-resized" style="border-radius:50px">
            <img
              src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&amp;auto=format&amp;fit=crop&amp;w=150&amp;q=80"
              alt="Michael Chen" style="border-radius:50px;width:60px;height:60px" />
          </figure>
          <!-- /wp:image -->

          <!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
          <div class="wp-block-group">
            <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
            <p style="font-weight:600">Michael Chen</p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph {"fontSize":"small"} -->
            <p class="has-small-font-size">Marketing Director, GrowthCorp</p>
            <!-- /wp:paragraph -->
          </div>
          <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:column -->

    <!-- wp:column -->
    <div class="wp-block-column">
      <!-- wp:group {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"var:preset|spacing|50","right":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
      <div class="wp-block-group has-base-background-color has-background"
        style="border-radius:12px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
        <!-- wp:paragraph {"fontSize":"large","style":{"typography":{"lineHeight":"1.6"}}} -->
        <p class="has-large-font-size" style="line-height:1.6">"Outstanding communication and results. They understood
          our vision perfectly and brought it to life. I'd recommend them to anyone looking for quality work."</p>
        <!-- /wp:paragraph -->

        <!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--40)">
          <!-- wp:image {"width":"60px","height":"60px","sizeSlug":"thumbnail","linkDestination":"none","style":{"border":{"radius":"50px"}}} -->
          <figure class="wp-block-image size-thumbnail is-resized" style="border-radius:50px">
            <img
              src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&amp;auto=format&amp;fit=crop&amp;w=150&amp;q=80"
              alt="Emily Rodriguez" style="border-radius:50px;width:60px;height:60px" />
          </figure>
          <!-- /wp:image -->

          <!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
          <div class="wp-block-group">
            <!-- wp:paragraph {"style":{"typography":{"fontWeight":"600"}}} -->
            <p style="font-weight:600">Emily Rodriguez</p>
            <!-- /wp:paragraph -->

            <!-- wp:paragraph {"fontSize":"small"} -->
            <p class="has-small-font-size">Founder, Creative Studios</p>
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