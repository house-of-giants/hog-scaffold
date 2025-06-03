/**
 * Container Block Save Component
 *
 * @package HoGScaffold\Blocks\Layout
 */

import { useBlockProps, InnerBlocks } from "@wordpress/block-editor";
import {
	getSpacingClasses,
	getSpacingStyles,
	getBackgroundStyles,
	getBackgroundClasses,
	getOverlayStyles,
	needsRelativePositioning,
} from "../../utils/index.js";

/**
 * Container Block Save Component
 *
 * @param {Object} props Block props
 */
export default function Save({ attributes }) {
	const { width, spacing, background, contentAlignment, minHeight } =
		attributes;

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

	const blockProps = useBlockProps.save({
		className: containerClasses,
		style: containerStyles,
	});

	return (
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
				<InnerBlocks.Content />
			</div>
		</div>
	);
}
