/**
 * Service Cards Block
 * Display service or feature cards with icons, titles, descriptions, and links in a responsive grid layout
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
	title: __("Service Cards", "hog"),
	description: __(
		"Display service or feature cards with icons, titles, descriptions, and links in a responsive grid layout",
		"hog"
	),
	edit,
	save,
});
