/**
 * Accessibility Enhancement Module
 * Handles keyboard navigation detection, focus management, and ARIA support
 *
 * @package HoGScaffold
 */

/* global MutationObserver, Node */

class AccessibilityEnhancer {
	constructor() {
		this.isKeyboardUser = false;
		this.isUsingMouse = false;
		this.reducedMotion = window.matchMedia(
			"(prefers-reduced-motion: reduce)"
		).matches;

		this.init();
	}

	/**
	 * Initialize accessibility enhancements
	 */
	init() {
		this.detectKeyboardNavigation();
		this.handleReducedMotion();
		this.enhanceSkipLinks();
		this.manageFocusTrapping();
		this.improveFormAccessibility();
		this.announcePageChanges();
		this.enhanceButtonAccessibility();
		this.addAriaLabels();
	}

	/**
	 * Detect if user is navigating with keyboard
	 */
	detectKeyboardNavigation() {
		const { body } = document;

		// Listen for first Tab key press
		document.addEventListener("keydown", (e) => {
			const { key } = e;
			if (key === "Tab") {
				this.isKeyboardUser = true;
				body.classList.add("keyboard-navigation");
				body.classList.remove("mouse-navigation");
			}
		});

		// Listen for mouse interaction
		document.addEventListener("mousedown", () => {
			this.isUsingMouse = true;
			body.classList.add("mouse-navigation");
			body.classList.remove("keyboard-navigation");
			this.isKeyboardUser = false;
		});

		// Listen for touch interaction
		document.addEventListener("touchstart", () => {
			body.classList.add("touch-navigation");
			body.classList.remove("keyboard-navigation", "mouse-navigation");
			this.isKeyboardUser = false;
		});
	}

	/**
	 * Handle reduced motion preferences
	 */
	handleReducedMotion() {
		if (this.reducedMotion) {
			document.body.classList.add("no-motion");
		}

		// Listen for changes in motion preference
		const motionMediaQuery = window.matchMedia(
			"(prefers-reduced-motion: reduce)"
		);
		motionMediaQuery.addEventListener("change", (e) => {
			if (e.matches) {
				document.body.classList.add("no-motion");
			} else {
				document.body.classList.remove("no-motion");
			}
		});
	}

	/**
	 * Enhance skip links functionality
	 */
	enhanceSkipLinks() {
		const skipLinks = document.querySelectorAll(
			".skip-link, .screen-reader-text"
		);

		skipLinks.forEach((link) => {
			link.addEventListener("click", (e) => {
				const target = document.querySelector(link.getAttribute("href"));
				if (target) {
					e.preventDefault();
					target.focus();
					target.scrollIntoView({ behavior: "smooth", block: "start" });

					// Announce to screen readers
					this.announceToScreenReader(
						`Skipped to ${target.textContent || "main content"}`
					);
				}
			});
		});
	}

	/**
	 * Manage focus trapping for modals and dialogs
	 */
	manageFocusTrapping() {
		const modals = document.querySelectorAll('[role="dialog"], .modal');

		modals.forEach((modal) => {
			const focusableElements = modal.querySelectorAll(
				'a[href], button, textarea, input, select, [tabindex]:not([tabindex="-1"])'
			);

			if (focusableElements.length === 0) return;

			const firstElement = focusableElements[0];
			const lastElement = focusableElements[focusableElements.length - 1];

			modal.addEventListener("keydown", (e) => {
				const { key, shiftKey } = e;
				if (key === "Tab") {
					if (shiftKey) {
						if (document.activeElement === firstElement) {
							lastElement.focus();
							e.preventDefault();
						}
					} else {
						if (document.activeElement === lastElement) {
							firstElement.focus();
							e.preventDefault();
						}
					}
				}

				if (key === "Escape") {
					this.closeModal(modal);
				}
			});
		});
	}

	/**
	 * Close modal and return focus
	 */
	closeModal(modal) {
		const trigger = document.querySelector(`[aria-controls="${modal.id}"]`);
		modal.setAttribute("aria-hidden", "true");
		modal.style.display = "none";

		if (trigger) {
			trigger.focus();
		}

		this.announceToScreenReader("Dialog closed");
	}

