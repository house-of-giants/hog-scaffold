/**
 * Template Block Edit Component
 *
 * @package HoGScaffold\Blocks\Layout
 */

import { __ } from "@wordpress/i18n";
import {
	InspectorControls,
	useBlockProps,
	BlockControls,
} from "@wordpress/block-editor";
import { PanelBody, TextControl } from "@wordpress/components";
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
 * Template Block Edit Component
 *
 * @param {Object} props Block props
 */
export default function Edit({ attributes, setAttributes }) {
	const { exampleAttribute, spacing, background } = attributes;

	const updateAttribute = (key, value) => {
		setAttributes({ [key]: value });
	};

	// Build CSS classes
	const blockClasses = [
		"hog-template-block",
		`hog-template-block--${exampleAttribute}`,
		getSpacingClasses(spacing),
		getBackgroundClasses(background),
	]
		.filter(Boolean)
		.join(" ");

	// Build inline styles
	const blockStyles = {
		...getSpacingStyles(spacing),
		...getBackgroundStyles(background),
		...(needsRelativePositioning(background) && { position: "relative" }),
	};

	const overlayStyles = getOverlayStyles(background);

	const blockProps = useBlockProps({
		className: blockClasses,
		style: blockStyles,
	});

	return (
		<>
			<BlockControls>{/* Add any toolbar controls here */}</BlockControls>

			<InspectorControls>
				<PanelBody
					title={__("Template Block Settings", "hog-scaffold")}
					initialOpen={true}
				>
					<TextControl
						label={__("Example Attribute", "hog-scaffold")}
						value={exampleAttribute}
						onChange={(value) => updateAttribute("exampleAttribute", value)}
						help={__(
							"This is an example attribute for demonstration",
							"hog-scaffold"
						)}
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
						className="hog-template-block__overlay"
						style={overlayStyles}
						aria-hidden="true"
					/>
				)}

				{/* Content wrapper */}
				<div
					className="hog-template-block__content"
					style={{ position: "relative", zIndex: 2 }}
				>
					{/* 
						For blocks that accept inner blocks, use:
						<InnerBlocks
							templateLock={false}
							placeholder={__("Add blocks to this template...", "hog-scaffold")}
						/>
						
						For blocks with custom content, add your JSX here
					*/}
					<p>
						{__("Template Block Content", "hog-scaffold")} - {exampleAttribute}
					</p>
				</div>
			</div>
		</>
	);
}
