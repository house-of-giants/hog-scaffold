/**
 * Container Block Edit Component
 *
 * @package HoGScaffold\Blocks\Layout
 */

import { __ } from "@wordpress/i18n";
import {
	InspectorControls,
	useBlockProps,
	InnerBlocks,
	BlockControls,
	AlignmentToolbar,
} from "@wordpress/block-editor";
import { PanelBody, SelectControl, TextControl } from "@wordpress/components";
import { SpacingControls, BackgroundControls } from "../../utils/index.js";
import {
	getSpacingClasses,
	getSpacingStyles,
	getBackgroundStyles,
	getBackgroundClasses,
	getOverlayStyles,
	needsRelativePositioning,
} from "../../utils/index.js";

/**
 * Container Block Edit Component
 *
 * @param {Object} props Block props
 */
export default function Edit({ attributes, setAttributes }) {
	const { width, spacing, background, contentAlignment, minHeight } =
		attributes;

	const updateAttribute = (key, value) => {
		setAttributes({ [key]: value });
	};

	const widthOptions = [
		{ label: __("Narrow", "hog-scaffold"), value: "narrow" },
		{ label: __("Standard", "hog-scaffold"), value: "standard" },
		{ label: __("Wide", "hog-scaffold"), value: "wide" },
		{ label: __("Full Width", "hog-scaffold"), value: "full" },
	];

	const alignmentOptions = [
		{ label: __("Left", "hog-scaffold"), value: "left" },
		{ label: __("Center", "hog-scaffold"), value: "center" },
		{ label: __("Right", "hog-scaffold"), value: "right" },
	];

	// Build CSS classes
	const containerClasses = [
		"hog-container",
		`hog-container--width-${width}`,
		`hog-container--align-${contentAlignment}`,
		getSpacingClasses(spacing),
		getBackgroundClasses(background),
	]
		.filter(Boolean)
		.join(" ");

	// Build inline styles
	const containerStyles = {
		...getSpacingStyles(spacing),
		...getBackgroundStyles(background),
		...(minHeight && { minHeight }),
		...(needsRelativePositioning(background) && { position: "relative" }),
	};

	const overlayStyles = getOverlayStyles(background);

	const blockProps = useBlockProps({
		className: containerClasses,
		style: containerStyles,
	});

	return (
		<>
			<BlockControls>
				<AlignmentToolbar
					value={contentAlignment}
					onChange={(value) => updateAttribute("contentAlignment", value)}
				/>
			</BlockControls>

			<InspectorControls>
				<PanelBody
					title={__("Container Settings", "hog-scaffold")}
					initialOpen={true}
				>
					<SelectControl
						label={__("Container Width", "hog-scaffold")}
						value={width}
						options={widthOptions}
						onChange={(value) => updateAttribute("width", value)}
						help={__("Choose how wide the container should be", "hog-scaffold")}
					/>

					<SelectControl
						label={__("Content Alignment", "hog-scaffold")}
						value={contentAlignment}
						options={alignmentOptions}
						onChange={(value) => updateAttribute("contentAlignment", value)}
						help={__("Align the content within the container", "hog-scaffold")}
					/>

					<TextControl
						label={__("Minimum Height", "hog-scaffold")}
						value={minHeight}
						onChange={(value) => updateAttribute("minHeight", value)}
						help={__(
							"Set a minimum height (e.g., 300px, 50vh)",
							"hog-scaffold"
						)}
						placeholder="e.g., 300px or 50vh"
					/>
				</PanelBody>

				<SpacingControls
					values={spacing}
					onChange={(value) => updateAttribute("spacing", value)}
				/>

				<BackgroundControls
					values={background}
					onChange={(value) => updateAttribute("background", value)}
				/>
			</InspectorControls>

			<div {...blockProps}>
				{/* Overlay element */}
				{background.overlay && background.overlayColor && (
					<div
						className="hog-container__overlay"
						style={overlayStyles}
						aria-hidden="true"
					/>
				)}

				{/* Content wrapper */}
				<div
					className="hog-container__content"
					style={{ position: "relative", zIndex: 2 }}
				>
					<InnerBlocks
						templateLock={false}
						placeholder={__("Add blocks to this container...", "hog-scaffold")}
					/>
				</div>
			</div>
		</>
	);
}
