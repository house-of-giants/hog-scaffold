/**
 * Footer Component JavaScript
 * Handles dynamic year update and newsletter form functionality
 */

class FooterComponent {
	constructor() {
		this.currentYearElement = document.getElementById("current-year");
		this.newsletterForm = document.querySelector(".newsletter-signup");

		this.init();
	}

	init() {
		this.updateCurrentYear();
		this.setupNewsletterForm();
	}

	/**
	 * Update the current year dynamically
	 */
	updateCurrentYear() {
		if (this.currentYearElement) {
			const currentYear = new Date().getFullYear();
			this.currentYearElement.textContent = currentYear;
		}
	}

	/**
	 * Setup newsletter form handling
	 */
	setupNewsletterForm() {
		if (!this.newsletterForm) {
			return;
		}

		this.newsletterForm.addEventListener("submit", (e) => {
			e.preventDefault();
			this.handleNewsletterSubmission(e);
		});

		// Add real-time email validation
		const emailInput = this.newsletterForm.querySelector('input[type="email"]');
		if (emailInput) {
			emailInput.addEventListener("input", (e) => {
				this.validateEmail(e.target);
			});

			emailInput.addEventListener("blur", (e) => {
				this.validateEmail(e.target);
			});
		}
	}

	/**
	 * Handle newsletter form submission
	 * @param {Event} event - Submit event
	 */
	async handleNewsletterSubmission(event) {
		const form = event.target;
		const emailInput = form.querySelector('input[type="email"]');
		const submitButton = form.querySelector('button[type="submit"]');
		const email = emailInput.value.trim();

		// Validate email
		if (!this.isValidEmail(email)) {
			this.showFormMessage("Please enter a valid email address.", "error");
			emailInput.focus();
			return;
		}

		// Show loading state
		const originalButtonText = submitButton.textContent;
		submitButton.textContent = "Subscribing...";
		submitButton.disabled = true;

		try {
			// Simulate API call (replace with actual newsletter service)
			await this.submitToNewsletterService(email);

			// Success
			this.showFormMessage(
				"Thank you for subscribing! Check your email for confirmation.",
				"success"
			);
			form.reset();
		} catch (error) {
			// Error handling
			this.showFormMessage(
				"Sorry, there was an error. Please try again.",
				"error"
			);
			console.error("Newsletter subscription error:", error);
		} finally {
			// Reset button state
			submitButton.textContent = originalButtonText;
			submitButton.disabled = false;
		}
	}

	/**
	 * Validate email input in real-time
	 * @param {HTMLInputElement} input - Email input element
	 */
	validateEmail(input) {
		const email = input.value.trim();

		// Remove existing validation classes
		input.classList.remove("valid", "invalid");

		if (email && !this.isValidEmail(email)) {
			input.classList.add("invalid");
			input.setAttribute("aria-invalid", "true");
		} else if (email) {
			input.classList.add("valid");
			input.setAttribute("aria-invalid", "false");
		}
	}

	/**
	 * Check if email is valid
	 * @param {string} email - Email address to validate
	 * @returns {boolean} - Whether email is valid
	 */
	isValidEmail(email) {
		const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return emailRegex.test(email);
	}

	/**
	 * Submit email to newsletter service
	 * @param {string} email - Email address
	 * @returns {Promise} - Newsletter service response
	 */
	async submitToNewsletterService(email) {
		// Simulate API delay
		await new Promise((resolve) => setTimeout(resolve, 1000));

		// Replace this with your actual newsletter service integration
		// Examples: Mailchimp, ConvertKit, Sendinblue, etc.

		// For WordPress, you might want to use REST API or AJAX
		const response = await fetch("/wp-json/newsletter/v1/subscribe", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
				"X-WP-Nonce": window.wpApiSettings?.nonce || "",
			},
			body: JSON.stringify({ email }),
		});

		if (!response.ok) {
			throw new Error(`HTTP error! status: ${response.status}`);
		}

		return response.json();
	}

	/**
	 * Show form message to user
	 * @param {string} message - Message to display
	 * @param {string} type - Message type ('success' or 'error')
	 */
	showFormMessage(message, type) {
		// Remove existing message
		const existingMessage = this.newsletterForm.querySelector(".form-message");
		if (existingMessage) {
			existingMessage.remove();
		}

		// Create new message element
		const messageElement = document.createElement("div");
		messageElement.className = `form-message form-message--${type}`;
		messageElement.textContent = message;
		messageElement.setAttribute("role", "alert");
		messageElement.setAttribute("aria-live", "polite");

		// Insert message after the form
		this.newsletterForm.insertAdjacentElement("afterend", messageElement);

		// Auto-remove success messages after 5 seconds
		if (type === "success") {
			setTimeout(() => {
				if (messageElement.parentNode) {
					messageElement.remove();
				}
			}, 5000);
		}
	}

	/**
	 * Setup smooth scrolling for footer links (if needed)
	 */
	setupSmoothScrolling() {
		const footerLinks = this.element.querySelectorAll('a[href^="#"]');

		footerLinks.forEach((link) => {
			link.addEventListener("click", (e) => {
				const targetId = link.getAttribute("href").substring(1);
				const targetElement = document.getElementById(targetId);

				if (targetElement) {
					e.preventDefault();
					targetElement.scrollIntoView({
						behavior: "smooth",
						block: "start",
					});
				}
			});
		});
	}
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
	new FooterComponent();
});

// Also initialize if DOM is already loaded
if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", () => {
		new FooterComponent();
	});
} else {
	new FooterComponent();
}
