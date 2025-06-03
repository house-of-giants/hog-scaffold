/**
 * Interactive Block API Utilities
 *
 * Provides essential API integration functions for interactive blocks
 *
 * @package HoGScaffold\Blocks\Utils\Interactive
 */

/**
 * Make an API request with error handling and security
 *
 * @param {string} endpoint - API endpoint
 * @param {Object} options - Request options
 * @returns {Promise} API response
 */
export async function apiRequest(endpoint, options = {}) {
	const {
		method = "GET",
		data = null,
		timeout = 10000,
		headers = {},
		cache = false,
	} = options;

	// Set up default headers
	const defaultHeaders = {
		"Content-Type": "application/json",
		"X-Requested-With": "XMLHttpRequest",
	};

	// Add WordPress nonce if available
	if (window.wpApiSettings && window.wpApiSettings.nonce) {
		defaultHeaders["X-WP-Nonce"] = window.wpApiSettings.nonce;
	}

	const requestHeaders = { ...defaultHeaders, ...headers };

	// Create AbortController for timeout handling
	const controller = new window.AbortController();
	const timeoutId = window.setTimeout(() => controller.abort(), timeout);

	try {
		const response = await fetch(endpoint, {
			method,
			headers: requestHeaders,
			body: data ? JSON.stringify(data) : null,
			signal: controller.signal,
			credentials: "same-origin",
		});

		window.clearTimeout(timeoutId);

		if (!response.ok) {
			const errorData = await response.json().catch(() => ({}));
			throw new Error(
				errorData.message || `HTTP ${response.status}: ${response.statusText}`
			);
		}

		const result = await response.json();

		// Cache successful responses if requested
		if (cache && method === "GET") {
			cacheResponse(endpoint, result);
		}

		return result;
	} catch (error) {
		window.clearTimeout(timeoutId);

		if (error.name === "AbortError") {
			throw new Error("Request timeout");
		}

		throw error;
	}
}

/**
 * WordPress REST API helper
 *
 * @param {string} route - REST API route
 * @param {Object} options - Request options
 * @returns {Promise} API response
 */
export async function wpApiRequest(route, options = {}) {
	const baseUrl = window.wpApiSettings?.root || "/wp-json/wp/v2/";
	const endpoint = `${baseUrl.replace(/\/$/, "")}${route.startsWith("/") ? route : `/${route}`}`;

	return apiRequest(endpoint, options);
}

/**
 * Handle API loading states
 *
 * @param {Function} setLoading - Loading state setter
 * @param {Function} asyncFunction - Async function to execute
 * @returns {Promise} Result of async function
 */
export async function withLoading(setLoading, asyncFunction) {
	setLoading(true);
	try {
		const result = await asyncFunction();
		return result;
	} finally {
		setLoading(false);
	}
}

/**
 * Retry API requests with exponential backoff
 *
 * @param {Function} requestFunction - Function that makes the request
 * @param {Object} options - Retry options
 * @returns {Promise} Request result
 */
export async function retryRequest(requestFunction, options = {}) {
	const {
		maxRetries = 3,
		baseDelay = 1000,
		maxDelay = 10000,
		retryCondition = (error) =>
			error.name === "TypeError" || error.message.includes("timeout"),
	} = options;

	let lastError;

	for (let attempt = 0; attempt <= maxRetries; attempt++) {
		try {
			return await requestFunction();
		} catch (error) {
			lastError = error;

			// Don't retry if condition not met or max retries reached
			if (!retryCondition(error) || attempt === maxRetries) {
				break;
			}

			// Calculate delay with exponential backoff
			const delay = Math.min(baseDelay * Math.pow(2, attempt), maxDelay);
			await new Promise((resolve) => setTimeout(resolve, delay));
		}
	}

	throw lastError;
}

/**
 * Save block data to WordPress
 *
 * @param {string} blockId - Unique block identifier
 * @param {Object} data - Data to save
 * @returns {Promise} Save result
 */
export async function saveBlockData(blockId, data) {
	return apiRequest("blocks/save", {
		method: "POST",
		data: {
			blockId,
			data,
			timestamp: new Date().toISOString(),
		},
	});
}

/**
 * Load block data from WordPress
 *
 * @param {string} blockId - Unique block identifier
 * @returns {Promise} Load result
 */
export async function loadBlockData(blockId) {
	return apiRequest(`blocks/load/${blockId}`);
}

/**
 * Verify nonce for security
 *
 * @param {string} nonce - Nonce to verify
 * @param {string} action - Action associated with nonce
 * @returns {Promise} Verification result
 */
export async function verifyNonce(nonce, action) {
	return apiRequest("verify-nonce", {
		method: "POST",
		data: { nonce, action },
	});
}

/**
 * Handle API errors with user-friendly messages
 *
 * @param {Error} error - Error object
 * @returns {string} User-friendly error message
 */
export function handleApiError(error) {
	// Common error patterns
	const errorPatterns = {
		"Failed to fetch":
			"Network connection error. Please check your internet connection.",
		timeout: "Request timed out. Please try again.",
		401: "You are not authorized to perform this action.",
		403: "Access denied. You do not have permission for this action.",
		404: "The requested resource was not found.",
		429: "Too many requests. Please wait a moment and try again.",
		500: "Server error. Please try again later.",
	};

	const message = error.message || "Unknown error";

	// Check for pattern matches
	for (const [pattern, friendlyMessage] of Object.entries(errorPatterns)) {
		if (message.toLowerCase().includes(pattern.toLowerCase())) {
			return friendlyMessage;
		}
	}

	// Return original message if no pattern matched, but sanitized
	return message.length > 100
		? "An error occurred. Please try again."
		: message;
}

