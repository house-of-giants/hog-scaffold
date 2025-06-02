/**
 * Security utilities for form handling and AJAX requests
 *
 * @package HoGScaffold
 */

(function ($) {
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
			// Set AJAX defaults for WordPress
			$.ajaxSetup({
				beforeSend: function (xhr, settings) {
					// Add nonce to all AJAX requests
					if (settings.data && typeof settings.data === 'string') {
						if (settings.data.indexOf('action=') !== -1) {
							const action = settings.data.match(/action=([^&]*)/);
							if (action && action[1]) {
								const nonce = HoGScaffoldSecurity.getNonce(action[1]);
								if (nonce) {
									settings.data += '&_wpnonce=' + nonce;
								}
							}
						}
					}
				},
				error: function (xhr, status, error) {
					if (xhr.status === 403) {
						HoGScaffoldSecurity.handleSecurityError(
							'Security verification failed. Please refresh the page and try again.',
						);
					}
				},
			});
		},

		/**
		 * Bind security events to forms
		 */
		bindFormEvents: function () {
			// Contact form security
			$(document).on(
				'submit',
				'.hog-scaffold-contact-form',
				this.handleContactForm,
			);

			// File upload security
			$(document).on('change', 'input[type="file"]', this.validateFileUpload);

			// Prevent double submission
			$(document).on('submit', 'form', this.preventDoubleSubmission);
		},

		/**
		 * Handle contact form submission with security
		 *
		 * @param {Event} e - Form submit event
		 */
		handleContactForm: function (e) {
			e.preventDefault();

			const $form = $(this);
			const $submitBtn = $form.find('button[type="submit"]');
			const originalText = $submitBtn.text();

			// Disable submit button
			$submitBtn.prop('disabled', true).text('Sending...');

			// Clear previous errors
			$form.find('.error-message').remove();
			$form.find('.field-error').removeClass('field-error');

			// Get form data
			const formData = {
				action: 'hog_scaffold_contact_form',
				name: $form.find('[name="name"]').val(),
				email: $form.find('[name="email"]').val(),
				phone: $form.find('[name="phone"]').val(),
				message: $form.find('[name="message"]').val(),
				contact_nonce: HoGScaffoldSecurity.getNonce('contact_form'),
			};

			// Validate form data
			const validation = HoGScaffoldSecurity.validateContactForm(formData);
			if (!validation.valid) {
				HoGScaffoldSecurity.displayFormErrors($form, validation.errors);
				$submitBtn.prop('disabled', false).text(originalText);
				return;
			}

			// Submit form via AJAX
			$.ajax({
				url: window.hogScaffoldAjax.ajaxurl,
				type: 'POST',
				data: formData,
				success: function (response) {
					if (response.success) {
						HoGScaffoldSecurity.showSuccessMessage(
							$form,
							response.data.message,
						);
						$form[0].reset();
					} else {
						HoGScaffoldSecurity.displayFormErrors(
							$form,
							response.data.errors || {},
						);
					}
				},
				error: function (xhr, status, error) {
					HoGScaffoldSecurity.handleSecurityError(
						'An error occurred. Please try again.',
					);
				},
				complete: function () {
					$submitBtn.prop('disabled', false).text(originalText);
				},
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
			const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
			return phoneRegex.test(phone);
		},

		/**
		 * Validate file uploads
		 *
		 * @param {Event} e - File input change event
		 */
		validateFileUpload: function (e) {
			const file = e.target.files[0];
			const $input = $(e.target);
			const maxSize = 2 * 1024 * 1024; // 2MB
			const allowedTypes = [
				'image/jpeg',
				'image/png',
				'image/gif',
				'image/webp',
				'application/pdf',
			];

			// Clear previous errors
			$input.siblings('.file-error').remove();

			if (!file) return;

			// Check file size
			if (file.size > maxSize) {
				HoGScaffoldSecurity.showFileError(
					$input,
					'File size must not exceed 2MB',
				);
				$input.val('');
				return;
			}

			// Check file type
			if (!allowedTypes.includes(file.type)) {
				HoGScaffoldSecurity.showFileError(
					$input,
					'File type not allowed. Please upload images or PDF files only.',
				);
				$input.val('');
				return;
			}
		},

		/**
		 * Show file upload error
		 *
		 * @param {jQuery} $input - File input element
		 * @param {string} message - Error message
		 */
		showFileError: function ($input, message) {
			$input.after(
				'<div class="file-error error-message">' + message + '</div>',
			);
		},

		/**
		 * Display form validation errors
		 *
		 * @param {jQuery} $form - Form element
		 * @param {Object} errors - Validation errors
		 */
		displayFormErrors: function ($form, errors) {
			$.each(errors, function (field, message) {
				const $field = $form.find('[name="' + field + '"]');
				$field.addClass('field-error');
				$field.after('<div class="error-message">' + message + '</div>');
			});
		},

		/**
		 * Show success message
		 *
		 * @param {jQuery} $form - Form element
		 * @param {string} message - Success message
		 */
		showSuccessMessage: function ($form, message) {
			$form.before('<div class="success-message">' + message + '</div>');
			setTimeout(function () {
				$('.success-message').fadeOut();
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
			const $form = $(this);
			if ($form.data('submitted')) {
				e.preventDefault();
				return false;
			}
			$form.data('submitted', true);

			// Reset after a delay to allow legitimate resubmissions
			setTimeout(function () {
				$form.data('submitted', false);
			}, 3000);
		},

		/**
		 * Validate form inputs on submit
		 */
		validateOnSubmit: function () {
			$(document).on('submit', 'form', function (e) {
				const $form = $(this);
				let isValid = true;

				// Check required fields
				$form.find('[required]').each(function () {
					const $field = $(this);
					if (!$field.val().trim()) {
						$field.addClass('field-error');
						isValid = false;
					} else {
						$field.removeClass('field-error');
					}
				});

				if (!isValid) {
					e.preventDefault();
					HoGScaffoldSecurity.handleSecurityError(
						'Please fill in all required fields.',
					);
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

	// Initialize when document is ready
	$(document).ready(function () {
		HoGScaffoldSecurity.init();
	});
})(jQuery);
