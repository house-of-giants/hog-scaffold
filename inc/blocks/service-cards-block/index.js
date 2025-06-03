/**
 * Service Cards Block
 * Display service or feature cards with icons, titles, descriptions, and links in a responsive grid layout
 */

/**
 * WordPress dependencies
 */
import { registerBlockType } from "@wordpress/blocks";

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
	edit,
	save,
});
