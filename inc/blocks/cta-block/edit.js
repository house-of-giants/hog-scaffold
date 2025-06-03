import { __ } from "@wordpress/i18n";
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
	RichText,
	URLInput,
	ColorPalette,
	GradientPicker,
} from "@wordpress/block-editor";
import {
	PanelBody,
	SelectControl,
	ToggleControl,
	Button,
	BaseControl,
} from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const {
		heading,
		text,
		buttonText,
		buttonUrl,
		buttonStyle,
		buttonSize,
		buttonOpenInNewTab,
		layout,
		backgroundType,
		backgroundColor,
		backgroundGradient,
		backgroundImage,
		backgroundPosition,
		backgroundSize,
		backgroundOverlay,
		backgroundOverlayColor,
		width,
		spacing,
	} = attributes;

	const blockProps = useBlockProps({
		className: `is-layout-${layout} background-type-${backgroundType} spacing-${spacing} width-${width}`,
		style: {
			...(backgroundType === "color" &&
				backgroundColor && {
					backgroundColor,
				}),
			...(backgroundType === "gradient" &&
				backgroundGradient && {
					background: backgroundGradient,
				}),
			...(backgroundType === "image" &&
				backgroundImage.url && {
					backgroundImage: `url(${backgroundImage.url})`,
					backgroundPosition,
					backgroundSize,
					backgroundRepeat: "no-repeat",
				}),
		},
	});

	const overlayStyle = {
		...(backgroundOverlay && {
			backgroundColor: backgroundOverlayColor,
		}),
	};

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={__("Layout Settings", "hog-scaffold")}
					initialOpen={true}
				>
					<SelectControl
						label={__("Layout", "hog-scaffold")}
						value={layout}
						options={[
							{ label: __("Centered", "hog-scaffold"), value: "centered" },
							{ label: __("Left Aligned", "hog-scaffold"), value: "left" },
							{ label: __("Right Aligned", "hog-scaffold"), value: "right" },
							{ label: __("Split Layout", "hog-scaffold"), value: "split" },
						]}
						onChange={(value) => setAttributes({ layout: value })}
					/>

					<SelectControl
						label={__("Width", "hog-scaffold")}
						value={width}
						options={[
							{ label: __("Normal", "hog-scaffold"), value: "normal" },
							{ label: __("Wide", "hog-scaffold"), value: "wide" },
							{ label: __("Full Width", "hog-scaffold"), value: "full" },
						]}
						onChange={(value) => setAttributes({ width: value })}
					/>

					<SelectControl
						label={__("Spacing", "hog-scaffold")}
						value={spacing}
						options={[
							{ label: __("Small", "hog-scaffold"), value: "small" },
							{ label: __("Medium", "hog-scaffold"), value: "medium" },
							{ label: __("Large", "hog-scaffold"), value: "large" },
							{
								label: __("Extra Large", "hog-scaffold"),
								value: "extra-large",
							},
						]}
						onChange={(value) => setAttributes({ spacing: value })}
					/>
				</PanelBody>

				<PanelBody
					title={__("Button Settings", "hog-scaffold")}
					initialOpen={false}
				>
					<SelectControl
						label={__("Button Style", "hog-scaffold")}
						value={buttonStyle}
						options={[
							{ label: __("Primary", "hog-scaffold"), value: "primary" },
							{ label: __("Secondary", "hog-scaffold"), value: "secondary" },
							{ label: __("Outline", "hog-scaffold"), value: "outline" },
							{ label: __("Link", "hog-scaffold"), value: "link" },
						]}
						onChange={(value) => setAttributes({ buttonStyle: value })}
					/>

					<SelectControl
						label={__("Button Size", "hog-scaffold")}
						value={buttonSize}
						options={[
							{ label: __("Small", "hog-scaffold"), value: "small" },
							{ label: __("Medium", "hog-scaffold"), value: "medium" },
							{ label: __("Large", "hog-scaffold"), value: "large" },
						]}
						onChange={(value) => setAttributes({ buttonSize: value })}
					/>

					<BaseControl label={__("Button URL", "hog-scaffold")}>
						<URLInput
							value={buttonUrl}
							onChange={(value) => setAttributes({ buttonUrl: value })}
							placeholder={__("Enter URL...", "hog-scaffold")}
						/>
					</BaseControl>

					<ToggleControl
						label={__("Open in New Tab", "hog-scaffold")}
						checked={buttonOpenInNewTab}
						onChange={(value) => setAttributes({ buttonOpenInNewTab: value })}
					/>
				</PanelBody>

				<PanelBody
					title={__("Background Settings", "hog-scaffold")}
					initialOpen={false}
				>
					<SelectControl
						label={__("Background Type", "hog-scaffold")}
						value={backgroundType}
						options={[
							{ label: __("None", "hog-scaffold"), value: "none" },
							{ label: __("Color", "hog-scaffold"), value: "color" },
							{ label: __("Gradient", "hog-scaffold"), value: "gradient" },
							{ label: __("Image", "hog-scaffold"), value: "image" },
						]}
						onChange={(value) => setAttributes({ backgroundType: value })}
					/>

					{backgroundType === "color" && (
						<BaseControl label={__("Background Color", "hog-scaffold")}>
							<ColorPalette
								value={backgroundColor}
								onChange={(value) => setAttributes({ backgroundColor: value })}
							/>
						</BaseControl>
					)}

					{backgroundType === "gradient" && (
						<BaseControl label={__("Background Gradient", "hog-scaffold")}>
							<GradientPicker
								value={backgroundGradient}
								onChange={(value) =>
									setAttributes({ backgroundGradient: value })
								}
							/>
						</BaseControl>
					)}

					{backgroundType === "image" && (
						<>
							<BaseControl label={__("Background Image", "hog-scaffold")}>
								<MediaUploadCheck>
									<MediaUpload
										onSelect={(media) =>
											setAttributes({
												backgroundImage: {
													url: media.url,
													alt: media.alt,
													id: media.id,
												},
											})
										}
										allowedTypes={["image"]}
										value={backgroundImage.id}
										render={({ open }) => (
											<Button
												onClick={open}
												variant={backgroundImage.url ? "secondary" : "primary"}
											>
												{backgroundImage.url
													? __("Replace Image", "hog-scaffold")
													: __("Select Image", "hog-scaffold")}
											</Button>
										)}
									/>
								</MediaUploadCheck>
							</BaseControl>

							{backgroundImage.url && (
								<>
									<SelectControl
										label={__("Background Position", "hog-scaffold")}
										value={backgroundPosition}
										options={[
											{
												label: __("Center Center", "hog-scaffold"),
												value: "center center",
											},
											{
												label: __("Center Top", "hog-scaffold"),
												value: "center top",
											},
											{
												label: __("Center Bottom", "hog-scaffold"),
												value: "center bottom",
											},
											{
												label: __("Left Center", "hog-scaffold"),
												value: "left center",
											},
											{
												label: __("Right Center", "hog-scaffold"),
												value: "right center",
											},
										]}
										onChange={(value) =>
											setAttributes({ backgroundPosition: value })
										}
									/>

									<SelectControl
										label={__("Background Size", "hog-scaffold")}
										value={backgroundSize}
										options={[
											{ label: __("Cover", "hog-scaffold"), value: "cover" },
											{
												label: __("Contain", "hog-scaffold"),
												value: "contain",
											},
											{ label: __("Auto", "hog-scaffold"), value: "auto" },
										]}
										onChange={(value) =>
											setAttributes({ backgroundSize: value })
										}
									/>

									<ToggleControl
										label={__("Add Overlay", "hog-scaffold")}
										checked={backgroundOverlay}
										onChange={(value) =>
											setAttributes({ backgroundOverlay: value })
										}
									/>

									{backgroundOverlay && (
										<BaseControl label={__("Overlay Color", "hog-scaffold")}>
											<ColorPalette
												value={backgroundOverlayColor}
												onChange={(value) =>
													setAttributes({ backgroundOverlayColor: value })
												}
											/>
										</BaseControl>
									)}
								</>
							)}
						</>
					)}
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{backgroundOverlay &&
					backgroundType === "image" &&
					backgroundImage.url && (
						<div className="wp-block-cta__overlay" style={overlayStyle}></div>
					)}

				<div className="wp-block-cta__content">
					<div className="wp-block-cta__text-content">
						<RichText
							tagName="h2"
							className="wp-block-cta__heading"
							value={heading}
							onChange={(value) => setAttributes({ heading: value })}
							placeholder={__("Enter heading...", "hog-scaffold")}
						/>

						<RichText
							tagName="p"
							className="wp-block-cta__text"
							value={text}
							onChange={(value) => setAttributes({ text: value })}
							placeholder={__("Enter description...", "hog-scaffold")}
						/>
					</div>

					<div className="wp-block-cta__button-container">
						<RichText
							tagName="span"
							className={`wp-block-cta__button is-style-${buttonStyle} is-size-${buttonSize}`}
							value={buttonText}
							onChange={(value) => setAttributes({ buttonText: value })}
							placeholder={__("Button text...", "hog-scaffold")}
						/>
					</div>
				</div>
			</div>
		</>
	);
}
