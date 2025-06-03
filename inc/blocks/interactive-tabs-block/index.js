/**
 * Interactive Tabs Block
 *
 * Example interactive block demonstrating patterns for dynamic content,
 * validation, security, and API integration.
 *
 * @package HoGScaffold\Blocks\InteractiveTabs
 */

import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { tabs as icon } from "@wordpress/icons";

// Import components
import { TabEditor } from "../utils/interactive/TabsComponent.js";
import { validateTabs } from "../utils/interactive/validation.js";

/**
 * Block Edit Component
 *
 * @param {Object} props - Block props
 * @returns {JSX.Element} Edit component
 */
function Edit({ attributes, setAttributes }) {
	const { tabs, activeTab } = attributes;

	/**
	 * Handle tabs change
	 *
	 * @param {Array} newTabs - Updated tabs array
	 */
	const onTabsChange = (newTabs) => {
		// Validate tabs before saving
		const errors = validateTabs(newTabs);

		if (Object.keys(errors).length === 0) {
			setAttributes({ tabs: newTabs });
		} else {
			// In a real implementation, you might want to show these errors
			console.warn("Tab validation errors:", errors);
		}
	};

	/**
	 * Handle active tab change
	 *
	 * @param {number} newActiveTab - New active tab index
	 */
	const onActiveTabChange = (newActiveTab) => {
		setAttributes({ activeTab: newActiveTab });
	};

	return (
		<div className="interactive-tabs-block-editor">
			<div className="block-editor-header">
				<h3>{__("Interactive Tabs", "hog-scaffold")}</h3>
				<p className="block-description">
					{__(
						"Configure your tabbed content below. This example demonstrates interactive block patterns.",
						"hog-scaffold"
					)}
				</p>
			</div>

			<TabEditor
				tabs={tabs}
				onTabsChange={onTabsChange}
				activeTab={activeTab}
				onActiveTabChange={onActiveTabChange}
			/>

			<div className="block-editor-preview">
				<h4>{__("Preview:", "hog-scaffold")}</h4>
				<div className="tabs-preview">
					{tabs.map((tab, index) => (
						<div key={index} className="tab-preview-item">
							<strong>{tab.title}</strong>
							<div className="tab-preview-content">{tab.content}</div>
						</div>
					))}
				</div>
			</div>
		</div>
	);
}

/**
 * Block Save Component
 *
 * @returns {null} Save component (null because using PHP render callback)
 */
function Save() {
	// Return null because we're using a PHP render callback
	// This allows for dynamic content and server-side processing
	return null;
}

/**
 * Register the Interactive Tabs block
 */
registerBlockType("hog-scaffold/interactive-tabs", {
	edit: Edit,
	save: Save,
	icon: icon,
	example: {
		attributes: {
			tabs: [
				{
					title: __("Welcome", "hog-scaffold"),
					content: __(
						"Welcome to our interactive tabs example. This demonstrates essential patterns for interactive blocks.",
						"hog-scaffold"
					),
				},
				{
					title: __("Features", "hog-scaffold"),
					content: __(
						"This block includes validation, security, accessibility, and API integration patterns.",
						"hog-scaffold"
					),
				},
				{
					title: __("Documentation", "hog-scaffold"),
					content: __(
						"Check the code comments for implementation details and extension points.",
						"hog-scaffold"
					),
				},
			],
			activeTab: 0,
		},
	},
});
