/**
 * Example Block
 * An example block for demonstrating block development patterns and best practices
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

/* Uncomment for CSS overrides in the admin */
// import './index.css';

/**
 * Register block
 * All metadata is defined in block.json
 */
registerBlockType(metadata.name, {
	edit,
	save,
});
