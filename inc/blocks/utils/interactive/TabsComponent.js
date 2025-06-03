/**
 * Interactive Tabs Component
 *
 * Reusable tabs UI component with accessibility features
 *
 * @package HoGScaffold\Blocks\Utils\Interactive
 */

import { useState, useEffect } from "@wordpress/element";
import { __ } from "@wordpress/i18n";

/**
 * Tabs Component
 *
 * @param {Object} props - Component props
 * @param {Array} props.tabs - Array of tab objects
 * @param {number} props.activeTab - Currently active tab index
 * @param {Function} props.onTabChange - Callback when tab changes
 * @param {string} props.className - Additional CSS class
 * @param {string} props.orientation - Tabs orientation (horizontal/vertical)
 * @returns {JSX.Element} Tabs component
 */
export function TabsComponent({
	tabs = [],
	activeTab = 0,
	onTabChange,
	className = "",
	orientation = "horizontal",
}) {
	const [currentTab, setCurrentTab] = useState(activeTab);

	// Update current tab when activeTab prop changes
	useEffect(() => {
		setCurrentTab(activeTab);
	}, [activeTab]);

	/**
	 * Handle tab change
	 *
	 * @param {number} index - Tab index
	 */
	const handleTabChange = (index) => {
		setCurrentTab(index);
		if (onTabChange) {
			onTabChange(index);
		}
	};

	/**
	 * Handle keyboard navigation
	 *
	 * @param {KeyboardEvent} event - Keyboard event
	 * @param {number} index - Current tab index
	 */
	const handleKeyDown = (event, index) => {
		let newIndex = index;

		switch (event.key) {
			case "ArrowRight":
			case "ArrowDown":
				event.preventDefault();
				newIndex = (index + 1) % tabs.length;
				break;
			case "ArrowLeft":
			case "ArrowUp":
				event.preventDefault();
				newIndex = (index - 1 + tabs.length) % tabs.length;
				break;
			case "Home":
				event.preventDefault();
				newIndex = 0;
				break;
			case "End":
				event.preventDefault();
				newIndex = tabs.length - 1;
				break;
			default:
				return;
		}

		handleTabChange(newIndex);

		// Focus the new tab
		const tabButton = event.target.parentElement.children[newIndex];
		if (tabButton) {
			tabButton.focus();
		}
	};

	if (!tabs.length) {
		return null;
	}

	return (
		<div className={`interactive-tabs ${orientation} ${className}`}>
			<div
				className="tabs-navigation"
				role="tablist"
				aria-orientation={orientation}
			>
				{tabs.map((tab, index) => (
					<button
						key={index}
						className={`tab-button ${index === currentTab ? "active" : ""}`}
						role="tab"
						aria-selected={index === currentTab}
						aria-controls={`tabpanel-${index}`}
						id={`tab-${index}`}
						tabIndex={index === currentTab ? 0 : -1}
						onClick={() => handleTabChange(index)}
						onKeyDown={(event) => handleKeyDown(event, index)}
					>
						{tab.title || __("Untitled Tab", "hog-scaffold")}
					</button>
				))}
			</div>

			<div className="tabs-content">
				{tabs.map((tab, index) => (
					<div
						key={index}
						className={`tab-panel ${index === currentTab ? "active" : ""}`}
						role="tabpanel"
						id={`tabpanel-${index}`}
						aria-labelledby={`tab-${index}`}
						hidden={index !== currentTab}
					>
						<div className="tab-content">
							{tab.content || __("No content", "hog-scaffold")}
						</div>
					</div>
				))}
			</div>
		</div>
	);
}

/**
 * Tab Editor Component - for use in block editor
 *
 * @param {Object} props - Component props
 * @param {Array} props.tabs - Array of tab objects
 * @param {Function} props.onTabsChange - Callback when tabs change
 * @param {number} props.activeTab - Currently active tab
 * @param {Function} props.onActiveTabChange - Callback when active tab changes
 * @returns {JSX.Element} Tab editor component
 */
export function TabEditor({
	tabs = [],
	onTabsChange,
	activeTab = 0,
	onActiveTabChange,
}) {
	/**
	 * Add a new tab
	 */
	const addTab = () => {
		const newTabs = [
			...tabs,
			{
				title: __("New Tab", "hog-scaffold"),
				content: __("New tab content", "hog-scaffold"),
			},
		];
		onTabsChange(newTabs);
	};

	/**
	 * Remove a tab
	 *
	 * @param {number} index - Tab index to remove
	 */
	const removeTab = (index) => {
		if (tabs.length <= 1) return; // Don't remove last tab

		const newTabs = tabs.filter((_, i) => i !== index);
		onTabsChange(newTabs);

		// Adjust active tab if necessary
		if (activeTab >= newTabs.length) {
			onActiveTabChange(newTabs.length - 1);
		} else if (activeTab > index) {
			onActiveTabChange(activeTab - 1);
		}
	};

	/**
	 * Update tab content
	 *
	 * @param {number} index - Tab index
	 * @param {string} field - Field to update ('title' or 'content')
	 * @param {string} value - New value
	 */
	const updateTab = (index, field, value) => {
		const newTabs = tabs.map((tab, i) => {
			if (i === index) {
				return { ...tab, [field]: value };
			}
			return tab;
		});
		onTabsChange(newTabs);
	};

	return (
		<div className="tab-editor">
			<div className="tab-editor-controls">
				<button className="button button-secondary" onClick={addTab}>
					{__("Add Tab", "hog-scaffold")}
				</button>
			</div>

			<div className="tab-editor-list">
				{tabs.map((tab, index) => (
					<div key={index} className="tab-editor-item">
						<div className="tab-editor-header">
							<input
								type="text"
								value={tab.title}
								onChange={(e) => updateTab(index, "title", e.target.value)}
								placeholder={__("Tab title", "hog-scaffold")}
								className="tab-title-input"
							/>
							{tabs.length > 1 && (
								<button
									className="button button-link-delete"
									onClick={() => removeTab(index)}
									aria-label={__("Remove tab", "hog-scaffold")}
								>
									×
								</button>
							)}
						</div>
						<textarea
							value={tab.content}
							onChange={(e) => updateTab(index, "content", e.target.value)}
							placeholder={__("Tab content", "hog-scaffold")}
							className="tab-content-input"
							rows="4"
						/>
					</div>
				))}
			</div>
		</div>
	);
}
