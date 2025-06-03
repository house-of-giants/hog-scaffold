/**
 * Spacing Helper Functions
 *
 * Utility functions for handling spacing in blocks
 *
 * @package HoGScaffold\Blocks\Utils
 */

/**
 * Get CSS class name for spacing value
 *
 * @param {string} property The CSS property (margin, padding)
 * @param {string} direction The direction (top, bottom, left, right)
 * @param {string} value The spacing value
 * @returns {string} CSS class name
 */
export function getSpacingClass(property, direction, value) {
	if (!value || value === "none") {
		return "";
	}

	if (value === "custom") {
		return "";
	}

	return `${property}-${direction}-${value}`;
}

/**
 * Get inline CSS styles for custom spacing values
 *
 * @param {Object} spacing Spacing configuration object
 * @returns {Object} CSS styles object
 */
export function getSpacingStyles(spacing) {
	const styles = {};

	// Handle margin
	if (spacing.marginTop === "custom" && spacing.customMarginTop) {
		styles.marginTop = `${spacing.customMarginTop}px`;
	}
	if (spacing.marginBottom === "custom" && spacing.customMarginBottom) {
		styles.marginBottom = `${spacing.customMarginBottom}px`;
	}
	if (spacing.marginLeft === "custom" && spacing.customMarginLeft) {
		styles.marginLeft = `${spacing.customMarginLeft}px`;
	}
	if (spacing.marginRight === "custom" && spacing.customMarginRight) {
		styles.marginRight = `${spacing.customMarginRight}px`;
	}

	// Handle padding
	if (spacing.paddingTop === "custom" && spacing.customPaddingTop) {
		styles.paddingTop = `${spacing.customPaddingTop}px`;
	}
	if (spacing.paddingBottom === "custom" && spacing.customPaddingBottom) {
		styles.paddingBottom = `${spacing.customPaddingBottom}px`;
	}
	if (spacing.paddingLeft === "custom" && spacing.customPaddingLeft) {
		styles.paddingLeft = `${spacing.customPaddingLeft}px`;
	}
	if (spacing.paddingRight === "custom" && spacing.customPaddingRight) {
		styles.paddingRight = `${spacing.customPaddingRight}px`;
	}

	return styles;
}

/**
 * Get CSS classes for all spacing values
 *
 * @param {Object} spacing Spacing configuration object
 * @returns {string} Space-separated CSS classes
 */
export function getSpacingClasses(spacing) {
	const classes = [];

	// Margin classes
	if (spacing.marginTop && spacing.marginTop !== "custom") {
		classes.push(getSpacingClass("margin", "top", spacing.marginTop));
	}
	if (spacing.marginBottom && spacing.marginBottom !== "custom") {
		classes.push(getSpacingClass("margin", "bottom", spacing.marginBottom));
	}
	if (spacing.marginLeft && spacing.marginLeft !== "custom") {
		classes.push(getSpacingClass("margin", "left", spacing.marginLeft));
	}
	if (spacing.marginRight && spacing.marginRight !== "custom") {
		classes.push(getSpacingClass("margin", "right", spacing.marginRight));
	}

	// Padding classes
	if (spacing.paddingTop && spacing.paddingTop !== "custom") {
		classes.push(getSpacingClass("padding", "top", spacing.paddingTop));
	}
	if (spacing.paddingBottom && spacing.paddingBottom !== "custom") {
		classes.push(getSpacingClass("padding", "bottom", spacing.paddingBottom));
	}
	if (spacing.paddingLeft && spacing.paddingLeft !== "custom") {
		classes.push(getSpacingClass("padding", "left", spacing.paddingLeft));
	}
	if (spacing.paddingRight && spacing.paddingRight !== "custom") {
		classes.push(getSpacingClass("padding", "right", spacing.paddingRight));
	}

	return classes.filter(Boolean).join(" ");
}

/**
 * Default spacing values
 */
export const DEFAULT_SPACING = {
	marginTop: "medium",
	marginBottom: "medium",
	marginLeft: "none",
	marginRight: "none",
	paddingTop: "medium",
	paddingBottom: "medium",
	paddingLeft: "medium",
	paddingRight: "medium",
	customMarginTop: 0,
	customMarginBottom: 0,
	customMarginLeft: 0,
	customMarginRight: 0,
	customPaddingTop: 0,
	customPaddingBottom: 0,
	customPaddingLeft: 0,
	customPaddingRight: 0,
	linkSpacing: false,
};
