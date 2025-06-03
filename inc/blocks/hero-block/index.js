/**
 * Hero Block
 * A hero section with multiple layout options, background support, and call-to-action buttons
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
