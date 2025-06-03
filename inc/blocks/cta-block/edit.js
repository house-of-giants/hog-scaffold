import { __ } from "@wordpress/i18n";
import { useCallback, useMemo } from "@wordpress/element";
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
	Notice,
} from "@wordpress/components";
import { withSpokenMessages } from "@wordpress/components";

/**
 * Edit component for CTA Block
 *
 * @param {Object}   props               The block props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Sets the value for block attributes.
 * @param {Function} props.speak         Accessibility announcements.
 * @return {Function} Render the edit screen
 */
function Edit({ attributes, setAttributes, speak }) {
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

	// Memoized options to prevent unnecessary re-renders
	const layoutOptions = useMemo(
		() => [
			{ label: __("Centered", "hog-scaffold"), value: "centered" },
			{ label: __("Left Aligned", "hog-scaffold"), value: "left" },
			{ label: __("Right Aligned", "hog-scaffold"), value: "right" },
			{ label: __("Split Layout", "hog-scaffold"), value: "split" },
		],
		[]
	);

	const buttonStyleOptions = useMemo(
		() => [
			{ label: __("Primary", "hog-scaffold"), value: "primary" },
			{ label: __("Secondary", "hog-scaffold"), value: "secondary" },
			{ label: __("Outline", "hog-scaffold"), value: "outline" },
			{ label: __("Link", "hog-scaffold"), value: "link" },
		],
		[]
	);

	const buttonSizeOptions = useMemo(
		() => [
			{ label: __("Small", "hog-scaffold"), value: "small" },
			{ label: __("Medium", "hog-scaffold"), value: "medium" },
			{ label: __("Large", "hog-scaffold"), value: "large" },
		],
		[]
	);

	const widthOptions = useMemo(
		() => [
			{ label: __("Normal", "hog-scaffold"), value: "normal" },
			{ label: __("Wide", "hog-scaffold"), value: "wide" },
			{ label: __("Full Width", "hog-scaffold"), value: "full" },
		],
		[]
	);

	const spacingOptions = useMemo(
		() => [
			{ label: __("Small", "hog-scaffold"), value: "small" },
			{ label: __("Medium", "hog-scaffold"), value: "medium" },
			{ label: __("Large", "hog-scaffold"), value: "large" },
			{ label: __("Extra Large", "hog-scaffold"), value: "extra-large" },
		],
		[]
	);

	const backgroundTypeOptions = useMemo(
		() => [
			{ label: __("None", "hog-scaffold"), value: "none" },
			{ label: __("Color", "hog-scaffold"), value: "color" },
			{ label: __("Gradient", "hog-scaffold"), value: "gradient" },
			{ label: __("Image", "hog-scaffold"), value: "image" },
		],
		[]
	);

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

	// Optimized image selection handler
	const onSelectImage = useCallback(
		(media) => {
			if (!media || !media.url) {
				speak(__("Invalid image selected", "hog-scaffold"));
				return;
			}

			setAttributes({
				backgroundImage: {
					id: media.id,
					url: media.url,
					alt: media.alt || "",
				},
			});
			speak(__("Background image updated", "hog-scaffold"));
		},
		[setAttributes, speak]
	);

	// Optimized image removal handler
	const removeBackgroundImage = useCallback(() => {
		setAttributes({
			backgroundImage: {},
		});
		speak(__("Background image removed", "hog-scaffold"));
	}, [setAttributes, speak]);

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
						options={layoutOptions}
						onChange={(value) => {
							setAttributes({ layout: value });
							speak(__("Layout changed to", "hog-scaffold") + " " + value);
						}}
					/>

					<SelectControl
						label={__("Width", "hog-scaffold")}
						value={width}
						options={widthOptions}
						onChange={(value) => setAttributes({ width: value })}
					/>

					<SelectControl
						label={__("Spacing", "hog-scaffold")}
						value={spacing}
						options={spacingOptions}
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
						options={buttonStyleOptions}
						onChange={(value) => setAttributes({ buttonStyle: value })}
					/>

					<SelectControl
						label={__("Button Size", "hog-scaffold")}
						value={buttonSize}
						options={buttonSizeOptions}
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
						options={backgroundTypeOptions}
						onChange={(value) => {
							setAttributes({ backgroundType: value });
							speak(
								__("Background type changed to", "hog-scaffold") + " " + value
							);
						}}
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
								<MediaUploadCheck
									fallback={
										<Notice status="warning" isDismissible={false}>
											{__("Media upload permissions required", "hog-scaffold")}
										</Notice>
									}
								>
									<MediaUpload
										onSelect={onSelectImage}
										allowedTypes={["image"]}
										value={backgroundImage?.id}
										render={({ open }) => (
											<div>
												{backgroundImage?.url ? (
													<div>
														<img
															src={backgroundImage.url}
															alt={
																backgroundImage.alt ||
																__("Background image", "hog-scaffold")
															}
															style={{
																width: "100%",
																height: "auto",
																maxHeight: "200px",
																objectFit: "cover",
															}}
														/>
														<Button
															onClick={removeBackgroundImage}
															isDestructive
															style={{ marginTop: "10px" }}
															aria-label={__(
																"Remove background image",
																"hog-scaffold"
															)}
														>
															{__("Remove Image", "hog-scaffold")}
														</Button>
													</div>
												) : (
													<Button
														onClick={open}
														isPrimary
														aria-label={__(
															"Select background image",
															"hog-scaffold"
														)}
													>
														{__("Select Background Image", "hog-scaffold")}
													</Button>
												)}
											</div>
										)}
									/>
								</MediaUploadCheck>
							</BaseControl>

							<SelectControl
								label={__("Background Position", "hog-scaffold")}
								value={backgroundPosition}
								options={[
									{ label: __("Top Left", "hog-scaffold"), value: "top left" },
									{
										label: __("Top Center", "hog-scaffold"),
										value: "top center",
									},
									{
										label: __("Top Right", "hog-scaffold"),
										value: "top right",
									},
									{
										label: __("Center Left", "hog-scaffold"),
										value: "center left",
									},
									{
										label: __("Center Center", "hog-scaffold"),
										value: "center center",
									},
									{
										label: __("Center Right", "hog-scaffold"),
										value: "center right",
									},
									{
										label: __("Bottom Left", "hog-scaffold"),
										value: "bottom left",
									},
									{
										label: __("Bottom Center", "hog-scaffold"),
										value: "bottom center",
									},
									{
										label: __("Bottom Right", "hog-scaffold"),
										value: "bottom right",
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
									{ label: __("Auto", "hog-scaffold"), value: "auto" },
									{ label: __("Cover", "hog-scaffold"), value: "cover" },
									{ label: __("Contain", "hog-scaffold"), value: "contain" },
								]}
								onChange={(value) => setAttributes({ backgroundSize: value })}
							/>

							<ToggleControl
								label={__("Background Overlay", "hog-scaffold")}
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
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{backgroundOverlay && (
					<div
						className="wp-block-cta__overlay"
						style={overlayStyle}
						aria-hidden="true"
					/>
				)}
				<div className="wp-block-cta__content">
					<div className="wp-block-cta__text-content">
						<RichText
							tagName="h2"
							className="wp-block-cta__heading"
							placeholder={__("Write heading...", "hog-scaffold")}
							value={heading}
							onChange={(value) => setAttributes({ heading: value })}
							aria-label={__("CTA heading", "hog-scaffold")}
						/>
						<RichText
							tagName="p"
							className="wp-block-cta__text"
							placeholder={__("Write text...", "hog-scaffold")}
							value={text}
							onChange={(value) => setAttributes({ text: value })}
							aria-label={__("CTA text", "hog-scaffold")}
						/>
					</div>
					{buttonText && (
						<div className="wp-block-cta__button-container">
							<a
								href={buttonUrl || "#"}
								className={`wp-block-cta__button is-style-${buttonStyle} is-size-${buttonSize}`}
								target={buttonOpenInNewTab ? "_blank" : "_self"}
								rel={buttonOpenInNewTab ? "noopener noreferrer" : ""}
								aria-label={
									buttonText +
									(buttonOpenInNewTab
										? " " + __("(opens in new tab)", "hog-scaffold")
										: "")
								}
							>
								{buttonText}
							</a>
						</div>
					)}
					{!buttonText && (
						<div className="wp-block-cta__button-container">
							<Button
								isPrimary
								onClick={() =>
									setAttributes({
										buttonText: __("Click here", "hog-scaffold"),
									})
								}
							>
								{__("Add Button Text", "hog-scaffold")}
							</Button>
						</div>
					)}
				</div>
			</div>
		</>
	);
}

export default withSpokenMessages(Edit);
