/**
 * Interactive Tabs Block - Frontend JavaScript
 *
 * Provides frontend interactivity for the tabs block
 *
 * @package HoGScaffold\Blocks\InteractiveTabs
 */

import { validateTabs } from "../utils/interactive/validation.js";
import {
	apiRequest,
	handleApiError,
	withLoading,
} from "../utils/interactive/api.js";
import {
	sanitizeHtml,
	isValidNonceFormat,
} from "../utils/interactive/security.js";

document.addEventListener("DOMContentLoaded", function () {
	// Initialize all interactive tabs blocks on the page
	const tabsBlocks = document.querySelectorAll(".interactive-tabs-block");
	tabsBlocks.forEach(initializeTabsBlock);
});

/**
 * Initialize a single tabs block
 *
 * @param {HTMLElement} blockElement - The tabs block element
 */
function initializeTabsBlock(blockElement) {
	const { blockId, nonce } = blockElement.dataset;

	// Validate nonce format
	if (!isValidNonceFormat(nonce)) {
		console.error("Invalid nonce format for tabs block:", blockId);
		return;
	}

	// Get tabs and panels
	const tabButtons = blockElement.querySelectorAll(".tab-button");
	const tabPanels = blockElement.querySelectorAll(".tab-panel");

	if (tabButtons.length === 0 || tabPanels.length === 0) {
		console.error("No tabs or panels found in block:", blockId);
		return;
	}

	// Validate tab structure
	const tabsData = Array.from(tabButtons).map((button, index) => ({
		title: button.textContent.trim(),
		content: tabPanels[index]?.textContent.trim() || "",
	}));

	const validationErrors = validateTabs(tabsData);
	if (Object.keys(validationErrors).length > 0) {
		console.error("Tab validation failed:", validationErrors);
		return;
	}

	// Initialize tab functionality
	let currentActiveTab = 0;
	let isLoading = false;

	// Find initially active tab
	tabButtons.forEach((button, index) => {
		if (button.classList.contains("active")) {
			currentActiveTab = index;
		}
	});

	// Set up click handlers
	tabButtons.forEach((button, index) => {
		button.addEventListener("click", () => {
			if (!isLoading) {
				switchTab(index);
			}
		});

		// Keyboard navigation
		button.addEventListener("keydown", (event) => {
			handleKeyboardNavigation(event, index);
		});
	});

	/**
	 * Switch to a specific tab
	 *
	 * @param {number} tabIndex - Index of tab to switch to
	 */
	function switchTab(tabIndex) {
		if (tabIndex === currentActiveTab || isLoading) {
			return;
		}

		// Simulate API call for dynamic content loading (example pattern)
		withLoading(setLoading, async () => {
			try {
				// Example: Load dynamic content for the tab
				const dynamicContent = await loadTabContent(tabIndex);

				// Update UI
				updateTabDisplay(tabIndex);

				// Optionally update content if dynamic
				if (dynamicContent) {
					updateTabContent(tabIndex, dynamicContent);
				}

				currentActiveTab = tabIndex;

				// Log analytics or other tracking
				logTabSwitch(blockId, tabIndex);
			} catch (error) {
				const errorMessage = handleApiError(error);
				showErrorMessage(errorMessage);
			}
		});
	}

	/**
	 * Update tab display (visual state)
	 *
	 * @param {number} activeIndex - Index of active tab
	 */
	function updateTabDisplay(activeIndex) {
		// Update tab buttons
		tabButtons.forEach((button, index) => {
			const isActive = index === activeIndex;
			button.classList.toggle("active", isActive);
			button.setAttribute("aria-selected", isActive ? "true" : "false");
			button.setAttribute("tabindex", isActive ? "0" : "-1");
		});

		// Update tab panels
		tabPanels.forEach((panel, index) => {
			const isActive = index === activeIndex;
			panel.classList.toggle("active", isActive);
			panel.hidden = !isActive;

			// Announce change to screen readers
			if (isActive) {
				panel.focus();
			}
		});
	}

	/**
	 * Handle keyboard navigation
	 *
	 * @param {KeyboardEvent} event - Keyboard event
	 * @param {number} currentIndex - Current tab index
	 */
	function handleKeyboardNavigation(event, currentIndex) {
		let newIndex = currentIndex;

		switch (event.key) {
			case "ArrowRight":
			case "ArrowDown":
				event.preventDefault();
				newIndex = (currentIndex + 1) % tabButtons.length;
				break;
			case "ArrowLeft":
			case "ArrowUp":
				event.preventDefault();
				newIndex = (currentIndex - 1 + tabButtons.length) % tabButtons.length;
				break;
			case "Home":
				event.preventDefault();
				newIndex = 0;
				break;
			case "End":
				event.preventDefault();
				newIndex = tabButtons.length - 1;
				break;
			case "Enter":
			case " ":
				event.preventDefault();
				switchTab(currentIndex);
				return;
			default:
				return;
		}

		// Focus the new tab
		tabButtons[newIndex].focus();
	}

	/**
	 * Example: Load dynamic content for a tab
	 *
	 * @param {number} tabIndex - Tab index
	 * @returns {Promise} Dynamic content
	 */
	async function loadTabContent(tabIndex) {
		// Example API call - this would be customized based on needs
		try {
			const response = await apiRequest("/wp-json/wp/v2/posts", {
				method: "GET",
				data: {
					per_page: 1,
					offset: tabIndex,
				},
			});

			return response;
		} catch (error) {
			// Log error but don't fail the tab switch
			console.warn("Failed to load dynamic content:", error);
			return null;
		}
	}

	/**
	 * Update tab content with dynamic data
	 *
	 * @param {number} tabIndex - Tab index
	 * @param {Object} content - Dynamic content
	 */
	function updateTabContent(tabIndex, content) {
		if (!content || !tabPanels[tabIndex]) {
			return;
		}

		// Example: Update content safely
		const contentElement = tabPanels[tabIndex].querySelector(".tab-content");
		if (contentElement && content.length > 0) {
			const post = content[0];
			const safeTitle = sanitizeHtml(post.title?.rendered || "");
			const safeExcerpt = sanitizeHtml(post.excerpt?.rendered || "");

			// Create additional content element
			const dynamicContent = document.createElement("div");
			dynamicContent.className = "dynamic-content";
			dynamicContent.innerHTML = `
				<h4>${safeTitle}</h4>
				<p>${safeExcerpt}</p>
			`;

			// Append to existing content
			contentElement.appendChild(dynamicContent);
		}
	}

	/**
	 * Set loading state
	 *
	 * @param {boolean} loading - Loading state
	 */
	function setLoading(loading) {
		isLoading = loading;

		// Update UI to show loading state
		blockElement.classList.toggle("is-loading", loading);

		// Disable tab buttons during loading
		tabButtons.forEach((button) => {
			button.disabled = loading;
		});
	}

	/**
	 * Show error message to user
	 *
	 * @param {string} message - Error message
	 */
	function showErrorMessage(message) {
		// Create or update error element
		let errorElement = blockElement.querySelector(".tabs-error");
		if (!errorElement) {
			errorElement = document.createElement("div");
			errorElement.className = "tabs-error";
			errorElement.setAttribute("role", "alert");
			blockElement.appendChild(errorElement);
		}

		errorElement.textContent = message;
		errorElement.style.display = "block";

		// Auto-hide after 5 seconds
		setTimeout(() => {
			if (errorElement) {
				errorElement.style.display = "none";
			}
		}, 5000);
	}

	/**
	 * Log tab switch for analytics
	 *
	 * @param {string} blockId - Block ID
	 * @param {number} tabIndex - Tab index
	 */
	function logTabSwitch(blockId, tabIndex) {
		// Example: Send analytics data
		if (window.gtag) {
			window.gtag("event", "tab_switch", {
				custom_parameter_1: blockId,
				custom_parameter_2: tabIndex,
			});
		}

		// Or use custom tracking
		console.log(`Tab switched in block ${blockId} to tab ${tabIndex}`);
	}
}
