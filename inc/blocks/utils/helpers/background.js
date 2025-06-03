/**
 * Background Helper Functions
 *
 * Utility functions for handling background styles in blocks
 *
 * @package HoGScaffold\Blocks\Utils
 */

/**
 * Get CSS styles for background configuration
 *
 * @param {Object} background Background configuration object
 * @returns {Object} CSS styles object
 */
export function getBackgroundStyles(background) {
	const styles = {};

	if (background.backgroundColor) {
		styles.backgroundColor = background.backgroundColor;
	}

	if (background.backgroundImage) {
		styles.backgroundImage = `url(${background.backgroundImage.url})`;
		styles.backgroundSize = background.backgroundSize || "cover";
		styles.backgroundPosition = background.backgroundPosition || "center";
		styles.backgroundRepeat = background.backgroundRepeat || "no-repeat";
		styles.backgroundAttachment = background.backgroundAttachment || "scroll";
	}

	return styles;
}

/**
 * Get overlay styles for background
 *
 * @param {Object} background Background configuration object
 * @returns {Object} CSS styles object for overlay
 */
export function getOverlayStyles(background) {
	const styles = {};

	if (background.overlay && background.overlayColor) {
		const opacity = background.overlayOpacity / 100 || 0.5;
		styles.backgroundColor = background.overlayColor;
		styles.opacity = opacity;
		styles.position = "absolute";
		styles.top = 0;
		styles.left = 0;
		styles.width = "100%";
		styles.height = "100%";
		styles.zIndex = 1;
	}

	return styles;
}

/**
 * Get CSS classes for background configuration
 *
 * @param {Object} background Background configuration object
 * @returns {string} Space-separated CSS classes
 */
export function getBackgroundClasses(background) {
	const classes = [];

	if (background.backgroundColor) {
		classes.push("has-background");
	}

	if (background.backgroundImage) {
		classes.push("has-background-image");
		classes.push(`background-size-${background.backgroundSize || "cover"}`);
		classes.push(
			`background-position-${(background.backgroundPosition || "center").replace(" ", "-")}`
		);
		classes.push(
			`background-repeat-${background.backgroundRepeat || "no-repeat"}`
		);
		classes.push(
			`background-attachment-${background.backgroundAttachment || "scroll"}`
		);
	}

	if (background.overlay) {
		classes.push("has-overlay");
	}

	return classes.filter(Boolean).join(" ");
}

/**
 * Check if background needs relative positioning for overlay
 *
 * @param {Object} background Background configuration object
 * @returns {boolean} True if relative positioning is needed
 */
export function needsRelativePositioning(background) {
	return background.overlay && background.overlayColor;
}

/**
 * Default background values
 */
export const DEFAULT_BACKGROUND = {
	backgroundColor: "",
	backgroundImage: null,
	backgroundSize: "cover",
	backgroundPosition: "center",
	backgroundRepeat: "no-repeat",
	backgroundAttachment: "scroll",
	overlay: false,
	overlayColor: "",
	overlayOpacity: 50,
};
