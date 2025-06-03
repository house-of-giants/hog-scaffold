/**
 * Divider Block Edit Component
 *
 * @package HoGScaffold\Blocks\Layout
 */

import { __ } from "@wordpress/i18n";
import {
	InspectorControls,
	useBlockProps,
	ColorPalette,
	useSettings,
} from "@wordpress/block-editor";
import { PanelBody, SelectControl, RangeControl } from "@wordpress/components";

/**
 * Divider Block Edit Component
 *
 * @param {Object} props Block props
 */
export default function Edit({ attributes, setAttributes }) {
	const { style, width, height, color, spacing } = attributes;

	const [colors] = useSettings("color.palette");

	const updateAttribute = (key, value) => {
		setAttributes({ [key]: value });
	};

	const updateSpacing = (key, value) => {
		setAttributes({
			spacing: {
				...spacing,
				[key]: value,
			},
		});
	};

	const styleOptions = [
		{ label: __("Solid", "hog-scaffold"), value: "solid" },
		{ label: __("Dashed", "hog-scaffold"), value: "dashed" },
		{ label: __("Dotted", "hog-scaffold"), value: "dotted" },
		{ label: __("Double", "hog-scaffold"), value: "double" },
	];

	const spacingOptions = [
		{ label: __("None", "hog-scaffold"), value: "none" },
		{ label: __("Small", "hog-scaffold"), value: "small" },
		{ label: __("Medium", "hog-scaffold"), value: "medium" },
		{ label: __("Large", "hog-scaffold"), value: "large" },
	];

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

	const blockProps = useBlockProps({
		className: `hog-divider hog-divider--${style}`,
		style: {
			textAlign: "center",
			margin: `${getSpacingValue(spacing.marginTop)} 0 ${getSpacingValue(spacing.marginBottom)} 0`,
		},
	});

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={__("Divider Settings", "hog-scaffold")}
					initialOpen={true}
				>
					<SelectControl
						label={__("Style", "hog-scaffold")}
						value={style}
						options={styleOptions}
						onChange={(value) => updateAttribute("style", value)}
					/>

					<RangeControl
						label={__("Width (%)", "hog-scaffold")}
						value={parseInt(width)}
						onChange={(value) => updateAttribute("width", value.toString())}
						min={10}
						max={100}
						step={5}
					/>

					<RangeControl
						label={__("Height (px)", "hog-scaffold")}
						value={height}
						onChange={(value) => updateAttribute("height", value)}
						min={1}
						max={20}
						step={1}
					/>

					<h4>{__("Color", "hog-scaffold")}</h4>
					<ColorPalette
						colors={colors}
						value={color}
						onChange={(value) => updateAttribute("color", value || "#ddd")}
						clearable={false}
					/>
				</PanelBody>

				<PanelBody title={__("Spacing", "hog-scaffold")} initialOpen={false}>
					<SelectControl
						label={__("Top Margin", "hog-scaffold")}
						value={spacing.marginTop}
						options={spacingOptions}
						onChange={(value) => updateSpacing("marginTop", value)}
					/>

					<SelectControl
						label={__("Bottom Margin", "hog-scaffold")}
						value={spacing.marginBottom}
						options={spacingOptions}
						onChange={(value) => updateSpacing("marginBottom", value)}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<hr style={dividerStyles} />
			</div>
		</>
	);
}
