/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
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
} from "@wordpress/components";

/**
 * Edit component for Hero Block
 *
 * @param {Object}   props               The block props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Sets the value for block attributes.
 * @return {Function} Render the edit screen
 */
const HeroBlockEdit = ({ attributes, setAttributes }) => {
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

	const blockProps = useBlockProps({
		className: `wp-block-hero is-style-${layout}`,
		style: {
			minHeight: minHeight,
			backgroundImage: backgroundImage?.url
				? `url(${backgroundImage.url})`
				: "none",
		},
	});

	const onSelectImage = (media) => {
		setAttributes({
			backgroundImage: {
				id: media.id,
				url: media.url,
				alt: media.alt,
			},
		});
	};

	const removeImage = () => {
		setAttributes({
			backgroundImage: {},
		});
	};

	const updatePrimaryButton = (key, value) => {
		setAttributes({
			primaryButton: {
				...primaryButton,
				[key]: value,
			},
		});
	};

	const updateSecondaryButton = (key, value) => {
		setAttributes({
			secondaryButton: {
				...secondaryButton,
				[key]: value,
			},
		});
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
							{
								label: __("Left Aligned", "hog-scaffold"),
								value: "left-aligned",
							},
							{ label: __("Split", "hog-scaffold"), value: "split" },
							{ label: __("Minimal", "hog-scaffold"), value: "minimal" },
						]}
						onChange={(value) => setAttributes({ layout: value })}
					/>
					<TextControl
						label={__("Minimum Height", "hog-scaffold")}
						value={minHeight}
						onChange={(value) => setAttributes({ minHeight: value })}
						help={__("CSS value (e.g., 60vh, 500px)", "hog-scaffold")}
					/>
				</PanelBody>

				<PanelBody
					title={__("Background Settings", "hog-scaffold")}
					initialOpen={false}
				>
					<MediaUploadCheck>
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
												alt={backgroundImage.alt || ""}
												style={{ width: "100%", height: "auto" }}
											/>
											<Button
												onClick={removeImage}
												isDestructive
												style={{ marginTop: "10px" }}
											>
												{__("Remove Image", "hog-scaffold")}
											</Button>
										</div>
									) : (
										<Button onClick={open} isPrimary>
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
					/>

					<ToggleControl
						label={__("Show Overlay", "hog-scaffold")}
						checked={showOverlay}
						onChange={(value) => setAttributes({ showOverlay: value })}
					/>

					{showOverlay && (
						<RangeControl
							label={__("Overlay Opacity", "hog-scaffold")}
							value={overlayOpacity}
							onChange={(value) => setAttributes({ overlayOpacity: value })}
							min={0}
							max={1}
							step={0.1}
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
					/>
					<TextControl
						label={__("Button URL", "hog-scaffold")}
						value={primaryButton.url}
						onChange={(value) => updatePrimaryButton("url", value)}
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
					/>
					<TextControl
						label={__("Button URL", "hog-scaffold")}
						value={secondaryButton.url}
						onChange={(value) => updateSecondaryButton("url", value)}
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
					/>
				)}
				{showOverlay && (
					<div
						className="wp-block-hero__overlay"
						style={{ backgroundColor: `rgba(0, 0, 0, ${overlayOpacity})` }}
					/>
				)}
				<div className="wp-block-hero__content">
					<RichText
						tagName="h1"
						className="wp-block-hero__title"
						placeholder={__("Enter hero heading...", "hog-scaffold")}
						value={heading}
						onChange={(value) => setAttributes({ heading: value })}
					/>
					<RichText
						tagName="p"
						className="wp-block-hero__subtitle"
						placeholder={__("Enter hero subheading...", "hog-scaffold")}
						value={subheading}
						onChange={(value) => setAttributes({ subheading: value })}
					/>
					<div className="wp-block-hero__actions">
						{primaryButton.text && (
							<a
								href={primaryButton.url}
								className="wp-block-button__link wp-block-button__link--primary"
								target={primaryButton.opensInNewTab ? "_blank" : "_self"}
								rel={primaryButton.opensInNewTab ? "noopener noreferrer" : ""}
							>
								{primaryButton.text}
							</a>
						)}
						{secondaryButton.text && (
							<a
								href={secondaryButton.url}
								className="wp-block-button__link wp-block-button__link--secondary"
								target={secondaryButton.opensInNewTab ? "_blank" : "_self"}
								rel={secondaryButton.opensInNewTab ? "noopener noreferrer" : ""}
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

export default HeroBlockEdit;
