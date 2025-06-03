/**
 * Background Controls Component
 *
 * Provides consistent background controls for colors, images, and patterns
 *
 * @package HoGScaffold\Blocks\Utils
 */

import { __ } from "@wordpress/i18n";
import {
	PanelBody,
	SelectControl,
	ToggleControl,
	RangeControl,
} from "@wordpress/components";
import {
	MediaUpload,
	MediaUploadCheck,
	ColorPalette,
	useSettings,
} from "@wordpress/block-editor";
import { Button } from "@wordpress/components";

/**
 * Background Controls Component
 *
 * @param {Object} props Component props
 * @param {Object} props.values Current background values
 * @param {Function} props.onChange Callback when values change
 * @param {string} props.title Panel title
 */
export default function BackgroundControls({
	values = {},
	onChange,
	title = __("Background", "hog-scaffold"),
}) {
	const [colors] = useSettings("color.palette");

	const {
		backgroundColor = "",
		backgroundImage = null,
		backgroundSize = "cover",
		backgroundPosition = "center",
		backgroundRepeat = "no-repeat",
		backgroundAttachment = "scroll",
		overlay = false,
		overlayColor = "",
		overlayOpacity = 50,
	} = values;

	const updateValue = (key, value) => {
		onChange({
			...values,
			[key]: value,
		});
	};

	const backgroundSizeOptions = [
		{ label: __("Cover", "hog-scaffold"), value: "cover" },
		{ label: __("Contain", "hog-scaffold"), value: "contain" },
		{ label: __("Auto", "hog-scaffold"), value: "auto" },
		{ label: __("100% Width", "hog-scaffold"), value: "100% auto" },
		{ label: __("100% Height", "hog-scaffold"), value: "auto 100%" },
	];

	const backgroundPositionOptions = [
		{ label: __("Center", "hog-scaffold"), value: "center" },
		{ label: __("Top Left", "hog-scaffold"), value: "top left" },
		{ label: __("Top Center", "hog-scaffold"), value: "top center" },
		{ label: __("Top Right", "hog-scaffold"), value: "top right" },
		{ label: __("Center Left", "hog-scaffold"), value: "center left" },
		{ label: __("Center Right", "hog-scaffold"), value: "center right" },
		{ label: __("Bottom Left", "hog-scaffold"), value: "bottom left" },
		{ label: __("Bottom Center", "hog-scaffold"), value: "bottom center" },
		{ label: __("Bottom Right", "hog-scaffold"), value: "bottom right" },
	];

	const backgroundRepeatOptions = [
		{ label: __("No Repeat", "hog-scaffold"), value: "no-repeat" },
		{ label: __("Repeat", "hog-scaffold"), value: "repeat" },
		{ label: __("Repeat X", "hog-scaffold"), value: "repeat-x" },
		{ label: __("Repeat Y", "hog-scaffold"), value: "repeat-y" },
	];

	const backgroundAttachmentOptions = [
		{ label: __("Scroll", "hog-scaffold"), value: "scroll" },
		{ label: __("Fixed", "hog-scaffold"), value: "fixed" },
		{ label: __("Local", "hog-scaffold"), value: "local" },
	];

	return (
		<PanelBody title={title} initialOpen={false}>
			<h4>{__("Background Color", "hog-scaffold")}</h4>
			<ColorPalette
				colors={colors}
				value={backgroundColor}
				onChange={(value) => updateValue("backgroundColor", value)}
				clearable={true}
			/>

			<h4>{__("Background Image", "hog-scaffold")}</h4>
			<MediaUploadCheck>
				<MediaUpload
					onSelect={(media) => updateValue("backgroundImage", media)}
					allowedTypes={["image"]}
					value={backgroundImage?.id}
					render={({ open }) => (
						<div>
							{backgroundImage ? (
								<div>
									<img
										src={
											backgroundImage.sizes?.medium?.url || backgroundImage.url
										}
										alt={backgroundImage.alt}
										style={{ maxWidth: "100%", height: "auto" }}
									/>
									<div style={{ marginTop: "10px" }}>
										<Button isSecondary onClick={open}>
											{__("Replace Image", "hog-scaffold")}
										</Button>
										<Button
											isDestructive
											onClick={() => updateValue("backgroundImage", null)}
											style={{ marginLeft: "10px" }}
										>
											{__("Remove Image", "hog-scaffold")}
										</Button>
									</div>
								</div>
							) : (
								<Button isPrimary onClick={open}>
									{__("Select Background Image", "hog-scaffold")}
								</Button>
							)}
						</div>
					)}
				/>
			</MediaUploadCheck>

			{backgroundImage && (
				<>
					<SelectControl
						label={__("Background Size", "hog-scaffold")}
						value={backgroundSize}
						options={backgroundSizeOptions}
						onChange={(value) => updateValue("backgroundSize", value)}
					/>

					<SelectControl
						label={__("Background Position", "hog-scaffold")}
						value={backgroundPosition}
						options={backgroundPositionOptions}
						onChange={(value) => updateValue("backgroundPosition", value)}
					/>

					<SelectControl
						label={__("Background Repeat", "hog-scaffold")}
						value={backgroundRepeat}
						options={backgroundRepeatOptions}
						onChange={(value) => updateValue("backgroundRepeat", value)}
					/>

					<SelectControl
						label={__("Background Attachment", "hog-scaffold")}
						value={backgroundAttachment}
						options={backgroundAttachmentOptions}
						onChange={(value) => updateValue("backgroundAttachment", value)}
					/>

					<ToggleControl
						label={__("Add Overlay", "hog-scaffold")}
						checked={overlay}
						onChange={(value) => updateValue("overlay", value)}
						help={__(
							"Add a color overlay on top of the background image",
							"hog-scaffold"
						)}
					/>

					{overlay && (
						<>
							<h4>{__("Overlay Color", "hog-scaffold")}</h4>
							<ColorPalette
								colors={colors}
								value={overlayColor}
								onChange={(value) => updateValue("overlayColor", value)}
								clearable={true}
							/>

							<RangeControl
								label={__("Overlay Opacity", "hog-scaffold")}
								value={overlayOpacity}
								onChange={(value) => updateValue("overlayOpacity", value)}
								min={0}
								max={100}
								step={5}
							/>
						</>
					)}
				</>
			)}
		</PanelBody>
	);
}
