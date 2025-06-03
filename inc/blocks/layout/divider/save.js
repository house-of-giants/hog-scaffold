/**
 * Divider Block Save Component
 *
 * @package HoGScaffold\Blocks\Layout
 */

import { useBlockProps } from "@wordpress/block-editor";

/**
 * Divider Block Save Component
 *
 * @param {Object} props Block props
 */
export default function Save({ attributes }) {
	const { style, width, height, color, spacing } = attributes;

	const getSpacingValue = (spacingType) => {
		switch (spacingType) {
			case "none":
				return "0";
			case "small":
				return "1rem";
			case "medium":
				return "2rem";
			case "large":
				return "3rem";
			default:
				return "2rem";
		}
	};

	const dividerStyles = {
		width: `${width}%`,
		height: `${height}px`,
		border: "none",
		margin: `${getSpacingValue(spacing.marginTop)} auto ${getSpacingValue(spacing.marginBottom)} auto`,
		borderRadius: style === "solid" ? "0" : undefined,
		borderStyle: style !== "solid" ? style : undefined,
		borderWidth: style !== "solid" ? `${height}px` : undefined,
		borderColor: style !== "solid" ? color : undefined,
		backgroundColor: style !== "solid" ? "transparent" : color,
	};

	const blockProps = useBlockProps.save({
		className: `hog-divider hog-divider--${style}`,
		style: {
			textAlign: "center",
			margin: `${getSpacingValue(spacing.marginTop)} 0 ${getSpacingValue(spacing.marginBottom)} 0`,
		},
	});

	return (
		<div {...blockProps}>
			<hr style={dividerStyles} />
		</div>
	);
}
