/**
 * Call to Action Block
 * A compelling call-to-action section with heading, text, button, and background options
 */

/**
 * WordPress dependencies
 */
import { registerBlockType } from "@wordpress/blocks";

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
	edit: Edit,
	save,
});
