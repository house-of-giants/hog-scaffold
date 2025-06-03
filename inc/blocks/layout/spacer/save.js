/**
 * Spacer Block Save Component
 *
 * @package HoGScaffold\Blocks\Layout
 */

import { useBlockProps } from "@wordpress/block-editor";

/**
 * Spacer Block Save Component
 *
 * @param {Object} props Block props
 */
export default function Save({ attributes }) {
	const { height, customHeight, mobileHeight, customMobileHeight } = attributes;

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
	};

	// Add mobile height as CSS custom property for responsive behavior
	if (
		mobileHeight !== height ||
		(mobileHeight === "custom" && customMobileHeight !== customHeight)
	) {
		spacerStyles["--mobile-height"] = getHeightValue(
			mobileHeight,
			customMobileHeight
		);
	}

	const blockProps = useBlockProps.save({
		className: `hog-spacer hog-spacer--${height} hog-spacer--mobile-${mobileHeight}`,
		style: spacerStyles,
	});

	return <div {...blockProps} aria-hidden="true" />;
}
