/**
 * Service Cards Block
 * A flexible grid of service cards with icons, titles, descriptions, and optional links
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
			heading: __("Our Services", "hog-scaffold"),
			subheading: __(
				"Discover how we can help your business grow",
				"hog-scaffold"
			),
			cards: [
				{
					title: __("Web Development", "hog-scaffold"),
					description: __(
						"Custom websites and web applications built with modern technologies.",
						"hog-scaffold"
					),
					icon: "code",
					link: "#",
					opensInNewTab: false,
				},
				{
					title: __("Digital Marketing", "hog-scaffold"),
					description: __(
						"Strategic marketing campaigns to boost your online presence.",
						"hog-scaffold"
					),
					icon: "megaphone",
					link: "#",
					opensInNewTab: false,
				},
				{
					title: __("Consulting", "hog-scaffold"),
					description: __(
						"Expert advice to optimize your business processes and strategy.",
						"hog-scaffold"
					),
					icon: "lightbulb",
					link: "#",
					opensInNewTab: false,
				},
			],
			columns: 3,
			layout: "grid",
			cardStyle: "elevated",
		},
	},
});
