/**
 * Interactive Block Validation Utilities
 *
 * Provides essential validation functions for interactive blocks
 *
 * @package HoGScaffold\Blocks\Utils\Interactive
 */

/**
 * Validate required fields
 *
 * @param {Object} values - Values to validate
 * @param {Array} requiredFields - Array of required field names
 * @returns {Object} Validation errors object
 */
export function validateRequired(values, requiredFields = []) {
	const errors = {};

	requiredFields.forEach((field) => {
		if (
			!values[field] ||
			(typeof values[field] === "string" && values[field].trim() === "")
		) {
			errors[field] = `${field} is required`;
		}
	});

	return errors;
}

/**
 * Validate email format
 *
 * @param {string} email - Email to validate
 * @returns {boolean} Whether email is valid
 */
export function validateEmail(email) {
	const emailRegex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i;
	return emailRegex.test(email);
}

/**
 * Validate URL format
 *
 * @param {string} url - URL to validate
 * @returns {boolean} Whether URL is valid
 */
export function validateUrl(url) {
	// Basic URL validation using regex for broader compatibility
	const urlRegex =
		/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_+.~#?&//=]*)$/;
	return urlRegex.test(url);
}

/**
 * Validate string length
 *
 * @param {string} value - String to validate
 * @param {number} minLength - Minimum length
 * @param {number} maxLength - Maximum length
 * @returns {Object} Validation result
 */
export function validateLength(value, minLength = 0, maxLength = Infinity) {
	const length = value ? value.length : 0;

	return {
		isValid: length >= minLength && length <= maxLength,
		length,
		minLength,
		maxLength,
	};
}

/**
 * Validate phone number format
 *
 * @param {string} phone - Phone number to validate
 * @returns {boolean} Whether phone number is valid
 */
export function validatePhone(phone) {
	// Basic phone validation - allows various international formats
	const phoneRegex = /^[+]?[1-9][\d]{0,15}$/;
	const cleanPhone = phone.replace(/[\s\-().]/g, "");
	return phoneRegex.test(cleanPhone);
}

/**
 * Validate numeric input
 *
 * @param {string|number} value - Value to validate
 * @param {Object} options - Validation options
 * @returns {Object} Validation result
 */
export function validateNumeric(value, options = {}) {
	const { min = -Infinity, max = Infinity, integer = false } = options;

	const result = {
		isValid: true,
		errors: [],
		value: null,
	};

	// Convert to number
	const num = Number(value);

	// Check if it's a valid number
	if (isNaN(num)) {
		result.isValid = false;
		result.errors.push("Must be a valid number");
		return result;
	}

	// Check for integer requirement
	if (integer && !Number.isInteger(num)) {
		result.isValid = false;
		result.errors.push("Must be a whole number");
	}

	// Check min/max bounds
	if (num < min) {
		result.isValid = false;
		result.errors.push(`Must be at least ${min}`);
	}

	if (num > max) {
		result.isValid = false;
		result.errors.push(`Must be no more than ${max}`);
	}

	result.value = num;
	return result;
}

/**
 * Validate date format
 *
 * @param {string} dateString - Date string to validate
 * @param {string} format - Expected format (ISO, US, etc.)
 * @returns {Object} Validation result
 */
export function validateDate(dateString, format = "ISO") {
	const result = {
		isValid: true,
		date: null,
		errors: [],
	};

	let date;

	try {
		if (format === "ISO") {
			// ISO format: YYYY-MM-DD
			const isoRegex = /^\d{4}-\d{2}-\d{2}$/;
			if (!isoRegex.test(dateString)) {
				result.isValid = false;
				result.errors.push("Date must be in YYYY-MM-DD format");
				return result;
			}
		}

		date = new Date(dateString);

		if (isNaN(date.getTime())) {
			result.isValid = false;
			result.errors.push("Invalid date");
			return result;
		}

		result.date = date;
	} catch {
		result.isValid = false;
		result.errors.push("Invalid date format");
	}

	return result;
}

/**
 * Validate password strength
 *
 * @param {string} password - Password to validate
 * @param {Object} options - Validation options
 * @returns {Object} Validation result
 */