/**
 * Debounce API calls to prevent excessive requests
 *
 * @param {Function} func - Function to debounce
 * @param {number} delay - Delay in milliseconds
 * @returns {Function} Debounced function
 */
export function debounceApiCall(func, delay = 300) {
	let timeoutId;

	return function (...args) {
		window.clearTimeout(timeoutId);

		return new Promise((resolve, reject) => {
			timeoutId = window.setTimeout(async () => {
				try {
					const result = await func.apply(this, args);
					resolve(result);
				} catch (error) {
					reject(error);
				}
			}, delay);
		});
	};
}

/**
 * Simple response caching for GET requests
 *
 * @param {string} key - Cache key
 * @param {*} data - Data to cache
 * @param {number} ttl - Time to live in milliseconds
 */
function cacheResponse(key, data, ttl = 300000) {
	// 5 minutes default
	if (!window.localStorage) return;

	try {
		const cacheData = {
			data,
			timestamp: Date.now(),
			ttl,
		};
		localStorage.setItem(`api_cache_${key}`, JSON.stringify(cacheData));
	} catch {
		// Storage quota exceeded or other localStorage error
		console.warn("Failed to cache API response");
	}
}

/**
 * Get cached response if valid
 *
 * @param {string} key - Cache key
 * @returns {*} Cached data or null
 */
export function getCachedResponse(key) {
	if (!window.localStorage) return null;

	try {
		const cached = localStorage.getItem(`api_cache_${key}`);
		if (!cached) return null;

		const { data, timestamp, ttl } = JSON.parse(cached);

		if (Date.now() - timestamp > ttl) {
			localStorage.removeItem(`api_cache_${key}`);
			return null;
		}

		return data;
	} catch {
		return null;
	}
}

/**
 * WordPress AJAX helper for custom endpoints
 *
 * @param {string} action - WordPress AJAX action
 * @param {Object} data - Data to send
 * @param {Object} options - Additional options
 * @returns {Promise} AJAX response
 */
export async function wpAjaxRequest(action, data = {}, options = {}) {
	const ajaxUrl = window.ajaxurl || "/wp-admin/admin-ajax.php";

	const formData = new window.FormData();
	formData.append("action", action);

	// Add nonce if available
	if (window.wpApiSettings?.nonce) {
		formData.append("_wpnonce", window.wpApiSettings.nonce);
	}

	// Add data
	Object.keys(data).forEach((key) => {
		formData.append(key, data[key]);
	});

	return apiRequest(ajaxUrl, {
		method: "POST",
		body: formData,
		headers: {}, // Let browser set Content-Type for FormData
		...options,
	});
}

/**
 * Batch multiple API requests
 *
 * @param {Array} requests - Array of request configurations
 * @param {Object} options - Batch options
 * @returns {Promise} Array of results
 */
export async function batchRequests(requests, options = {}) {
	const { maxConcurrent = 3, failFast = false } = options;

	const results = [];
	const errors = [];

	// Process requests in chunks
	for (let i = 0; i < requests.length; i += maxConcurrent) {
		const chunk = requests.slice(i, i + maxConcurrent);

		const chunkPromises = chunk.map(async (request, index) => {
			try {
				const result = await apiRequest(request.endpoint, request.options);
				return { index: i + index, result, error: null };
			} catch (error) {
				const errorResult = { index: i + index, result: null, error };

				if (failFast) {
					throw error;
				}

				return errorResult;
			}
		});

		const chunkResults = await Promise.all(chunkPromises);

		chunkResults.forEach(({ index, result, error }) => {
			results[index] = result;
			if (error) {
				errors[index] = error;
			}
		});
	}

	return { results, errors };
}

/**
 * Upload file with progress tracking
 *
 * @param {File} file - File to upload
 * @param {string} endpoint - Upload endpoint
 * @param {Function} onProgress - Progress callback
 * @param {Object} options - Upload options
 * @returns {Promise} Upload result
 */
export async function uploadFile(
	file,
	endpoint,
	onProgress = null,
	options = {}
) {
	const {
		timeout = 60000, // 1 minute for file uploads
		additionalData = {},
	} = options;

	const formData = new window.FormData();
	formData.append("file", file);

	// Add additional data
	Object.keys(additionalData).forEach((key) => {
		formData.append(key, additionalData[key]);
	});

	// Add WordPress nonce if available
	if (window.wpApiSettings?.nonce) {
		formData.append("_wpnonce", window.wpApiSettings.nonce);
	}

	return new Promise((resolve, reject) => {
		const xhr = new window.XMLHttpRequest();

		// Set up progress tracking
		if (onProgress) {
			xhr.upload.addEventListener("progress", (e) => {
				if (e.lengthComputable) {
					const percentComplete = (e.loaded / e.total) * 100;
					onProgress(percentComplete);
				}
			});
		}

		// Set up completion handlers
		xhr.addEventListener("load", () => {
			if (xhr.status >= 200 && xhr.status < 300) {
				try {
					const result = JSON.parse(xhr.responseText);
					resolve(result);
				} catch {
					resolve(xhr.responseText);
				}
			} else {
				reject(new Error(`Upload failed: ${xhr.status} ${xhr.statusText}`));
			}
		});

		xhr.addEventListener("error", () => {
			reject(new Error("Upload failed: Network error"));
		});

		xhr.addEventListener("timeout", () => {
			reject(new Error("Upload failed: Timeout"));
		});

		// Configure and send request
		xhr.open("POST", endpoint);
		xhr.timeout = timeout;
		xhr.send(formData);
	});
}
