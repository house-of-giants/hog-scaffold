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
 * Block Registration
 */
registerBlockType(metadata.name, {
	...metadata,
	title: __("Team Profiles", "hog-scaffold"),
	description: __(
		"Display team member profiles with photos, names, positions, and social links.",
		"hog-scaffold"
	),
	edit: Edit,
	save,
});
