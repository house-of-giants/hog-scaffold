/**
 * Interactive Block Security Utilities
 *
 * Provides essential security functions for interactive blocks
 *
 * @package HoGScaffold\Blocks\Utils\Interactive
 */

/**
 * Sanitize HTML content for safe display
 *
 * @param {string} content - Content to sanitize
 * @param {boolean} allowBasicHtml - Whether to allow basic HTML tags
 * @returns {string} Sanitized content
 */
export function sanitizeHtml(content, allowBasicHtml = false) {
	if (typeof content !== "string") return "";

	// Remove script tags and dangerous content
	let sanitized = content
		.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, "")
		.replace(/javascript:/gi, "")
		.replace(/on\w+\s*=/gi, "")
		.replace(/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/gi, "")
		.replace(/<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/gi, "")
		.replace(/<embed\b[^<]*(?:(?!<\/embed>)<[^<]*)*<\/embed>/gi, "");

	// If basic HTML is not allowed, strip all tags
	if (!allowBasicHtml) {
		sanitized = sanitized.replace(/<[^>]*>/g, "");
	} else {
		// Allow only safe HTML tags
		const allowedTags = ["p", "br", "strong", "em", "b", "i", "u", "span"];
		const tagRegex = /<\/?([a-zA-Z][a-zA-Z0-9]*)[^>]*>/g;

		sanitized = sanitized.replace(tagRegex, (match, tagName) => {
			return allowedTags.includes(tagName.toLowerCase()) ? match : "";
		});
	}

	return sanitized.trim();
}

/**
 * Escape HTML entities for safe output
 *
 * @param {string} text - Text to escape
 * @returns {string} Escaped text
 */
export function escapeHtml(text) {
	if (typeof text !== "string") return "";

	const div = document.createElement("div");
	div.textContent = text;
	return div.innerHTML;
}

/**
 * Validate and sanitize user input
 *
 * @param {string} input - Input to validate and sanitize
 * @param {Object} options - Validation options
 * @returns {Object} Validation result
 */
export function validateInput(input, options = {}) {
	const {
		maxLength = 1000,
		minLength = 0,
		allowHtml = false,
		required = false,
		pattern = null,
	} = options;

	const result = {
		isValid: true,
		sanitized: "",
		errors: [],
	};

	// Check if required
	if (required && (!input || input.trim() === "")) {
		result.isValid = false;
		result.errors.push("This field is required");
		return result;
	}

	// If empty and not required, return valid
	if (!input || input.trim() === "") {
		result.sanitized = "";
		return result;
	}

	// Length validation
	if (input.length < minLength) {
		result.isValid = false;
		result.errors.push(`Minimum length is ${minLength} characters`);
	}

	if (input.length > maxLength) {
		result.isValid = false;
		result.errors.push(`Maximum length is ${maxLength} characters`);
	}

	// Pattern validation
	if (pattern && !pattern.test(input)) {
		result.isValid = false;
		result.errors.push("Invalid format");
	}

	// Sanitize the input
	result.sanitized = sanitizeHtml(input, allowHtml);

	return result;
}

/**
 * Generate a secure random token
 *
 * @param {number} length - Token length
 * @returns {string} Random token
 */
export function generateSecureToken(length = 32) {
	const chars =
		"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	let result = "";

	// Use crypto.getRandomValues if available, fallback to Math.random
	if (window.crypto && window.crypto.getRandomValues) {
		const array = new Uint8Array(length);
		window.crypto.getRandomValues(array);

		for (let i = 0; i < length; i++) {
			result += chars.charAt(array[i] % chars.length);
		}
	} else {
		for (let i = 0; i < length; i++) {
			result += chars.charAt(Math.floor(Math.random() * chars.length));
		}
	}

	return result;
}

/**
 * Check if a nonce is valid format (basic client-side check)
 *
 * @param {string} nonce - Nonce to check
 * @returns {boolean} Whether nonce appears valid
 */
export function isValidNonceFormat(nonce) {
	// WordPress nonces are typically 10 characters of alphanumeric
	const nonceRegex = /^[a-zA-Z0-9]{10}$/;
	return typeof nonce === "string" && nonceRegex.test(nonce);
}

/**
 * Rate limiting helper for API calls
 *
 * @param {string} key - Unique key for rate limiting
 * @param {number} maxRequests - Maximum requests allowed
 * @param {number} windowMs - Time window in milliseconds
 * @returns {boolean} Whether request is allowed
 */
export function checkRateLimit(key, maxRequests = 10, windowMs = 60000) {
	const now = Date.now();
	const storageKey = `rate_limit_${key}`;

	try {
		let requests = JSON.parse(localStorage.getItem(storageKey) || "[]");

		// Remove old requests outside the window
		requests = requests.filter((timestamp) => now - timestamp < windowMs);

		// Check if we're over the limit
		if (requests.length >= maxRequests) {
			return false;
		}

		// Add current request
		requests.push(now);
		localStorage.setItem(storageKey, JSON.stringify(requests));

		return true;
	} catch (error) {
		// If localStorage is not available, allow the request
		console.warn("Rate limiting not available:", error);
		return true;
	}
}

/**
 * Content Security Policy helper
 *
 * @param {string} content - Content to check
 * @returns {Object} CSP validation result
 */
export function validateCSP(content) {
	const result = {
		isValid: true,
		violations: [],
	};

	// Check for inline scripts
	if (/<script[^>]*>/.test(content)) {
		result.isValid = false;
		result.violations.push("Inline scripts not allowed");
	}

	// Check for inline styles
	if (/style\s*=/.test(content)) {
		result.isValid = false;
		result.violations.push("Inline styles not allowed");
	}

	// Check for data URLs in images
	if (/src\s*=\s*["']data:/.test(content)) {
		result.isValid = false;
		result.violations.push("Data URLs not allowed");
	}

	return result;
}

/**
 * Validate file upload security
 *
 * @param {File} file - File to validate
 * @param {Object} options - Validation options
 * @returns {Object} Validation result
 */
export function validateFileUpload(file, options = {}) {
	const {
		maxSize = 5 * 1024 * 1024, // 5MB default
		allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"],
		allowedExtensions = ["jpg", "jpeg", "png", "gif", "webp"],
	} = options;

	const result = {
		isValid: true,
		errors: [],
	};

	// Check file size
	if (file.size > maxSize) {
		result.isValid = false;
		result.errors.push(
			`File size exceeds ${Math.round(maxSize / 1024 / 1024)}MB limit`
		);
	}

	// Check file type
	if (!allowedTypes.includes(file.type)) {
		result.isValid = false;
		result.errors.push("File type not allowed");
	}

	// Check file extension
	const extension = file.name.split(".").pop().toLowerCase();
	if (!allowedExtensions.includes(extension)) {
		result.isValid = false;
		result.errors.push("File extension not allowed");
	}

	return result;
}
