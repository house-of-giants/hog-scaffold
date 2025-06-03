/**
 * Save component for Hero Block
 *
 * Renders static HTML for optimal SEO performance.
 * Content is saved directly to the database for faster loading.
 *
 * @return {JSX.Element} The saved block HTML.
 */
import { useBlockProps } from "@wordpress/block-editor";

const HeroBlockSave = ({ attributes }) => {
	const {
		heading = "",
		subheading = "",
		layout = "default",
		textAlignment = "left",
		backgroundImage = null,
		backgroundVideo = "",
		showOverlay = false,
		overlayOpacity = 0.5,
		minHeight = 400,
		primaryButton = { text: "", url: "", opensInNewTab: false },
		secondaryButton = { text: "", url: "", opensInNewTab: false },
	} = attributes;

	// Build container styles
	const containerStyle = {
		minHeight: `${minHeight}px`,
		textAlign: textAlignment,
		position: "relative",
	};

	const blockProps = useBlockProps.save({
		className: `layout-${layout} text-align-${textAlignment} ${backgroundImage?.url ? "has-background-image" : ""} ${showOverlay ? "has-overlay" : ""}`,
		style: containerStyle,
	});

	return (
		<div {...blockProps}>
			{backgroundImage?.url && (
				<div
					className="wp-block-hero__background"
					style={{ backgroundImage: `url(${backgroundImage.url})` }}
					role="img"
					aria-label={backgroundImage.alt || "Hero background"}
				/>
			)}
			{backgroundVideo && (
				<video
					className="wp-block-hero__video"
					autoPlay
					muted
					loop
					playsInline
					aria-hidden="true"
				>
					<source src={backgroundVideo} type="video/mp4" />
				</video>
			)}
			{showOverlay && (
				<div
					className="wp-block-hero__overlay"
					style={{ backgroundColor: `rgba(0, 0, 0, ${overlayOpacity})` }}
					aria-hidden="true"
				/>
			)}
			<div
				className="wp-block-hero__content"
				style={{ position: "relative", zIndex: "2" }}
			>
				{heading && <h1 className="wp-block-hero__title">{heading}</h1>}
				{subheading && <p className="wp-block-hero__subtitle">{subheading}</p>}
				{(primaryButton.text || secondaryButton.text) && (
					<div
						className="wp-block-hero__actions"
						role="group"
						aria-label="Hero actions"
					>
						{primaryButton.text &&
							(primaryButton.url ? (
								<a
									href={primaryButton.url}
									className="wp-block-button__link wp-block-button__link--primary"
									target={primaryButton.opensInNewTab ? "_blank" : "_self"}
									rel={primaryButton.opensInNewTab ? "noopener noreferrer" : ""}
									aria-label={
										primaryButton.text +
										(primaryButton.opensInNewTab ? " (opens in new tab)" : "")
									}
								>
									{primaryButton.text}
								</a>
							) : (
								<span className="wp-block-button__link wp-block-button__link--primary">
									{primaryButton.text}
								</span>
							))}
						{secondaryButton.text &&
							(secondaryButton.url ? (
								<a
									href={secondaryButton.url}
									className="wp-block-button__link wp-block-button__link--secondary"
									target={secondaryButton.opensInNewTab ? "_blank" : "_self"}
									rel={
										secondaryButton.opensInNewTab ? "noopener noreferrer" : ""
									}
									aria-label={
										secondaryButton.text +
										(secondaryButton.opensInNewTab ? " (opens in new tab)" : "")
									}
								>
									{secondaryButton.text}
								</a>
							) : (
								<span className="wp-block-button__link wp-block-button__link--secondary">
									{secondaryButton.text}
								</span>
							))}
					</div>
				)}
			</div>
		</div>
	);
};

export default HeroBlockSave;
