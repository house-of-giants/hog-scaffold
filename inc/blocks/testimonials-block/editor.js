/**
 * Testimonials Block
 * A showcase of customer testimonials with quotes, names, roles, and optional photos
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
			heading: __("What Our Clients Say", "hog-scaffold"),
			subheading: __("Don't just take our word for it", "hog-scaffold"),
			testimonials: [
				{
					quote: __(
						"Working with this team has been transformative for our business. Their expertise and dedication exceeded all expectations.",
						"hog-scaffold"
					),
					name: __("Jennifer Martinez", "hog-scaffold"),
					role: __("Marketing Director", "hog-scaffold"),
					company: __("TechCorp Solutions", "hog-scaffold"),
					image: {},
					rating: 5,
				},
				{
					quote: __(
						"The results speak for themselves. Our online presence has never been stronger, and our ROI has increased significantly.",
						"hog-scaffold"
					),
					name: __("David Thompson", "hog-scaffold"),
					role: __("CEO", "hog-scaffold"),
					company: __("Growth Dynamics", "hog-scaffold"),
					image: {},
					rating: 5,
				},
				{
					quote: __(
						"Professional, reliable, and innovative. They delivered exactly what we needed and more. Highly recommended!",
						"hog-scaffold"
					),
					name: __("Lisa Wang", "hog-scaffold"),
					role: __("Product Manager", "hog-scaffold"),
					company: __("Innovation Labs", "hog-scaffold"),
					image: {},
					rating: 5,
				},
			],
			layout: "carousel",
			showRating: true,
			showCompany: true,
			autoplay: true,
			autoplaySpeed: 5000,
		},
	},
});
