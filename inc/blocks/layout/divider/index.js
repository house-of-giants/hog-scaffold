/**
 * Divider Block Registration
 *
 * @package HoGScaffold\Blocks\Layout
 */

import { registerBlockType } from "@wordpress/blocks";
import Edit from "./edit.js";
import Save from "./save.js";
import metadata from "./block.json";

/**
 * Register the Divider Block
 */
registerBlockType(metadata.name, {
	...metadata,
	edit: Edit,
	save: Save,
});
