/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { useCallback, useMemo } from "@wordpress/element";
import {
	useBlockProps,
	RichText,
	MediaUpload,
	MediaUploadCheck,
	InspectorControls,
} from "@wordpress/block-editor";
import {
	PanelBody,
	SelectControl,
	ToggleControl,
	RangeControl,
	Button,
	TextControl,
	CheckboxControl,
	Notice,
} from "@wordpress/components";
import { withSpokenMessages } from "@wordpress/components";

/**
 * Edit component for Hero Block
 *
 * @param {Object}   props               The block props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Sets the value for block attributes.
 * @param {Function} props.speak         Accessibility announcements.
 * @return {Function} Render the edit screen
 */
const HeroBlockEdit = ({ attributes, setAttributes, speak }) => {
	const {
		heading,
		subheading,
		layout,
		backgroundImage,
		backgroundVideo,
		showOverlay,
		overlayOpacity,
		primaryButton,
		secondaryButton,
		minHeight,
	} = attributes;

	// Memoize block props for performance
	const blockProps = useBlockProps({
		className: `wp-block-hero is-style-${layout}`,
		style: {
			minHeight: minHeight || "60vh",
			backgroundImage: backgroundImage?.url
				? `url(${backgroundImage.url})`
				: "none",
		},
	});

	// Memoized layout options to prevent unnecessary re-renders
	const layoutOptions = useMemo(
		() => [
			{ label: __("Centered", "hog-scaffold"), value: "centered" },
			{ label: __("Left Aligned", "hog-scaffold"), value: "left-aligned" },
			{ label: __("Split", "hog-scaffold"), value: "split" },
			{ label: __("Minimal", "hog-scaffold"), value: "minimal" },
		],
		[]
	);

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
	const removeImage = useCallback(() => {
		setAttributes({
			backgroundImage: {},
		});
		speak(__("Background image removed", "hog-scaffold"));
	}, [setAttributes, speak]);

	// Optimized button update handlers
	const updatePrimaryButton = useCallback(
		(key, value) => {
			setAttributes({
				primaryButton: {
					...primaryButton,
					[key]: value,
				},
			});
		},
		[primaryButton, setAttributes]
	);

	const updateSecondaryButton = useCallback(
		(key, value) => {
			setAttributes({
				secondaryButton: {
					...secondaryButton,
					[key]: value,
				},
			});
		},
		[secondaryButton, setAttributes]
	);

	// Validation for minimum height
	const isValidMinHeight = useMemo(() => {
		if (!minHeight) return true;
		const validUnits = ["px", "vh", "vw", "em", "rem", "%"];
		return (
			validUnits.some((unit) => minHeight.includes(unit)) ||
			!isNaN(parseFloat(minHeight))
		);
	}, [minHeight]);

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
					<TextControl
						label={__("Minimum Height", "hog-scaffold")}
						value={minHeight}
						onChange={(value) => setAttributes({ minHeight: value })}
						help={__("CSS value (e.g., 60vh, 500px)", "hog-scaffold")}
						className={!isValidMinHeight ? "has-error" : ""}
					/>
					{!isValidMinHeight && (
						<Notice status="warning" isDismissible={false}>
							{__("Please enter a valid CSS height value", "hog-scaffold")}
						</Notice>
					)}
				</PanelBody>

				<PanelBody
					title={__("Background Settings", "hog-scaffold")}
					initialOpen={false}
				>
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
												onClick={removeImage}
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
											aria-label={__("Select background image", "hog-scaffold")}
										>
											{__("Select Background Image", "hog-scaffold")}
										</Button>
									)}
								</div>
							)}
						/>
					</MediaUploadCheck>

					<TextControl
						label={__("Background Video URL", "hog-scaffold")}
						value={backgroundVideo}
						onChange={(value) => setAttributes({ backgroundVideo: value })}
						help={__("MP4 video URL for background", "hog-scaffold")}
						type="url"
					/>

					<ToggleControl
						label={__("Show Overlay", "hog-scaffold")}
						checked={showOverlay}
						onChange={(value) => {
							setAttributes({ showOverlay: value });
							speak(
								value
									? __("Overlay enabled", "hog-scaffold")
									: __("Overlay disabled", "hog-scaffold")
							);
						}}
					/>

					{showOverlay && (
						<RangeControl
							label={__("Overlay Opacity", "hog-scaffold")}
							value={overlayOpacity}
							onChange={(value) => setAttributes({ overlayOpacity: value })}
							min={0}
							max={1}
							step={0.1}
							help={__("Adjust overlay transparency", "hog-scaffold")}
						/>
					)}
				</PanelBody>

				<PanelBody
					title={__("Primary Button", "hog-scaffold")}
					initialOpen={false}
				>
					<TextControl
						label={__("Button Text", "hog-scaffold")}
						value={primaryButton.text}
						onChange={(value) => updatePrimaryButton("text", value)}
						placeholder={__("Enter button text", "hog-scaffold")}
					/>
					<TextControl
						label={__("Button URL", "hog-scaffold")}
						value={primaryButton.url}
						onChange={(value) => updatePrimaryButton("url", value)}
						type="url"
						placeholder={__("https://example.com", "hog-scaffold")}
					/>
					<CheckboxControl
						label={__("Open in new tab", "hog-scaffold")}
						checked={primaryButton.opensInNewTab}
						onChange={(value) => updatePrimaryButton("opensInNewTab", value)}
					/>
				</PanelBody>

				<PanelBody
					title={__("Secondary Button", "hog-scaffold")}
					initialOpen={false}
				>
					<TextControl
						label={__("Button Text", "hog-scaffold")}
						value={secondaryButton.text}
						onChange={(value) => updateSecondaryButton("text", value)}
						placeholder={__("Enter button text", "hog-scaffold")}
					/>
					<TextControl
						label={__("Button URL", "hog-scaffold")}
						value={secondaryButton.url}
						onChange={(value) => updateSecondaryButton("url", value)}
						type="url"
						placeholder={__("https://example.com", "hog-scaffold")}
					/>
					<CheckboxControl
						label={__("Open in new tab", "hog-scaffold")}
						checked={secondaryButton.opensInNewTab}
						onChange={(value) => updateSecondaryButton("opensInNewTab", value)}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				{backgroundImage?.url && (
					<div
						className="wp-block-hero__background"
						style={{ backgroundImage: `url(${backgroundImage.url})` }}
						role="img"
						aria-label={
							backgroundImage.alt || __("Hero background", "hog-scaffold")
						}
					/>
				)}
				{showOverlay && (
					<div
						className="wp-block-hero__overlay"
						style={{ backgroundColor: `rgba(0, 0, 0, ${overlayOpacity})` }}
						aria-hidden="true"
					/>
				)}
				<div className="wp-block-hero__content">
					<RichText
						tagName="h1"
						className="wp-block-hero__title"
						placeholder={__("Enter hero heading...", "hog-scaffold")}
						value={heading}
						onChange={(value) => setAttributes({ heading: value })}
						aria-label={__("Hero heading", "hog-scaffold")}
					/>
					<RichText
						tagName="p"
						className="wp-block-hero__subtitle"
						placeholder={__("Enter hero subheading...", "hog-scaffold")}
						value={subheading}
						onChange={(value) => setAttributes({ subheading: value })}
						aria-label={__("Hero subheading", "hog-scaffold")}
					/>
					<div
						className="wp-block-hero__actions"
						role="group"
						aria-label={__("Hero actions", "hog-scaffold")}
					>
						{primaryButton.text && (
							<a
								href={primaryButton.url || "#"}
								className="wp-block-button__link wp-block-button__link--primary"
								target={primaryButton.opensInNewTab ? "_blank" : "_self"}
								rel={primaryButton.opensInNewTab ? "noopener noreferrer" : ""}
								aria-label={
									primaryButton.text +
									(primaryButton.opensInNewTab
										? " " + __("(opens in new tab)", "hog-scaffold")
										: "")
								}
							>
								{primaryButton.text}
							</a>
						)}
						{secondaryButton.text && (
							<a
								href={secondaryButton.url || "#"}
								className="wp-block-button__link wp-block-button__link--secondary"
								target={secondaryButton.opensInNewTab ? "_blank" : "_self"}
								rel={secondaryButton.opensInNewTab ? "noopener noreferrer" : ""}
								aria-label={
									secondaryButton.text +
									(secondaryButton.opensInNewTab
										? " " + __("(opens in new tab)", "hog-scaffold")
										: "")
								}
							>
								{secondaryButton.text}
							</a>
						)}
					</div>
				</div>
			</div>
		</>
	);
};

export default withSpokenMessages(HeroBlockEdit);
