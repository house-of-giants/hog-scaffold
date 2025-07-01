<?php
/**
 * Title: Hero Section
 * Slug: hog-scaffold/hero
 * Categories: hog-scaffold-sections, header, featured
 * Description: A prominent hero section with star rating, gradient heading, and call-to-action button.
 * Keywords: hero, banner, header, cta, featured, rating, stars
 */
?>
<!-- wp:cover {"dimRatio":0,"overlayColor":"black","isUserOverlayColor":true,"minHeight":100,"minHeightUnit":"vh","contentPosition":"center center","metadata":{"categories":["hog-scaffold-sections","header","featured"],"patternName":"hog-scaffold/hero","name":"Hero Section"},"className":"is-dark","style":{"color":{"background":"#1a202c"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover is-dark has-background" style="background-color:#1a202c;min-height:100vh"><span
    aria-hidden="true"
    class="wp-block-cover__background has-black-background-color has-background-dim-0 has-background-dim"></span>
  <div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"800px"}} -->
    <div class="wp-block-group">
      <!-- wp:group {"className":"hero-rating","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
      <div class="wp-block-group hero-rating" style="margin-bottom:var(--wp--preset--spacing--40)">
        <!-- wp:paragraph {"align":"center","style":{"color":{"text":"#ffffff"},"typography":{"fontSize":"16px","lineHeight":"1.5"}}} -->
        <p class="has-text-align-center has-text-color" style="color:#ffffff;font-size:16px;line-height:1.5">5.0 Stars
        </p>
        <!-- /wp:paragraph -->
      </div>
      <!-- /wp:group -->

      <!-- wp:heading {"textAlign":"center","level":1,"className":"hero-gradient-title","style":{"typography":{"fontSize":"62px","lineHeight":"1.27","letterSpacing":"-1.24px","fontWeight":"500"},"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}}} -->
      <h1 class="wp-block-heading has-text-align-center hero-gradient-title"
        style="margin-bottom:var(--wp--preset--spacing--40);font-size:62px;font-weight:500;letter-spacing:-1.24px;line-height:1.27">
        Speed Up Your Project<br>Managers Workflow by 72%</h1>
      <!-- /wp:heading -->

      <!-- wp:paragraph {"align":"center","style":{"color":{"text":"rgba(255,255,255,0.8)"},"typography":{"fontSize":"20px","lineHeight":"1.75"},"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} -->
      <p class="has-text-align-center has-text-color"
        style="color:rgba(255,255,255,0.8);margin-bottom:var(--wp--preset--spacing--50);font-size:20px;line-height:1.75">
        Super good thing here</p>
      <!-- /wp:paragraph -->

      <!-- wp:buttons {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
      <div class="wp-block-buttons" style="margin-bottom:var(--wp--preset--spacing--60)">
        <!-- wp:button {"className":"hero-cta-button","style":{"border":{"radius":"6px","width":"1px","color":"rgba(255,255,255,0.56)"},"color":{"background":"#0f80d2","text":"#ffffff"},"typography":{"fontSize":"18px","fontWeight":"400","fontStyle":"normal"},"spacing":{"padding":{"left":"32px","right":"32px","top":"16px","bottom":"16px"}},"boxShadow":"0px 1px 2px 0px rgba(13,42,62,0.5), 0px 0px 0px 3px #134367"}} -->
        <div class="wp-block-button hero-cta-button"><a
            class="wp-block-button__link has-text-color has-background has-border-color has-custom-font-size wp-element-button"
            style="border-color:rgba(255,255,255,0.56);border-width:1px;border-radius:6px;color:#ffffff;background-color:#0f80d2;padding-top:16px;padding-right:32px;padding-bottom:16px;padding-left:32px;font-size:18px;font-style:normal;font-weight:400">Create
            Your Account Now</a></div>
        <!-- /wp:button -->
      </div>
      <!-- /wp:buttons -->

      <!-- wp:group {"className":"hero-features","style":{"spacing":{"gap":"48px"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
      <div class="wp-block-group hero-features">
        <!-- wp:group {"className":"feature-item","style":{"spacing":{"gap":"8px"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"flex-start"}} -->
        <div class="wp-block-group feature-item">
          <!-- wp:paragraph {"style":{"color":{"text":"#ffffff"},"typography":{"fontSize":"16px","lineHeight":"1.75"}}} -->
          <p class="has-text-color" style="color:#ffffff;font-size:16px;line-height:1.75">Some smaller</p>
          <!-- /wp:paragraph -->
        </div>
        <!-- /wp:group -->

        <!-- wp:group {"className":"feature-item","style":{"spacing":{"gap":"8px"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"flex-start"}} -->
        <div class="wp-block-group feature-item">
          <!-- wp:paragraph {"style":{"color":{"text":"#ffffff"},"typography":{"fontSize":"16px","lineHeight":"1.75"}}} -->
          <p class="has-text-color" style="color:#ffffff;font-size:16px;line-height:1.75">things here</p>
          <!-- /wp:paragraph -->
        </div>
        <!-- /wp:group -->

        <!-- wp:group {"className":"feature-item","style":{"spacing":{"gap":"8px"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"flex-start"}} -->
        <div class="wp-block-group feature-item">
          <!-- wp:paragraph {"style":{"color":{"text":"#ffffff"},"typography":{"fontSize":"16px","lineHeight":"1.75"}}} -->
          <p class="has-text-color" style="color:#ffffff;font-size:16px;line-height:1.75">Very good.</p>
          <!-- /wp:paragraph -->
        </div>
        <!-- /wp:group -->
      </div>
      <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
  </div>
</div>
<!-- /wp:cover -->