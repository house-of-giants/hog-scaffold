/**
 * Template Block Save Component
 *
 * @package HoGScaffold\Blocks\Layout
 */

import { useBlockProps } from "@wordpress/block-editor";
import {
	getSpacingClasses,
	getSpacingStyles,
	getBackgroundStyles,
	getBackgroundClasses,
	getOverlayStyles,
	needsRelativePositioning,
} from "../../utils/index.js";

/**
 * Template Block Save Component
 *
 * @param {Object} props Block props
 */
export default function Save({ attributes }) {
	const { exampleAttribute, spacing, background } = attributes;

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

	const blockProps = useBlockProps.save({
		className: blockClasses,
		style: blockStyles,
	});

	return (
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
					<InnerBlocks.Content />
					
					For blocks with custom content, add your JSX here
				*/}
				<p>Template Block Content - {exampleAttribute}</p>
			</div>
		</div>
	);
}