	/**
	 * Improve form accessibility
	 */
	improveFormAccessibility() {
		const forms = document.querySelectorAll("form");

		forms.forEach((form) => {
			// Add required attributes and labels
			const inputs = form.querySelectorAll("input, select, textarea");

			inputs.forEach((input) => {
				// Add aria-required for required fields
				if (input.hasAttribute("required")) {
					input.setAttribute("aria-required", "true");

					// Add visual indicator if not already present
					const label = form.querySelector(`label[for="${input.id}"]`);
					if (label && !label.classList.contains("required")) {
						label.classList.add("required");
					}
				}

				// Handle form validation
				input.addEventListener("invalid", (e) => {
					this.handleFormError(input, e.target.validationMessage);
				});

				input.addEventListener("blur", () => {
					this.validateField(input);
				});
			});

			// Handle form submission
			form.addEventListener("submit", (e) => {
				const isValid = this.validateForm(form);
				if (!isValid) {
					e.preventDefault();
					this.focusFirstError(form);
				}
			});
		});
	}

	/**
	 * Handle form field errors
	 */
	handleFormError(field, message) {
		const wrapper =
			field.closest(".form-field, .wp-block-group") || field.parentElement;
		const existingError = wrapper.querySelector(".error-message");

		// Remove existing error
		if (existingError) {
			existingError.remove();
		}

		// Add error class
		wrapper.classList.add("has-error");
		field.setAttribute("aria-invalid", "true");

		// Create error message
		const errorElement = document.createElement("span");
		errorElement.className = "error-message";
		errorElement.textContent = message;
		errorElement.id = `${field.id || field.name}-error`;

		// Associate error with field
		field.setAttribute("aria-describedby", errorElement.id);

		// Insert error message
		wrapper.appendChild(errorElement);

		// Announce error to screen readers
		this.announceToScreenReader(`Error: ${message}`);
	}

	/**
	 * Validate individual form field
	 */
	validateField(field) {
		const wrapper =
			field.closest(".form-field, .wp-block-group") || field.parentElement;
		const errorMessage = wrapper.querySelector(".error-message");

		if (field.validity.valid) {
			wrapper.classList.remove("has-error");
			field.removeAttribute("aria-invalid");
			field.removeAttribute("aria-describedby");

			if (errorMessage) {
				errorMessage.remove();
			}
		}
	}

	/**
	 * Validate entire form
	 */
	validateForm(form) {
		const fields = form.querySelectorAll("input, select, textarea");
		let isValid = true;

		fields.forEach((field) => {
			if (!field.validity.valid) {
				this.handleFormError(field, field.validationMessage);
				isValid = false;
			}
		});

		return isValid;
	}

	/**
	 * Focus first error in form
	 */
	focusFirstError(form) {
		const firstError = form.querySelector(
			".has-error input, .has-error select, .has-error textarea"
		);
		if (firstError) {
			firstError.focus();
			firstError.scrollIntoView({ behavior: "smooth", block: "center" });
		}
	}

	/**
	 * Announce page changes to screen readers
	 */
	announcePageChanges() {
		// Create live region for announcements
		const liveRegion = document.createElement("div");
		liveRegion.setAttribute("aria-live", "polite");
		liveRegion.setAttribute("aria-atomic", "true");
		liveRegion.className = "sr-only";
		liveRegion.id = "accessibility-announcements";
		document.body.appendChild(liveRegion);

		// Monitor for dynamic content changes
		const observer = new MutationObserver((mutations) => {
			mutations.forEach((mutation) => {
				if (mutation.type === "childList" && mutation.addedNodes.length > 0) {
					// Check if significant content was added
					const addedContent = Array.from(mutation.addedNodes)
						.filter((node) => node.nodeType === Node.ELEMENT_NODE)
						.find((node) =>
							node.matches('main *, [role="main"] *, .content *')
						);

					if (addedContent) {
						this.announceToScreenReader("Page content updated");
					}
				}
			});
		});

		const main = document.querySelector('main, [role="main"]');
		if (main) {
			observer.observe(main, { childList: true, subtree: true });
		}
	}

