/**
 * Security utilities for form handling and AJAX requests
 *
 * @package HoGScaffold
 */

(function () {
	'use strict';

	// Security utilities object
	window.HoGScaffoldSecurity = {
		/**
		 * Initialize security features
		 */
		init: function () {
			this.bindFormEvents();
			this.setupAjaxSecurity();
			this.validateOnSubmit();
		},

		/**
		 * Get nonce for specific action
		 *
		 * @param {string} action - The nonce action
		 * @returns {string} The nonce value
		 */
		getNonce: function (action) {
			const nonces = window.hogScaffoldNonces || {};
			return nonces[action] || '';
		},

		/**
		 * Setup secure AJAX defaults
		 */
		setupAjaxSecurity: function () {
			// Note: Since we're using fetch API, we'll handle nonce injection per request
			// rather than globally like $.ajaxSetup()
		},

		/**
		 * Bind security events to forms
		 */
		bindFormEvents: function () {
			// Contact form security - use event delegation
			document.addEventListener('submit', (e) => {
				if (e.target.matches('.hog-scaffold-contact-form')) {
					this.handleContactForm(e);
				}
			});

			// File upload security - use event delegation
			document.addEventListener('change', (e) => {
				if (e.target.matches('input[type="file"]')) {
					this.validateFileUpload(e);
				}
			});

			// Prevent double submission - use event delegation
			document.addEventListener('submit', (e) => {
				if (e.target.matches('form')) {
					this.preventDoubleSubmission(e);
				}
			});
		},

		/**
		 * Handle contact form submission with security
		 *
		 * @param {Event} e - Form submit event
		 */
		handleContactForm: function (e) {
			e.preventDefault();

			const form = e.target;
			const submitBtn = form.querySelector('button[type="submit"]');
			const originalText = submitBtn.textContent;

			// Disable submit button
			submitBtn.disabled = true;
			submitBtn.textContent = 'Sending...';

			// Clear previous errors
			const errorMessages = form.querySelectorAll('.error-message');
			errorMessages.forEach((el) => el.remove());

			const fieldErrors = form.querySelectorAll('.field-error');
			fieldErrors.forEach((el) => el.classList.remove('field-error'));

			// Get form data
			const formData = {
				action: 'hog_scaffold_contact_form',
				name: form.querySelector('[name="name"]')?.value || '',
				email: form.querySelector('[name="email"]')?.value || '',
				phone: form.querySelector('[name="phone"]')?.value || '',
				message: form.querySelector('[name="message"]')?.value || '',
				contact_nonce: this.getNonce('contact_form'),
			};

			// Validate form data
			const validation = this.validateContactForm(formData);
			if (!validation.valid) {
				this.displayFormErrors(form, validation.errors);
				submitBtn.disabled = false;
				submitBtn.textContent = originalText;
				return;
			}

			// Convert form data to URLSearchParams for submission
			const params = new URLSearchParams(formData);

			// Submit form via fetch API
			fetch(window.hogScaffoldAjax.ajaxurl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: params,
			})
				.then((response) => {
					if (!response.ok) {
						throw new Error(`HTTP error! status: ${response.status}`);
					}
					return response.json();
				})
				.then((data) => {
					if (data.success) {
						this.showSuccessMessage(form, data.data.message);
						form.reset();
					} else {
						this.displayFormErrors(form, data.data.errors || {});
					}
				})
				.catch((error) => {
					console.error('AJAX Error:', error);
					this.handleSecurityError('An error occurred. Please try again.');
				})
				.finally(() => {
					submitBtn.disabled = false;
					submitBtn.textContent = originalText;
				});
		},

		/**
		 * Validate contact form data client-side
		 *
		 * @param {Object} data - Form data
		 * @returns {Object} Validation result
		 */
		validateContactForm: function (data) {
			const errors = {};

			// Name validation
			if (!data.name || data.name.trim().length < 2) {
				errors.name = 'Name must be at least 2 characters';
			} else if (data.name.length > 100) {
				errors.name = 'Name must not exceed 100 characters';
			}

			// Email validation
			if (!data.email || !this.isValidEmail(data.email)) {
				errors.email = 'Please enter a valid email address';
			}

			// Phone validation (optional)
			if (data.phone && !this.isValidPhone(data.phone)) {
				errors.phone = 'Please enter a valid phone number';
			}

			// Message validation
			if (!data.message || data.message.trim().length < 10) {
				errors.message = 'Message must be at least 10 characters';
			} else if (data.message.length > 2000) {
				errors.message = 'Message must not exceed 2000 characters';
			}

			return {
				valid: Object.keys(errors).length === 0,
				errors: errors,
			};
		},

		/**
		 * Validate email format
		 *
		 * @param {string} email - Email to validate
		 * @returns {boolean} Is valid email
		 */
		isValidEmail: function (email) {
			const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			return emailRegex.test(email);
		},

		/**
		 * Validate phone number format
		 *
		 * @param {string} phone - Phone to validate
		 * @returns {boolean} Is valid phone
		 */
		isValidPhone: function (phone) {
			const phoneRegex = /^[+]?[0-9\s\-()]{10,}$/;
			return phoneRegex.test(phone);
		},

		/**
		 * Validate file uploads
		 *
		 * @param {Event} e - File input change event
		 */
		validateFileUpload: function (e) {
			const file = e.target.files[0];
			const input = e.target;
			const maxSize = 2 * 1024 * 1024; // 2MB
			const allowedTypes = [
				'image/jpeg',
				'image/png',
				'image/gif',
				'image/webp',
				'application/pdf',
			];

			// Clear previous errors
			const existingErrors = input.parentNode.querySelectorAll('.file-error');
			existingErrors.forEach((el) => el.remove());

			if (!file) return;

			// Check file size
			if (file.size > maxSize) {
				this.showFileError(input, 'File size must not exceed 2MB');
				input.value = '';
				return;
			}

			// Check file type
			if (!allowedTypes.includes(file.type)) {
				this.showFileError(
					input,
					'File type not allowed. Please upload images or PDF files only.',
				);
				input.value = '';
				return;
			}
		},

		/**
		 * Show file upload error
		 *
		 * @param {HTMLElement} input - File input element
		 * @param {string} message - Error message
		 */
		showFileError: function (input, message) {
			const errorDiv = document.createElement('div');
			errorDiv.className = 'file-error error-message';
			errorDiv.textContent = message;
			input.insertAdjacentElement('afterend', errorDiv);
		},

		/**
		 * Display form validation errors
		 *
		 * @param {HTMLElement} form - Form element
		 * @param {Object} errors - Validation errors
		 */
		displayFormErrors: function (form, errors) {
			Object.entries(errors).forEach(([field, message]) => {
				const fieldElement = form.querySelector(`[name="${field}"]`);
				if (fieldElement) {
					fieldElement.classList.add('field-error');

					const errorDiv = document.createElement('div');
					errorDiv.className = 'error-message';
					errorDiv.textContent = message;
					fieldElement.insertAdjacentElement('afterend', errorDiv);
				}
			});
		},

		/**
		 * Show success message
		 *
		 * @param {HTMLElement} form - Form element
		 * @param {string} message - Success message
		 */
		showSuccessMessage: function (form, message) {
			const successDiv = document.createElement('div');
			successDiv.className = 'success-message';
			successDiv.textContent = message;
			form.insertAdjacentElement('beforebegin', successDiv);

			// Fade out after 5 seconds using CSS transition
			setTimeout(() => {
				successDiv.style.transition = 'opacity 0.5s ease-out';
				successDiv.style.opacity = '0';

				// Remove element after transition completes
				setTimeout(() => {
					if (successDiv.parentNode) {
						successDiv.remove();
					}
				}, 500);
			}, 5000);
		},

		/**
		 * Handle security errors
		 *
		 * @param {string} message - Error message
		 */
		handleSecurityError: function (message) {
			alert(message); // In production, use a more user-friendly notification
			console.warn('Security error:', message);
		},

		/**
		 * Prevent double form submission
		 *
		 * @param {Event} e - Form submit event
		 */
		preventDoubleSubmission: function (e) {
			const form = e.target;
			const isSubmitted = form.dataset.submitted === 'true';

			if (isSubmitted) {
				e.preventDefault();
				return false;
			}

			form.dataset.submitted = 'true';

			// Reset after a delay to allow legitimate resubmissions
			setTimeout(() => {
				form.dataset.submitted = 'false';
			}, 3000);
		},

		/**
		 * Validate form inputs on submit
		 */
		validateOnSubmit: function () {
			document.addEventListener('submit', (e) => {
				if (!e.target.matches('form')) return;

				const form = e.target;
				let isValid = true;

				// Check required fields
				const requiredFields = form.querySelectorAll('[required]');
				requiredFields.forEach((field) => {
					if (!field.value.trim()) {
						field.classList.add('field-error');
						isValid = false;
					} else {
						field.classList.remove('field-error');
					}
				});

				if (!isValid) {
					e.preventDefault();
					this.handleSecurityError('Please fill in all required fields.');
				}
			});
		},

		/**
		 * Sanitize user input for XSS prevention
		 *
		 * @param {string} input - User input
		 * @returns {string} Sanitized input
		 */
		sanitizeInput: function (input) {
			const div = document.createElement('div');
			div.textContent = input;
			return div.innerHTML;
		},

		/**
		 * Rate limiting check (client-side)
		 *
		 * @param {string} action - Action being performed
		 * @returns {boolean} Whether action is allowed
		 */
		checkRateLimit: function (action) {
			const now = Date.now();
			const key = 'hog_scaffold_rate_' + action;
			const attempts = JSON.parse(localStorage.getItem(key) || '[]');

			// Remove attempts older than 1 hour
			const validAttempts = attempts.filter((time) => now - time < 3600000);

			if (validAttempts.length >= 5) {
				return false;
			}

			validAttempts.push(now);
			localStorage.setItem(key, JSON.stringify(validAttempts));
			return true;
		},
	};

	// Initialize when DOM content is loaded
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', () => {
			window.HoGScaffoldSecurity.init();
		});
	} else {
		window.HoGScaffoldSecurity.init();
	}
})();
