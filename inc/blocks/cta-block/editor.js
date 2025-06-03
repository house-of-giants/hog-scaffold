/**
 * Call to Action Block
 * A compelling call-to-action section with heading, text, button, and background options
 */

/**
 * WordPress dependencies
 */
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

/**
 * Internal dependencies
 */
import Edit from "./edit.js";
import save from "./save.js";
import metadata from "./block.json";

/* CSS import temporarily commented out due to webpack configuration issues */
// import "./index.css";

/**
 * Register block
 * All metadata is defined in block.json
 */
registerBlockType(metadata.name, {
	...metadata,
	edit: Edit,
	save,
	example: {
		attributes: {
			heading: __("Ready to Get Started?", "hog-scaffold"),
			text: __(
				"Join thousands of satisfied customers who have transformed their business with our solutions. Take the first step today.",
				"hog-scaffold"
			),
			buttonText: __("Start Your Journey", "hog-scaffold"),
			buttonUrl: "#",
			buttonStyle: "primary",
			buttonSize: "large",
			layout: "centered",
			backgroundType: "gradient",
			backgroundGradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
			width: "normal",
			spacing: "large",
		},
	},
});
