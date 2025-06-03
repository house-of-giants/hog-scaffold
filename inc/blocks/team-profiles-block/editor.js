/**
 * Team Profiles Block
 * A responsive grid of team member profiles with photos, names, roles, and social links
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
			heading: __("Meet Our Team", "hog-scaffold"),
			subheading: __("The talented people behind our success", "hog-scaffold"),
			profiles: [
				{
					name: __("Sarah Johnson", "hog-scaffold"),
					role: __("CEO & Founder", "hog-scaffold"),
					bio: __(
						"Visionary leader with 15+ years of experience in technology and business strategy.",
						"hog-scaffold"
					),
					image: {},
					socialLinks: {
						linkedin: "https://linkedin.com",
						twitter: "https://twitter.com",
						email: "sarah@example.com",
					},
				},
				{
					name: __("Michael Chen", "hog-scaffold"),
					role: __("Lead Developer", "hog-scaffold"),
					bio: __(
						"Full-stack developer passionate about creating innovative solutions and clean code.",
						"hog-scaffold"
					),
					image: {},
					socialLinks: {
						linkedin: "https://linkedin.com",
						github: "https://github.com",
						email: "michael@example.com",
					},
				},
				{
					name: __("Emily Rodriguez", "hog-scaffold"),
					role: __("Design Director", "hog-scaffold"),
					bio: __(
						"Creative designer focused on user experience and beautiful, functional interfaces.",
						"hog-scaffold"
					),
					image: {},
					socialLinks: {
						linkedin: "https://linkedin.com",
						dribbble: "https://dribbble.com",
						email: "emily@example.com",
					},
				},
			],
			columns: 3,
			layout: "grid",
			showBio: true,
			showSocialLinks: true,
		},
	},
});
