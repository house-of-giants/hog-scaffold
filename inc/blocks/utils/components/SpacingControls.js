/**
 * Spacing Controls Component
 *
 * Provides consistent spacing controls for margin and padding
 *
 * @package HoGScaffold\Blocks\Utils
 */

import { __ } from "@wordpress/i18n";
import {
	PanelBody,
	RangeControl,
	SelectControl,
	ToggleControl,
} from "@wordpress/components";

/**
 * Spacing Controls Component
 *
 * @param {Object} props Component props
 * @param {Object} props.values Current spacing values
 * @param {Function} props.onChange Callback when values change
 * @param {string} props.title Panel title
 * @param {boolean} props.showMargin Whether to show margin controls
 * @param {boolean} props.showPadding Whether to show padding controls
 */
export default function SpacingControls({
	values = {},
	onChange,
	title = __("Spacing", "hog-scaffold"),
	showMargin = true,
	showPadding = true,
}) {
	const spacingOptions = [
		{ label: __("None", "hog-scaffold"), value: "none" },
		{ label: __("Small", "hog-scaffold"), value: "small" },
		{ label: __("Medium", "hog-scaffold"), value: "medium" },
		{ label: __("Large", "hog-scaffold"), value: "large" },
		{ label: __("Extra Large", "hog-scaffold"), value: "xl" },
		{ label: __("Custom", "hog-scaffold"), value: "custom" },
	];

	const {
		marginTop = "medium",
		marginBottom = "medium",
		marginLeft = "none",
		marginRight = "none",
		paddingTop = "medium",
		paddingBottom = "medium",
		paddingLeft = "medium",
		paddingRight = "medium",
		customMarginTop = 0,
		customMarginBottom = 0,
		customMarginLeft = 0,
		customMarginRight = 0,
		customPaddingTop = 0,
		customPaddingBottom = 0,
		customPaddingLeft = 0,
		customPaddingRight = 0,
		linkSpacing = false,
	} = values;

	const updateValue = (key, value) => {
		onChange({
			...values,
			[key]: value,
		});
	};

	return (
		<PanelBody title={title} initialOpen={false}>
			<ToggleControl
				label={__("Link spacing values", "hog-scaffold")}
				checked={linkSpacing}
				onChange={(value) => updateValue("linkSpacing", value)}
				help={__(
					"When enabled, all spacing values will be synchronized",
					"hog-scaffold"
				)}
			/>

			{showMargin && (
				<>
					<h4>{__("Margin", "hog-scaffold")}</h4>
					<SelectControl
						label={__("Margin Top", "hog-scaffold")}
						value={marginTop}
						options={spacingOptions}
						onChange={(value) => {
							if (linkSpacing) {
								updateValue("marginTop", value);
								updateValue("marginBottom", value);
								updateValue("marginLeft", value);
								updateValue("marginRight", value);
							} else {
								updateValue("marginTop", value);
							}
						}}
					/>
					{marginTop === "custom" && (
						<RangeControl
							label={__("Custom Margin Top (px)", "hog-scaffold")}
							value={customMarginTop}
							onChange={(value) => updateValue("customMarginTop", value)}
							min={0}
							max={200}
						/>
					)}

					{!linkSpacing && (
						<>
							<SelectControl
								label={__("Margin Bottom", "hog-scaffold")}
								value={marginBottom}
								options={spacingOptions}
								onChange={(value) => updateValue("marginBottom", value)}
							/>
							{marginBottom === "custom" && (
								<RangeControl
									label={__("Custom Margin Bottom (px)", "hog-scaffold")}
									value={customMarginBottom}
									onChange={(value) => updateValue("customMarginBottom", value)}
									min={0}
									max={200}
								/>
							)}

							<SelectControl
								label={__("Margin Left", "hog-scaffold")}
								value={marginLeft}
								options={spacingOptions}
								onChange={(value) => updateValue("marginLeft", value)}
							/>
							{marginLeft === "custom" && (
								<RangeControl
									label={__("Custom Margin Left (px)", "hog-scaffold")}
									value={customMarginLeft}
									onChange={(value) => updateValue("customMarginLeft", value)}
									min={0}
									max={200}
								/>
							)}

							<SelectControl
								label={__("Margin Right", "hog-scaffold")}
								value={marginRight}
								options={spacingOptions}
								onChange={(value) => updateValue("marginRight", value)}
							/>
							{marginRight === "custom" && (
								<RangeControl
									label={__("Custom Margin Right (px)", "hog-scaffold")}
									value={customMarginRight}
									onChange={(value) => updateValue("customMarginRight", value)}
									min={0}
									max={200}
								/>
							)}
						</>
					)}
				</>
			)}

			{showPadding && (
				<>
					<h4>{__("Padding", "hog-scaffold")}</h4>
					<SelectControl
						label={__("Padding Top", "hog-scaffold")}
						value={paddingTop}
						options={spacingOptions}
						onChange={(value) => {
							if (linkSpacing) {
								updateValue("paddingTop", value);
								updateValue("paddingBottom", value);
								updateValue("paddingLeft", value);
								updateValue("paddingRight", value);
							} else {
								updateValue("paddingTop", value);
							}
						}}
					/>
					{paddingTop === "custom" && (
						<RangeControl
							label={__("Custom Padding Top (px)", "hog-scaffold")}
							value={customPaddingTop}
							onChange={(value) => updateValue("customPaddingTop", value)}
							min={0}
							max={200}
						/>
					)}

					{!linkSpacing && (
						<>
							<SelectControl
								label={__("Padding Bottom", "hog-scaffold")}
								value={paddingBottom}
								options={spacingOptions}
								onChange={(value) => updateValue("paddingBottom", value)}
							/>
							{paddingBottom === "custom" && (
								<RangeControl
									label={__("Custom Padding Bottom (px)", "hog-scaffold")}
									value={customPaddingBottom}
									onChange={(value) =>
										updateValue("customPaddingBottom", value)
									}
									min={0}
									max={200}
								/>
							)}

							<SelectControl
								label={__("Padding Left", "hog-scaffold")}
								value={paddingLeft}
								options={spacingOptions}
								onChange={(value) => updateValue("paddingLeft", value)}
							/>
							{paddingLeft === "custom" && (
								<RangeControl
									label={__("Custom Padding Left (px)", "hog-scaffold")}
									value={customPaddingLeft}
									onChange={(value) => updateValue("customPaddingLeft", value)}
									min={0}
									max={200}
								/>
							)}

							<SelectControl
								label={__("Padding Right", "hog-scaffold")}
								value={paddingRight}
								options={spacingOptions}
								onChange={(value) => updateValue("paddingRight", value)}
							/>
							{paddingRight === "custom" && (
								<RangeControl
									label={__("Custom Padding Right (px)", "hog-scaffold")}
									value={customPaddingRight}
									onChange={(value) => updateValue("customPaddingRight", value)}
									min={0}
									max={200}
								/>
							)}
						</>
					)}
				</>
			)}
		</PanelBody>
	);
}
