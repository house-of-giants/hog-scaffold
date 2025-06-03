/**
 * Testimonials Carousel Enhanced Accessibility
 * Provides keyboard navigation and focus management for testimonials carousel
 */

/* global setInterval, clearInterval */

document.addEventListener("DOMContentLoaded", () => {
	const carousels = document.querySelectorAll(
		".wp-block-testimonials.is-layout-carousel"
	);

	carousels.forEach((carousel) => {
		initCarousel(carousel);
	});
});

/**
 * Initialize carousel with accessibility features
 * @param {Element} carousel - The carousel container
 */
function initCarousel(carousel) {
	const testimonials = carousel.querySelectorAll(
		".wp-block-testimonials__testimonial"
	);
	const prevButton = carousel.querySelector(".wp-block-testimonials__nav-prev");
	const nextButton = carousel.querySelector(".wp-block-testimonials__nav-next");
	const indicators = carousel.querySelectorAll(
		".wp-block-testimonials__indicator"
	);

	let currentIndex = 0;
	let autoRotateTimer = null;

	// Check if auto-rotation is enabled
	const autoRotate =
		carousel.hasAttribute("data-autorotate") &&
		carousel.getAttribute("data-autorotate") === "true";
	const rotationSpeed =
		parseInt(carousel.getAttribute("data-rotation-speed"), 10) || 5000;

	/**
	 * Show testimonial at specific index
	 * @param {number} index - Index of testimonial to show
	 * @param {boolean} focus - Whether to focus the testimonial
	 */
	function showTestimonial(index, focus = false) {
		// Remove active class from all testimonials and indicators
		testimonials.forEach((testimonial, i) => {
			testimonial.classList.toggle("is-active", i === index);
			if (indicators[i]) {
				indicators[i].classList.toggle("is-active", i === index);
				indicators[i].setAttribute(
					"aria-selected",
					i === index ? "true" : "false"
				);
			}
		});

		currentIndex = index;

		// Update ARIA live region for screen readers
		announceTestimonial(index);

		// Focus management
		if (focus && testimonials[index]) {
			// Make testimonial focusable temporarily
			testimonials[index].setAttribute("tabindex", "-1");
			testimonials[index].focus();

			// Remove tabindex after focus
			setTimeout(() => {
				testimonials[index].removeAttribute("tabindex");
			}, 100);
		}

		// Reset auto-rotation timer
		if (autoRotate) {
			resetAutoRotation();
		}
	}

	/**
	 * Announce testimonial change to screen readers
	 * @param {number} index - Index of current testimonial
	 */
	function announceTestimonial(index) {
		let liveRegion = carousel.querySelector(
			".wp-block-testimonials__live-region"
		);
		if (!liveRegion) {
			liveRegion = document.createElement("div");
			liveRegion.className = "wp-block-testimonials__live-region";
			liveRegion.setAttribute("aria-live", "polite");
			liveRegion.setAttribute("aria-atomic", "true");
			liveRegion.style.cssText =
				"position: absolute; left: -10000px; width: 1px; height: 1px; overflow: hidden;";
			carousel.appendChild(liveRegion);
		}

		const testimonial = testimonials[index];
		if (testimonial) {
			const quote = testimonial.querySelector(".wp-block-testimonials__quote");
			const author = testimonial.querySelector(
				".wp-block-testimonials__author"
			);

			const announcement = `Testimonial ${index + 1} of ${testimonials.length}. ${
				quote ? quote.textContent : ""
			} ${author ? `From ${author.textContent}` : ""}`;

			liveRegion.textContent = announcement;
		}
	}

	/**
	 * Navigate to previous testimonial
	 */
	function goToPrevious() {
		const newIndex =
			currentIndex > 0 ? currentIndex - 1 : testimonials.length - 1;
		showTestimonial(newIndex, true);
	}

	/**
	 * Navigate to next testimonial
	 */
	function goToNext() {
		const newIndex =
			currentIndex < testimonials.length - 1 ? currentIndex + 1 : 0;
		showTestimonial(newIndex, true);
	}

	/**
	 * Start auto-rotation
	 */
	function startAutoRotation() {
		if (autoRotate && testimonials.length > 1) {
			autoRotateTimer = setInterval(() => {
				const newIndex =
					currentIndex < testimonials.length - 1 ? currentIndex + 1 : 0;
				showTestimonial(newIndex);
			}, rotationSpeed);
		}
	}

	/**
	 * Reset auto-rotation timer
	 */
	function resetAutoRotation() {
		if (autoRotateTimer) {
			clearInterval(autoRotateTimer);
		}
		startAutoRotation();
	}

	/**
	 * Stop auto-rotation
	 */
	function stopAutoRotation() {
		if (autoRotateTimer) {
			clearInterval(autoRotateTimer);
			autoRotateTimer = null;
		}
	}

	// Event Listeners

	// Navigation buttons
	if (prevButton) {
		prevButton.addEventListener("click", goToPrevious);
	}

	if (nextButton) {
		nextButton.addEventListener("click", goToNext);
	}

	// Indicators
	indicators.forEach((indicator, index) => {
		indicator.addEventListener("click", () => {
			showTestimonial(index, true);
		});

		// Set initial ARIA attributes
		indicator.setAttribute(
			"aria-selected",
			index === currentIndex ? "true" : "false"
		);
	});

	// Keyboard navigation
	carousel.addEventListener("keydown", (e) => {
		switch (e.key) {
			case "ArrowLeft":
				e.preventDefault();
				goToPrevious();
				break;
			case "ArrowRight":
				e.preventDefault();
				goToNext();
				break;
			case "Home":
				e.preventDefault();
				showTestimonial(0, true);
				break;
			case "End":
				e.preventDefault();
				showTestimonial(testimonials.length - 1, true);
				break;
		}
	});

	// Pause auto-rotation on hover/focus
	if (autoRotate) {
		carousel.addEventListener("mouseenter", stopAutoRotation);
		carousel.addEventListener("mouseleave", startAutoRotation);
		carousel.addEventListener("focusin", stopAutoRotation);
		carousel.addEventListener("focusout", (e) => {
			// Only restart if focus is completely leaving the carousel
			if (!carousel.contains(e.relatedTarget)) {
				startAutoRotation();
			}
		});
	}

	// Handle reduced motion preference
	if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
		// Disable auto-rotation for users who prefer reduced motion
		return;
	}

	// Initialize
	showTestimonial(0);
	if (autoRotate) {
		startAutoRotation();
	}
}