export function validatePassword(password, options = {}) {
	const {
		minLength = 8,
		requireUppercase = true,
		requireLowercase = true,
		requireNumbers = true,
		requireSpecial = false,
	} = options;

	const result = {
		isValid: true,
		strength: "weak",
		errors: [],
	};

	// Length check
	if (password.length < minLength) {
		result.isValid = false;
		result.errors.push(`Password must be at least ${minLength} characters`);
	}

	// Character requirements
	if (requireUppercase && !/[A-Z]/.test(password)) {
		result.isValid = false;
		result.errors.push("Password must contain uppercase letters");
	}

	if (requireLowercase && !/[a-z]/.test(password)) {
		result.isValid = false;
		result.errors.push("Password must contain lowercase letters");
	}

	if (requireNumbers && !/\d/.test(password)) {
		result.isValid = false;
		result.errors.push("Password must contain numbers");
	}

	if (requireSpecial && !/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
		result.isValid = false;
		result.errors.push("Password must contain special characters");
	}

	// Calculate strength
	let strengthScore = 0;
	if (password.length >= 8) strengthScore++;
	if (/[A-Z]/.test(password)) strengthScore++;
	if (/[a-z]/.test(password)) strengthScore++;
	if (/\d/.test(password)) strengthScore++;
	if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strengthScore++;

	if (strengthScore >= 4) result.strength = "strong";
	else if (strengthScore >= 3) result.strength = "medium";

	return result;
}

/**
 * Sanitize user input for display
 *
 * @param {string} input - Input to sanitize
 * @returns {string} Sanitized input
 */
export function sanitizeInput(input) {
	if (typeof input !== "string") return "";

	// Remove script tags and dangerous content
	return input
		.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, "")
		.replace(/javascript:/gi, "")
		.replace(/on\w+\s*=/gi, "");
}

/**
 * Validate tab data structure
 *
 * @param {Array} tabs - Array of tab objects
 * @returns {Object} Validation result
 */
export function validateTabs(tabs) {
	const errors = {};

	if (!Array.isArray(tabs)) {
		errors.tabs = "Tabs must be an array";
		return errors;
	}

	if (tabs.length === 0) {
		errors.tabs = "At least one tab is required";
		return errors;
	}

	tabs.forEach((tab, index) => {
		if (!tab.title || tab.title.trim() === "") {
			errors[`tab_${index}_title`] = `Tab ${index + 1} title is required`;
		}

		if (!tab.content || tab.content.trim() === "") {
			errors[`tab_${index}_content`] = `Tab ${index + 1} content is required`;
		}
	});

	return errors;
}

/**
 * Validate hex color format
 *
 * @param {string} color - Color string to validate
 * @returns {boolean} Whether color is valid hex format
 */
export function validateHexColor(color) {
	const hexRegex = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;
	return hexRegex.test(color);
}

/**
 * Validate form data comprehensively
 *
 * @param {Object} data - Form data to validate
 * @param {Object} rules - Validation rules
 * @returns {Object} Validation result
 */
export function validateForm(data, rules) {
	const errors = {};
	let isValid = true;

	Object.keys(rules).forEach((field) => {
		const rule = rules[field];
		const value = data[field];

		// Required validation
		if (rule.required && (!value || value.toString().trim() === "")) {
			errors[field] = rule.requiredMessage || `${field} is required`;
			isValid = false;
			return;
		}

		// Skip other validations if field is empty and not required
		if (!value || value.toString().trim() === "") {
			return;
		}

		// Type-specific validation
		if (rule.type === "email" && !validateEmail(value)) {
			errors[field] = rule.emailMessage || "Please enter a valid email address";
			isValid = false;
		}

		if (rule.type === "url" && !validateUrl(value)) {
			errors[field] = rule.urlMessage || "Please enter a valid URL";
			isValid = false;
		}

		if (rule.type === "phone" && !validatePhone(value)) {
			errors[field] = rule.phoneMessage || "Please enter a valid phone number";
			isValid = false;
		}

		// Length validation
		if (rule.minLength && value.length < rule.minLength) {
			errors[field] =
				rule.minLengthMessage ||
				`Must be at least ${rule.minLength} characters`;
			isValid = false;
		}

		if (rule.maxLength && value.length > rule.maxLength) {
			errors[field] =
				rule.maxLengthMessage ||
				`Must be no more than ${rule.maxLength} characters`;
			isValid = false;
		}

		// Pattern validation
		if (rule.pattern && !rule.pattern.test(value)) {
			errors[field] = rule.patternMessage || "Invalid format";
			isValid = false;
		}

		// Custom validation function
		if (rule.custom && typeof rule.custom === "function") {
			const customResult = rule.custom(value, data);
			if (customResult !== true) {
				errors[field] = customResult || "Invalid value";
				isValid = false;
			}
		}
	});

	return { isValid, errors };
}

/**
 * Get user-friendly error message
 *
 * @param {string} field - Field name
 * @param {string} error - Error type
 * @returns {string} User-friendly error message
 */
export function getErrorMessage(field, error) {
	const errorMessages = {
		required: `${field} is required`,
		email: "Please enter a valid email address",
		url: "Please enter a valid URL",
		phone: "Please enter a valid phone number",
		minLength: `${field} is too short`,
		maxLength: `${field} is too long`,
		invalid: `${field} is not valid`,
	};

	return errorMessages[error] || `${field} has an error`;
}