	/**
	 * Enhance button accessibility
	 */
	enhanceButtonAccessibility() {
		const buttons = document.querySelectorAll(
			'button, .wp-block-button__link, [role="button"]'
		);

		buttons.forEach((button) => {
			// Add keyboard support for elements with button role
			if (
				button.getAttribute("role") === "button" &&
				button.tagName !== "BUTTON"
			) {
				button.addEventListener("keydown", (e) => {
					const { key } = e;
					if (key === "Enter" || key === " ") {
						e.preventDefault();
						button.click();
					}
				});

				// Ensure it's focusable
				if (!button.hasAttribute("tabindex")) {
					button.setAttribute("tabindex", "0");
				}
			}

			// Add aria-expanded for toggle buttons
			if (button.hasAttribute("aria-controls")) {
				const target = document.getElementById(
					button.getAttribute("aria-controls")
				);
				if (target) {
					const isExpanded = !target.hidden && target.style.display !== "none";
					button.setAttribute("aria-expanded", isExpanded);

					button.addEventListener("click", () => {
						const newState =
							button.getAttribute("aria-expanded") === "true"
								? "false"
								: "true";
						button.setAttribute("aria-expanded", newState);
					});
				}
			}
		});
	}

	/**
	 * Add missing ARIA labels
	 */
	addAriaLabels() {
		// Add labels to navigation
		const nav = document.querySelector(".wp-block-navigation, nav");
		if (nav && !nav.getAttribute("aria-label")) {
			nav.setAttribute("aria-label", "Main navigation");
		}

		// Add labels to search forms
		const searchForms = document.querySelectorAll(".wp-block-search");
		searchForms.forEach((form, index) => {
			if (!form.getAttribute("aria-label")) {
				form.setAttribute("aria-label", `Search ${index > 0 ? index + 1 : ""}`);
			}
		});

		// Add labels to social links
		const socialLinks = document.querySelectorAll(".wp-block-social-links");
		socialLinks.forEach((links) => {
			if (!links.getAttribute("aria-label")) {
				links.setAttribute("aria-label", "Social media links");
			}
		});

		// Enhance images without alt text
		const images = document.querySelectorAll("img:not([alt])");
		images.forEach((img) => {
			// If decorative, add empty alt
			if (img.closest(".wp-block-cover, .decoration")) {
				img.setAttribute("alt", "");
			} else {
				// Log missing alt text for content images
				console.warn("Image missing alt text:", img.src);
			}
		});
	}

	/**
	 * Announce message to screen readers
	 */
	announceToScreenReader(message) {
		const liveRegion = document.getElementById("accessibility-announcements");
		if (liveRegion) {
			liveRegion.textContent = message;

			// Clear after announcement
			setTimeout(() => {
				liveRegion.textContent = "";
			}, 1000);
		}
	}

	/**
	 * Add text zoom support
	 */
	handleTextZoom() {
		const root = document.documentElement;
		let currentZoom = 1;

		document.addEventListener("keydown", (e) => {
			const { key, ctrlKey, metaKey } = e;
			if ((ctrlKey || metaKey) && (key === "+" || key === "=")) {
				e.preventDefault();
				currentZoom = Math.min(currentZoom + 0.1, 2);
				root.style.fontSize = `${currentZoom}rem`;
				this.announceToScreenReader(
					`Text size increased to ${Math.round(currentZoom * 100)}%`
				);
			}

			if ((ctrlKey || metaKey) && key === "-") {
				e.preventDefault();
				currentZoom = Math.max(currentZoom - 0.1, 0.8);
				root.style.fontSize = `${currentZoom}rem`;
				this.announceToScreenReader(
					`Text size decreased to ${Math.round(currentZoom * 100)}%`
				);
			}

			if ((ctrlKey || metaKey) && key === "0") {
				e.preventDefault();
				currentZoom = 1;
				root.style.fontSize = "";
				this.announceToScreenReader("Text size reset to normal");
			}
		});
	}
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
	new AccessibilityEnhancer();
});

// Also initialize if script loads after DOM ready
if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", () => {
		new AccessibilityEnhancer();
	});
} else {
	new AccessibilityEnhancer();
}
