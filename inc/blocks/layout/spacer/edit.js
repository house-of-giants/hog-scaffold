/**
 * Spacer Block Edit Component
 *
 * @package HoGScaffold\Blocks\Layout
 */

import { __ } from "@wordpress/i18n";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import { PanelBody, SelectControl, RangeControl } from "@wordpress/components";

/**
 * Spacer Block Edit Component
 *
 * @param {Object} props Block props
 */
export default function Edit({ attributes, setAttributes }) {
	const { height, customHeight, mobileHeight, customMobileHeight } = attributes;

	const updateAttribute = (key, value) => {
		setAttributes({ [key]: value });
	};

	const heightOptions = [
		{ label: __("Small", "hog-scaffold"), value: "small" },
		{ label: __("Medium", "hog-scaffold"), value: "medium" },
		{ label: __("Large", "hog-scaffold"), value: "large" },
		{ label: __("Extra Large", "hog-scaffold"), value: "xl" },
		{ label: __("Custom", "hog-scaffold"), value: "custom" },
	];

	// Get the height value for styling
	const getHeightValue = (heightType, customValue) => {
		switch (heightType) {
			case "small":
				return "20px";
			case "medium":
				return "40px";
			case "large":
				return "60px";
			case "xl":
				return "80px";
			case "custom":
				return `${customValue}px`;
			default:
				return "40px";
		}
	};

	const spacerStyles = {
		height: getHeightValue(height, customHeight),
		backgroundColor: "#f0f0f0",
		border: "2px dashed #ccc",
		display: "flex",
		alignItems: "center",
		justifyContent: "center",
		fontSize: "12px",
		color: "#666",
		minHeight: "20px",
	};

	const blockProps = useBlockProps({
		className: `hog-spacer hog-spacer--${height}`,
		style: spacerStyles,
	});

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={__("Spacer Settings", "hog-scaffold")}
					initialOpen={true}
				>
					<SelectControl
						label={__("Desktop Height", "hog-scaffold")}
						value={height}
						options={heightOptions}
						onChange={(value) => updateAttribute("height", value)}
						help={__("Choose the height for desktop screens", "hog-scaffold")}
					/>

					{height === "custom" && (
						<RangeControl
							label={__("Custom Desktop Height (px)", "hog-scaffold")}
							value={customHeight}
							onChange={(value) => updateAttribute("customHeight", value)}
							min={10}
							max={300}
							step={5}
						/>
					)}

					<SelectControl
						label={__("Mobile Height", "hog-scaffold")}
						value={mobileHeight}
						options={heightOptions}
						onChange={(value) => updateAttribute("mobileHeight", value)}
						help={__("Choose the height for mobile screens", "hog-scaffold")}
					/>

					{mobileHeight === "custom" && (
						<RangeControl
							label={__("Custom Mobile Height (px)", "hog-scaffold")}
							value={customMobileHeight}
							onChange={(value) => updateAttribute("customMobileHeight", value)}
							min={10}
							max={200}
							step={5}
						/>
					)}
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{__("Spacer", "hog-scaffold")} ({getHeightValue(height, customHeight)})
			</div>
		</>
	);
}
