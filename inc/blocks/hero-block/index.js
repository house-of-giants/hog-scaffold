/**
 * Hero Block
 * A hero section with multiple layout options, background support, and call-to-action buttons
 */

/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { registerBlockType } from "@wordpress/blocks";

/**
 * Internal dependencies
 */
import edit from "./edit.js";
import save from "./save.js";
import block from "./block.json";

/* Import CSS for the block */
import "./index.css";

/**
 * Register block
 */
registerBlockType(block.name, {
	title: __("Hero Section", "hog"),
	description: __(
		"A hero section with multiple layout options, background support, and call-to-action buttons",
		"hog"
	),
	edit,
	save,
});
