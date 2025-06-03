/**
 * Hero Block
 * A hero section with multiple layout options, background support, and call-to-action buttons
 */

/**
 * WordPress dependencies
 */
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

/**
 * Internal dependencies
 */
import edit from "./edit.js";
import save from "./save.js";
import metadata from "./block.json";

/**
 * Register block
 * All metadata is defined in block.json
 */
registerBlockType(metadata.name, {
	...metadata,
	edit,
	save,
	example: {
		attributes: {
			heading: __("Welcome to Our Amazing Service", "hog-scaffold"),
			subheading: __(
				"Discover how we can help transform your business with our innovative solutions and expert team.",
				"hog-scaffold"
			),
			layout: "centered",
			minHeight: "60vh",
			primaryButton: {
				text: __("Get Started", "hog-scaffold"),
				url: "#",
				opensInNewTab: false,
			},
			secondaryButton: {
				text: __("Learn More", "hog-scaffold"),
				url: "#",
				opensInNewTab: false,
			},
		},
	},
});
