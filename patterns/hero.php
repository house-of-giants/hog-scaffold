<?php
/**
 * Title: Hero Section
 * Slug: hog-scaffold/hero
 * Categories: header, featured
 * Description: A prominent hero section with heading, description, and call-to-action buttons.
 * Keywords: hero, banner, header, cta, featured
 */
?>

<!-- wp:cover {"url":"","id":0,"dimRatio":30,"overlayColor":"black","minHeight":70,"minHeightUnit":"vh","contentPosition":"center center","isDark":true,"layout":{"type":"constrained"}} -->
<div class="wp-block-cover is-dark" style="min-height:70vh">
	<span aria-hidden="true"
	class="wp-block-cover__background has-black-background-color has-background-dim-30 has-background-dim"></span>
	<div class="wp-block-cover__inner-container">
	<!-- wp:group {"layout":{"type":"constrained","contentSize":"800px"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"textAlign":"center","level":1,"fontSize":"xx-large","className":"hero-title"} -->
		<h1 class="wp-block-heading has-text-align-center has-xx-large-font-size hero-title">Build Something Amazing</h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","fontSize":"large","className":"hero-description"} -->
		<p class="has-text-align-center has-large-font-size hero-description">Create powerful websites with our modern
		WordPress block theme. Designed for developers and content creators who demand performance and flexibility.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--50)">
		<!-- wp:button {"className":"is-style-fill"} -->
		<div class="wp-block-button is-style-fill">
			<a class="wp-block-button__link wp-element-button">Get Started</a>
		</div>
		<!-- /wp:button -->

		<!-- wp:button {"className":"is-style-outline"} -->
		<div class="wp-block-button is-style-outline">
			<a class="wp-block-button__link wp-element-button">Learn More</a>
		</div>
		<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
	</div>
</div>
<!-- /wp:cover -->